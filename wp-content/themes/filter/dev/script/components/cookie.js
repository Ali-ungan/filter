// COOKIE
// ------------------------------------------------------------
import Cookies from 'js-cookie'

// COOKIE CONTENT
// ------------------------------------------------------------
var cookie = cookieContent;

if (Cookies.get('cookie') && Cookies.get('cookie') == 'accepted') { } else $('body').append(cookie);

$('.cookie-bar .accept').click(function () {
    Cookies.set('cookie', 'accepted', { expires: 7 });
    $('.cookie-bar').addClass('hide-cookie');
})