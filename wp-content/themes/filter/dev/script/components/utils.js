// FancyBox Settings ------------------------------------------------
Fancybox.defaults.placeFocusBack = 0;
Fancybox.defaults.autoFocus = false;
Fancybox.defaults.trapFocus = false;
Fancybox.defaults.dragToClose = false;

Fancybox.bind("[data-fancybox]", { infinite: false, Toolbar: { display: ["download", "close"], }, });

// Smart Menu -------------------------------------------------------
let headerHeight = document.querySelector('.header-field').offsetHeight;
let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
if (currentScrollTop >= (headerHeight / 2)) { header.classList.add('is-hidden'); header.classList.add('is-fixed'); }
else { header.classList.remove('is-hidden'); header.classList.remove('is-fixed'); }

let lastScrollTop = 0;
window.addEventListener('scroll', function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop && scrollTop > (headerHeight / 2)) { header.classList.add('is-hidden'); header.classList.add('is-fixed'); }
    else {
        if (scrollTop > (headerHeight / 2)) { header.classList.remove('is-hidden'); header.classList.add('is-fixed'); }
        else { header.classList.remove('is-hidden'); header.classList.remove('is-fixed'); }
    }
    lastScrollTop = scrollTop;
}, false);

// Mobile Menu -----------------------------------------------------
/* let mobileMenuTrigger = document.querySelector('.mobile-trigger');
mobileMenuTrigger.addEventListener('click', function () {
    if (!this.classList.contains('active')) {
        this.classList.add('active');
        document.body.style.overflowY = 'hidden';
        document.querySelector('.menu-field').style.display = 'block';
    }
    else {
        this.classList.remove('active');
        document.body.style.overflowY = 'visible';
        document.querySelector('.menu-field').style.display = 'none';
    }
}, false); */