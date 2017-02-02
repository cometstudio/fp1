$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function()
{
    hideMainNavOnScroll();

    fixContentWrapperPosition();

    smoothScroll();

    initDatepicker();

    getCommentsThread();

    initMagnificPopup();
});

$(window).on('resize', function(){
    fixContentWrapperPosition();
});

function initMagnificPopup()
{
    $('.popup-gallery').magnificPopup({
        delegate: 'a', // child items selector, by clicking on it popup will open
        type: 'image'
        // other options
    });
}

function hideMainNavOnScroll()
{
    var lastTop = 0;
    var top = 0;
    var mainNavHeight;
    var indexHeight;
    var header;

    $(window).on('scroll', function(){
        header = $('.header');
        indexHeight = header.find('.index').outerHeight();
        mainNavHeight = header.outerHeight();

        top = $(window).scrollTop();

        if((top > indexHeight) && (lastTop < top)){
            header.css('top', -1 * mainNavHeight);
        }else{
            header.css('top', 0);
        }

        lastTop = top;
    });
}

function fixContentWrapperPosition()
{
    indexHeight = $('.header').outerHeight();

    $('.content-wrapper').css('padding-top', indexHeight).show();
}

function initDatepicker()
{
    $( ".datepicker" ).datepicker({
        dateFormat: "dd.mm.yy",
        beforeShow: function ( input, inst ) {
            //console.log(this);
        }
    });
}

function submitComment()
{
    var form = $('.comments-grid form');
    var container = $('.thread-container');

    if(form.length) {
        ajaxSubmit(form, function(response){
            container.html(response.view);

            form.clearForm();
        });
    }

    return false;
}

function getCommentsThread()
{
    var input = $('input[name=_comments_thread_url]');

    if(input.length) {
        var url = input.val();
        var container = $('.thread-container');

        ajax(url, function (response) {
            container.html(response.view);
        }, null, {}, 'POST', true);
    }
}

function touchCaptcha()
{
    ajax('/captcha/touch', function (response) {
        $('#captcha-img').attr('src', response[0]);
        $('input[name=captcha]').val('');
    });
}

function login()
{
    var form = $('.login form');

    ajaxSubmit(form, null, function(response){
        touchCaptcha();
    });

    return false;
}

function forgot()
{
    var form = $('.forgot form');

    ajaxSubmit(form, null, function(response){
        touchCaptcha();
    });

    return false;
}

function saveProfile()
{
    var form = $('.profile form');

    ajaxSubmit(form, function(response){
        if(response.picture){
            $('.profile-picture-img').attr('src', response.picture);
            form.find('#profile-picture').show('fast');
        }
    });

    return false;
}

function unlinkProfilePicture(el)
{
    var form = $('.profile form');
    var url = $(el).attr('href');

    ajax(url, function(response){
        $('.profile-picture-img').attr('src', response.picture);
        form.find('#profile-picture').hide('fast');
    });

    return false;
}

function ajaxSubmit(form, callback, errorCallback, data, url)
{
    if(form.length) {

        toggleWait();

        var options = {
            data: data,
            success: function (response, status, xhr, form) {

                toggleWait();

                if (typeof(callback) == 'function') {
                    callback(response, status, xhr, form);
                } else {
                    if (response.message) {
                        alert(response.message);
                    } else if (response.location) {
                        document.location.assign(response.location);
                    }
                }
            },
            error: function (response, status, xhr, form) {

                toggleWait();

                if (typeof(errorCallback) == 'function') errorCallback(response, status, xhr, form);

                var errors = response.responseJSON;

                if (typeof(errors) != 'undefined') {

                    for (first in errors) break;

                    var errorInput = $('#' + first);

                    if (errorInput.length) {
                        errorInput.addClass('error').on('focus', function () {
                            $(this).removeClass('error');
                        });
                    }

                    alert(errors[first]);
                }
            },
            dataType: 'json',
            type: 'POST'
        };

        if (typeof(url) == 'string') options.url = url;

        form.ajaxSubmit(options);
    }
}

function ajax(url, callback, errorCallback, data, method, dontShowIndicator)
{
    if(!dontShowIndicator) toggleWait();

    method = typeof (method) == 'undefined' ? 'POST' : method;

    $.ajax(url, {
        dataType: 'json',
        data: data,
        method: method,
        success: function(response, status, jqXHR){

            if(!dontShowIndicator) toggleWait();

            if(typeof(callback) == 'function'){
                callback(response, status, jqXHR);
            }else{
                if (response.message) {
                    alert(response.message);
                } else if (response.location) {
                    document.location.assign(response.location);
                }
            }
        },
        error: function(response, status, jqXHR){
            if(!dontShowIndicator) toggleWait();

            if(typeof(errorCallback) == 'function') errorCallback(response, status, jqXHR);

            var errors = response.responseJSON;

            if(typeof(errors) != 'undefined') {

                for (first in errors) break;

                alert(errors[first]);
            }
        }
    });
}

function toggleWait()
{
    var container = $('.body-fade');
    var container1 = $('.body-wait');

    if(container1.css('display') == 'none'){
        container1.fadeIn('fast');
        setTimeout(function(){ container.fadeIn('fast'); }, 100);

    }else{
        container1.fadeOut('fast');
        setTimeout(function(){ container.fadeOut('fast'); }, 100);
    }
}

function smoothScroll(){
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 300);
                return false;
            }
        }
    });
}