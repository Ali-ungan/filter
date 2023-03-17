// ScrollReveal -----------------------------------------------------
import ScrollReveal from 'scrollreveal';
window.ScrollReveal = ScrollReveal;
ScrollReveal({ afterReveal: function(el) { el.removeAttribute('style'); el.removeAttribute('data-sr-id'); } });

ScrollReveal().reveal('.values-field .list .item', { interval: 200, distance: '60px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.values-field .heading', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.princibles-field .wrapper .text-editor', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.princibles-field .wrapper .side-title', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.info-field .info-list .info', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.about-field .image-field', { interval: 200, distance: '30px', duration: 600, origin: 'left', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.about-field .text-field', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.latest-news .headings', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.latest-news .blog-field .carousel', { interval: 200, distance: '30px', duration: 600, origin: 'left', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.latest-news .blog-field .list .blog', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.social-field .bar .title', { interval: 200, distance: '30px', duration: 600, origin: 'left', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.social-field > .wrapper > .title', { interval: 200, distance: '30px', duration: 600, origin: 'left', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.social-field .bar .list .item', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.social-field > .wrapper > .list > .item', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.prolog-field .wrapper .side:nth-child(1)', { interval: 200, distance: '30px', duration: 600, origin: 'left', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.prolog-field .wrapper .side:nth-child(2)', { interval: 200, distance: '30px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.category-field .category-list', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.fleet-list .fleet', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.table-field .table-wrapper', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.25, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 

ScrollReveal().reveal('.blog-list .news-el', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.team-field .person', { interval: 200, distance: '60px', duration: 600, origin: 'bottom', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 


ScrollReveal().reveal('.breadcrumb .page-title', { interval: 200, distance: '60px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 
ScrollReveal().reveal('.breadcrumb .page-location', { delay: 200, interval: 200, distance: '60px', duration: 600, origin: 'right', viewFactor: 0.5, easing: 'cubic-bezier(.42,.97,.52,1.49)', mobile: false }); 