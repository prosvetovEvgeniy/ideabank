$(document).ready(function () {
    $('.message-menu li a').each(function () {

        var location = (window.location.href).split('?')[0];
        var link = this.href;

        if(location === 'http://ideabank.local/message/chat')
        {
            $(this).parents('li').addClass('active');
            return false;
        }

        if(location === link)
        {
            $(this).parents('li').addClass('active');
        }
    });
});