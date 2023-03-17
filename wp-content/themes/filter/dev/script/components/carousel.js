// Swiper -----------------------------------------------------------
import Swiper, * as Carousel from 'swiper';
window.Swiper = Swiper;
window.Carousel = Carousel;

new Swiper('.featured-news-carousel', {
    modules: [Carousel.Autoplay, Carousel.Pagination, Carousel.Navigation, Carousel.Parallax],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    pagination: {
        type: 'bullets',
        el: '.featured-news-bullets',
        clickable: true,
    },
    navigation: {
        prevEl: '.featured-news-prev',
        nextEl: '.featured-news-next',
    },
});

let teamTextCarousel = new Swiper('.team-text-carousel', {
    modules: [Carousel.EffectCreative],
    speed: 750,
    slidesPerView: 1,
    spaceBetween: 30,
    resistance: true,
    resistanceRatio: 0,
    allowTouchMove: false,
    loop: true,
    effect: 'creative',
    creativeEffect: {
        prev: {
            translate: ['-50%', 0, 0],
            scale: 0.25,
            opacity: 0,
        },
        next: {
            translate: ['50%', 0, 0],
            scale: 0.5,
            opacity: 0,
        },
    },
});

new Swiper('.team-image-carousel', {
    modules: [Carousel.Autoplay, Carousel.Navigation,],
    speed: 750,
    slidesPerView: 3,
    spaceBetween: 30,
    resistance: true,
    resistanceRatio: 0,
    loop: true,
    watchSlidesProgress: true,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    navigation: {
        prevEl: '.team-prev',
        nextEl: '.team-next',
    },
    on: {
        slideChange: function() {
            teamTextCarousel.slideTo(this.realIndex)
        }
    },
    breakpoints: {
        0: { slidesPerView: 1, },
        1025: { slidesPerView: 3, },
    }
});

let galleryFeaturedCarousel = new Swiper('.gallery-featured-carousel', {
    modules: [Carousel.Parallax],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    allowTouchMove: false,
});

new Swiper('.gallery-thumbs-carousel', {
    modules: [Carousel.Autoplay, Carousel.Navigation],
    speed: 750,
    slidesPerView: 5,
    spaceBetween: 30,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    centeredSlides: true,
    loop: true,
    slideToClickedSlide: true,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    navigation: {
        prevEl: '.gallery-prev',
        nextEl: '.gallery-next',
    },
    on: {
        slideChange: function() {
            galleryFeaturedCarousel.slideTo(this.realIndex)
        }
    },
    breakpoints: {
        0: { slidesPerView: 2, spaceBetween: 15 },
        601: { slidesPerView: 3, spaceBetween: 20 },
        991: { slidesPerView: 4, spaceBetween: 30 },
        1281: { slidesPerView: 5, spaceBetween: 30 },
    }
});

new Swiper('.fleet-carousel', {
    modules: [Carousel.Autoplay, Carousel.Pagination, Carousel.Navigation, Carousel.Parallax],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    navigation: {
        prevEl: '.fleet-prev',
        nextEl: '.fleet-next',
    },
    on: {
        beforeInit: function () {
            let totalSlideNumber = this.wrapperEl.querySelectorAll(".swiper-slide").length;
            let totalField = this.el.closest('.carousel-field').querySelector('.record .total');

            if(totalField) totalField.innerHTML = totalSlideNumber;
        },
        slideChange: function(){
            var activeSlideNumber = this.realIndex + 1;
            let activeField = this.el.closest('.carousel-field').querySelector('.record .current');

            if(activeField) activeField.innerHTML = activeSlideNumber;
        },
    }
});

let heroCarousel = new Swiper('.hero-carousel', {
    modules: [Carousel.Autoplay, Carousel.Navigation, Carousel.Parallax],
    speed: 1000,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    navigation: {
        prevEl: '.hero-prev',
        nextEl: '.hero-next',
    },
    on: {
        slideChange: function() {
            let lines = document.querySelector('.hero-field .hero-thumbs .lines')
            
            lines.querySelectorAll('.group').forEach((group, index) => {
                group.classList.remove('active');
                lines.querySelectorAll('.group')[this.activeIndex].classList.add('active');
            });
        },
    }
});

let lineGroup = document.querySelectorAll('.hero-field .hero-thumbs .lines .group')
lineGroup.forEach((group, index) => {
    group.addEventListener('click', () => {
        heroCarousel.slideTo(index);
    }, false);
});

let latestTextCarousel = new Swiper('.latest-news-text-carousel', {
    modules: [Carousel.EffectCreative],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    allowTouchMove: false,
    resistanceRatio: 0,
});

new Swiper('.latest-news-image-carousel', {
    modules: [Carousel.Autoplay, Carousel.Pagination, Carousel.Navigation, Carousel.Parallax],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    pagination: {
        type: 'bullets',
        el: '.latest-news-bullets',
        clickable: true,
    },
    navigation: {
        prevEl: '.latest-news-prev',
        nextEl: '.latest-news-next',
    },
    on: {
        slideChange: function() {
            latestTextCarousel.slideTo(this.realIndex)
        }
    }
});

let currentIndex = 6;
new Swiper('.fleet-background-carousel', {
    modules: [Carousel.Autoplay, Carousel.Navigation, Carousel.Parallax],
    speed: 750,
    slidesPerView: 1,
    parallax: true,
    resistance: true,
    resistanceRatio: 0,
    direction: 'vertical',
    preventInteractionOnTransition: true,
    allowTouchMove: false,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    navigation: {
        prevEl: '.fleet-background-prev',
        nextEl: '.fleet-background-next',
    },
    on: {
        slideChange: function() {
            let lines = document.querySelector('.fleet-field .circle-field .lines');
            let line = lines.querySelectorAll('.line.has-pin')
            let lineCount = line.length;
            
            let currentSlideIndex = this.activeIndex;
            let prevSlideIndex = this.previousIndex;
            let direction = 'next';
            (currentSlideIndex > prevSlideIndex) ? direction = 'next' : direction = 'prev';
            
            let rotateTL = gsap.timeline()
            if(direction == 'next') {
                rotateTL.to(lines, { rotate: '-=40', duration: 0.75, ease: 'circleRoll', })
                currentIndex++;
                if(currentIndex >= lineCount) currentIndex = 0;
            }
            
            if(direction == 'prev') {
                rotateTL.to(lines, { rotate: '+=40', duration: 0.75, ease: 'circleRoll', }) 
                currentIndex--;
                if(currentIndex < 0) currentIndex = lineCount - 1;
            }

            line.forEach(item => {
                item.classList.remove('active');
                line[currentIndex].classList.add('active');
            });
        },
    },
});