$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){

    var containers = $( 'textarea.ck' );

    if(containers.length){
        containers.ckeditor();
    }

    var container = $( ".sortable" ).sortable({
        axis: "y",
        delay: 150,
        handle: ".grab",
        opacity: 0.5,
        tolerance: "pointer",
        stop: function( event, ui ) {
            var ids = $('.sortable').sortable('toArray');
            //console.log(ids, ui.item.attr('id'));
            var url = $('input[name=_sort_action_url]').val();
            url += '?ids=' + ids;

            ajax(url, null, null, {}, 'GET');
        }
    });

    $( ".datepicker" ).datepicker({
        dateFormat: "dd.mm.yy",
        beforeShow: function ( input, inst ) {
            //console.log(this);
        }
    });


    $( ".gallery" ).sortable({
        update: function( event, ui ) {
            sortGallery(ui);
        }
    });
});

function sortGallery(ui)
{
    var form = $('.edit form');
    var action = form.find('input[name=_gallerysort]').val();
    var galleryInput = form.find('input[name=gallery]');

    ajaxSubmit(form, function(response){
        galleryInput.val(response.gallery);
    }, null, {
        'indexes': $('.gallery').sortable('toArray')
    }, action);
}

function imageadd(el)
{
    var control = $(el);
    var relatedInput = $('input[name='+control.attr('rel')+']');
    var form = $('.edit form');
    var action = form.find('input[name=_imageadd]').val();
    var galleryInput = form.find('input[name=gallery]');
    var galleryContainer = form.find('.gallery');

    relatedInput.unbind('change');

    relatedInput.on('change', function(){
        ajaxSubmit(form, function(response){
            if(response.location){
                document.location.assign(response.location);
            }else {
                galleryInput.val(response.gallery);
                galleryContainer.html(response.part);
            }
        }, null, {}, action);
    });

    clickFileInput(relatedInput);

    return false;
}

function imagedrop(el, index)
{
    var control = $(el);
    var form = $('.edit form');
    var action = form.find('input[name=_imagedrop]').val();
    var galleryInput = form.find('input[name=gallery]');
    var galleryContainer = form.find('.gallery');

    ajaxSubmit(form, function(response){
        if(response.location){
            document.location.assign(response.location);
        }else {
            galleryInput.val(response.gallery);
            galleryContainer.html(response.part);
        }
    }, null, {
        index: index
    }, action);

    return false;
}

function showGalleryImageEditField(el, index)
{
    var edit = $('#i' + index + ' .edit');

    $('.gallery .edit').hide();
    edit.show();

    return false;
}

function saveGalleryTitles()
{
    var form = $('.edit form');
    var action = form.find('input[name=_imagetitles]').val();
    var galleryInput = form.find('input[name=gallery]');
    var galleryContainer = form.find('.gallery');

    ajaxSubmit(form, function(response){
        if(response.location){
            document.location.assign(response.location);
        }else {
            galleryInput.val(response.gallery);
            galleryContainer.html(response.part);

            galleryContainer.find('.edit').hide();
        }
    }, null, {}, action);

    return false;
}

function save()
{
    var form = $('.edit form');

    ajaxSubmit(form, function(response){
        if(response.location){
            document.location.assign(response.location);
        }

    });

    return false;
}

function drop(el)
{
    var control = $(el);
    var url = control.attr('href');

    ajax(url);

    return false;
}

function login()
{
    var form = $('.login form');

    ajaxSubmit(form);

    return false;
}

function postARequest(el)
{
    var control = $(el);
    var url = control.attr('href');

    ajax(url);

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
    var container = $('body > .wait');

    if(container.css('display') == 'none'){
        container.fadeIn('fast');
    }else{
        container.fadeOut('fast');
    }
}

function clickFileInput(target)
{
    target.click();

    return false;
}

function swap(target, control)
{
    var options = {duration: 300, easing: 'easeOutQuart'};

    target.slideDown(options);
    control.slideUp(options);

    return false;
}

function cookie(name, value)
{
    var type = typeof(value);

    if(type == 'undefined'){
        return Cookies.get('name');
    }else if(type === null){
        return Cookies.remove('name');
    }else{
        return Cookies.set('name', 'value', { expires: 7, path: '/' });
    }
}