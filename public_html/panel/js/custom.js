var timeout;

function addBinding(el, container)
{
    var control = $(el);
    var url = control.attr('href');
    var form = $('.edit form');

    ajaxSubmit(form, function(response){
        container.html(response.view)
    }, null, {}, url);

    return false;
}

function dropBinding(el, container)
{

    var control = $(el);
    var url = control.attr('href');

    ajax(url, function(response){
        container.html(response.view)
    });

    return false;
}