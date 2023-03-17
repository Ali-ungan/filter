// GSAP -------------------------------------------------------------
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger'
import SplitText from 'gsap/SplitText'
/* import ScrollSmoother from 'gsap/ScrollSmoother' */
/* import DrawSVGPlugin from 'gsap/DrawSVGPlugin' */
/* import ScrollToPlugin from 'gsap/ScrollToPlugin'; */
import CustomEase from 'gsap/CustomEase';
/* import MotionPathPlugin from 'gsap/MotionPathPlugin' */
gsap.registerPlugin(ScrollTrigger, SplitText, /* DrawSVGPlugin, ScrollToPlugin, CustomEase, MotionPathPlugin */);
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
window.CustomEase = CustomEase;
/* window.ScrollSmoother = ScrollSmoother; */
window.SplitText = SplitText;
/* window.CustomEase = CustomEase; */
/* window.MotionPath = MotionPathPlugin; */

// Text Hover Effect
gsap.utils.toArray(".text-hover-effect").forEach(element => {
    document.fonts.ready.then(() => {
        let text = element.textContent;
        element.innerHTML = '<span class="actual -translate-y-[50%] absolute left-0 top-[50%] w-[100%]">' + text + '</span><span class="placeholder">' + text + '</span>';

        let placeholder = new SplitText(element.querySelector(".placeholder"), { type: "chars, words, lines", charsClass: 'split-chars', wordsClass: 'split-words', linesClass: 'split-lines' });
        let actual = new SplitText(element.querySelector(".actual"), { type: "chars, words, lines", charsClass: 'split-chars', wordsClass: 'split-words', linesClass: 'split-lines' });
        
        let textHoverTL = gsap.timeline({ paused: true });
        
        gsap.set(actual.chars, { x: '120%', opacity: 0, });

        textHoverTL.to(placeholder.chars, { duration: 0.25, x: '-120%', opacity: 0, stagger: 0.01 })
        .to(actual.chars, { delay: 0.025, duration: 0.25, x: '0%', opacity: 1, stagger: 0.01 }, 0);
        
        element.addEventListener("mouseenter", () => textHoverTL.play());
        element.addEventListener("mouseleave", () => textHoverTL.reverse());
    });
});

CustomEase.create("circleRoll", ".51,1.1,.24,1.15");

// Text Hover Effect with Specific Child
gsap.utils.toArray(".text-hover-effect-with-specific-child").forEach(element => {
    document.fonts.ready.then(() => {
        let text = element.querySelector(".text-hover-effect-child").textContent;
        element.querySelector(".text-hover-effect-child").innerHTML = '<span class="actual text-center -translate-x-[50%] -translate-y-[50%] absolute left-[50%] top-[50%] w-[100%]">' + text + '</span><span class="placeholder">' + text + '</span>';

        let placeholder = new SplitText(element.querySelector(".placeholder"), { type: "chars, words, lines", charsClass: 'split-chars', wordsClass: 'split-words', linesClass: 'split-lines' });
        let actual = new SplitText(element.querySelector(".actual"), { type: "chars, words, lines", charsClass: 'split-chars', wordsClass: 'split-words', linesClass: 'split-lines' });
        
        let textHoverTL = gsap.timeline({ paused: true });
        
        gsap.set(actual.chars, { x: '120%', opacity: 0, });

        textHoverTL.to(placeholder.chars, { duration: 0.25, x: '-120%', opacity: 0, stagger: 0.01 })
        .to(actual.chars, { delay: 0.025, duration: 0.25, x: '0%', opacity: 1, stagger: 0.01 }, 0);
        
        element.addEventListener("mouseenter", () => textHoverTL.play());
        element.addEventListener("mouseleave", () => textHoverTL.reverse());
    });
});

// Text Reveal Effect
gsap.utils.toArray(".text-reveal-effect").forEach(element => {
    document.fonts.ready.then(() => {
        let splitText = new SplitText(element, { type: "words, lines", charsClass: 'split-chars', wordsClass: 'split-words', linesClass: 'split-lines' });
        
        gsap.set(splitText.words, { opacity: 0.05, });
        gsap.to(splitText.words, { 
            scrollTrigger: {
                trigger: element,
                start: 'top 90%',
                end: 'bottom 90%',
            },
            delay: 0.5,
            duration: 1,
            opacity: 1,
            stagger: 0.1
        })
    });
});

// Popup Hover Effect
gsap.utils.toArray(".popup-hover-effect").forEach(element => {
    let circleEl = document.createElement('div');
    circleEl.classList.add('circle','absolute','left-0','top-0', 'scale-0','w-[40px]','h-[40px]','bg-primary','rounded-full', 'flex', 'items-center', 'justify-center');
    element.appendChild(circleEl);

    let iconEl = document.createElement('div');
    iconEl.classList.add('icon','icon-resize', 'text-[12px]','h-[12px]', 'leading-none', 'text-white');
    element.querySelector('.circle').appendChild(iconEl)

    let circle = element.querySelector(".circle");
    let icon = element.querySelector(".icon");

    element.addEventListener("mouseenter", () => { gsap.to(circle, { duration: .6, ease: 'expo.out', scale: 1.25 }) });
    element.addEventListener("mouseleave", () => { gsap.to(circle, { duration: .6, ease: 'expo.out', scale: 0 }) });
    element.addEventListener("mousemove", (e) => { 
        let xSet = gsap.quickTo(circle, "x", { delay: .05, duration: .45, ease: "back.out(7)"});
        let ySet = gsap.quickTo(circle, "y", { delay: .05, duration: .45, ease: "back.out(7)"});
        let rSet = gsap.quickSetter(icon, "css");

        let scale = element.getBoundingClientRect().width / element.offsetWidth;
        xSet( ( ( e.clientX - element.getBoundingClientRect().left ) / scale ) - circle.scrollWidth / 2);
        ySet( ( ( e.clientY - element.getBoundingClientRect().top ) / scale ) - circle.scrollHeight / 2);

        let rotate = ( ( e.clientX - element.getBoundingClientRect().left - (element.getBoundingClientRect().width / 2)) / scale ) * 90 / (element.getBoundingClientRect().width / 2);
        rSet({ rotate: rotate });
    });
});

// Sticky Object
gsap.utils.toArray(".dynamic-sticky").forEach(element => {
    let wrapper = element.closest('.sticky-wrapper');

    gsap.to(element, {
        scrollTrigger: {
            trigger: element,
            start: 'top top',
            end: 'top+=' + ( wrapper.scrollHeight - element.scrollHeight ) + ' top',
            pin: true,
            pinSpacing: false,
            onUpdate: (self) => {
                let direction = self.direction == 1 ? 'down' : 'up';
                let progress = self.progress.toFixed(2);
                let header = document.querySelector('.header-field');

                if(direction == 'down' && progress > 0) element.style.marginTop = '30px';
                if(direction == 'up' && progress > 0) element.style.marginTop = `${header.clientHeight + 30}px`;
                if(progress == 0 || progress == 1) element.style.marginTop = '0px';
            },
        }
    })
});

// Scroll Down
let scrollDown = document.querySelector('.scroll-down');
if(scrollDown) {
    let scrollDownTL = gsap.timeline({ repeat: -1 })
    scrollDownTL.to(scrollDown.querySelectorAll('svg path'), { duration: 0.5, scale: 2, stagger: 0.05 })
    scrollDownTL.to(scrollDown.querySelectorAll('svg path'), { duration: 0.5, scale: 1, stagger: -0.05 })
}

// Scroll Field
if(window.innerWidth > mobileLimit) {
    let scrollFields = document.querySelectorAll('.scroll-field');
    scrollFields.forEach(scrollField => {
        let sections = scrollField.querySelectorAll('.wrapper .section');
        sections.forEach((section, index) => {
            section.querySelector('.text-field').style.bottom = `${2 * index * 100}%`;
        });

        let pins = scrollField.querySelector('.pins');
        let pinLength = pins.querySelectorAll('.pin').length;

        let sectionTL = gsap.timeline({ 
            scrollTrigger: {
                trigger: scrollField,
                start: 'top top',
                end: `bottom+=${(100 * sections.length) - 100}% bottom`,
                scrub: 1.5,
                pin: true,
                snap: 1 / (sections.length - 1),
                onUpdate: (self) => {
                    let value = Math.ceil(self.progress * pinLength);
                    if(value == 0) value = 1;

                    pins.querySelectorAll('.pin').forEach(pin => pin.classList.remove('active'));
                    pins.querySelectorAll('.pin')[value - 1].classList.add('active');
                },
            },
        });

        sectionTL.to(scrollField.querySelectorAll('.wrapper .section .image-field'), { ease: 'none', duration: 1, y: `${-100 * (sections.length - 1)}%`, }, 'same');
        sectionTL.to(scrollField.querySelectorAll('.wrapper .section .text-field'), { ease: 'none', duration: 1, y: `${100 * (sections.length - 1)}%`, }, 'same');
    });
}

// Breadcrumb Scroll Effect
let breadcrumb = document.querySelector('.breadcrumb');
if(breadcrumb) {
    gsap.to(breadcrumb.querySelector('.background > *'), {
        scrollTrigger: {
            trigger: breadcrumb,
            start: 'top top',
            end: 'bottom top',
            scrub: 3,
        },
        scale: 1.25,
    });
}

// Career Arrows
let careerArrow = document.querySelectorAll('.career-prolog .career-steps .process-field .title svg path');
if(document.querySelector('.career-prolog .career-steps .process-field .title')) {
    let careerArrowTL = gsap.timeline({ repeat: -1 });
    careerArrowTL.set(careerArrow[3], { opacity: 0, })
    careerArrowTL.to(careerArrow[0], { translateX: '135%', opacity: 0, })
    careerArrowTL.to(careerArrow[1], { translateX: '135%', }, 'same')
    careerArrowTL.to(careerArrow[2], { translateX: '135%', }, 'same')
    careerArrowTL.to(careerArrow[3], { translateX: '135%', opacity: 1, }, 'same')
}

// Career Circle
let careerBox = document.querySelectorAll('.apply-options .box');
if(document.querySelector('.apply-options')) {
    careerBox.forEach(element => {
        let circleTL = gsap.timeline({ paused: true });
        circleTL.to(element.querySelector('svg'), { rotate: 720, duration: 3, });

        element.addEventListener('mouseenter', () => { circleTL.play(); });
        element.addEventListener('mouseleave', () => { circleTL.reverse(); });
    });
}

// Values Field
let valuesField = document.querySelector('.values-field');
if(valuesField) {
    gsap.to(valuesField.querySelectorAll('.lines .line'), {
        scrollTrigger: {
            trigger: valuesField,
            start: 'top center',
            end: 'bottom bottom',
            scrub: 1.5,
        },
        scaleY: 1,
        stagger: 0.01,
    });

    valuesField.querySelectorAll('.list .item').forEach(element => {
        gsap.to(element.querySelector('.number'), {
            scrollTrigger: {
                trigger: element,
                start: 'top center',
                end: 'bottom center',
                scrub: 1.5,
            },
            color: element.getAttribute('data-color'),
            '-webkit-text-stroke-color' : 'transparent',
        })
    });
}

// About Prolog
let prologFieldImages = document.querySelector('.prolog-field .image-list');
if(prologFieldImages && window.innerWidth > mobileLimit) {
    let prologTL = gsap.timeline({
        scrollTrigger: {
            trigger: prologFieldImages,
            start: 'top-=50% center',
            end: 'bottom center',
            scrub: 1.5,
        }
    })
    prologTL.to(prologFieldImages.querySelectorAll('.image')[0], { y: -30 }, 'same <=0.25')
    prologTL.to(prologFieldImages.querySelectorAll('.image')[0].querySelector('img'), { y: -30 }, 'same <=0.25')
    prologTL.to(prologFieldImages.querySelectorAll('.image')[1], { y: 30 }, 'same2 <=0.25')
    prologTL.to(prologFieldImages.querySelectorAll('.image')[1].querySelector('img'), { y: -30 }, 'same2 <=0.25')
    prologTL.to(prologFieldImages.querySelectorAll('.image')[2], { y: -30 }, 'same3 <=0.25')
    prologTL.to(prologFieldImages.querySelectorAll('.image')[2].querySelector('img'), { y: -30 }, 'same3 <=0.25')
}

// Image Reveal Effect
gsap.utils.toArray(".image-reveal-effect").forEach(element => {
    let image = element.querySelector('img');

    let shapeEL = document.createElement('div');
    shapeEL.classList.add('shape','absolute', 'left-0', 'top-0', 'w-full', 'h-0', 'bg-primary');
    element.appendChild(shapeEL);

    gsap.set(image, { opacity: 0 });

    let imageTL = gsap.timeline({
        scrollTrigger: {
            trigger: element,
            start: 'top center',
            end: 'bottom center',
        }
    });

    imageTL.to(element.querySelector('.shape'), { height: '100%', duration: 0.6, ease: 'expo.inOut' })
    imageTL.set(element.querySelector('img'), { opacity: 1, })
    imageTL.set(element.querySelector('.shape'), { top: 'unset', bottom: 0 })
    imageTL.to(element.querySelector('.shape'), { delay: 0.1, height: '0%', duration: 0.6, ease: 'expo.inOut' })
});

// Fleet Circle
let fleetBox = document.querySelectorAll('.fleet-list .fleet');
if(fleetBox.length) {
    fleetBox.forEach(element => {
        let circleTL = gsap.timeline({ paused: true });
        circleTL.to(element.querySelector('svg'), { rotate: 180, duration: 3, });

        element.addEventListener('mouseenter', () => { circleTL.play(); });
        element.addEventListener('mouseleave', () => { circleTL.restart(); circleTL.pause(); });
    });
}

// Logo Animation
let logo = document.querySelector('.logo-field .logo svg');
if(logo) {
    let logoTL = gsap.timeline();
    logoTL.from(logo.querySelectorAll('path.line'), { duration: 1, scaleX: 0, stagger: 0.1, ease: 'expo.inOut' })
    logoTL.from(logo.querySelectorAll('path.text'), { duration: 1, translateX: '25%', opacity: 0, stagger: -0.1, ease: 'expo.inOut' }, '<=0.25')
}

// Policies Circle
let policiesItem = document.querySelectorAll('.policies-list .policy');
if(policiesItem.length) {
    policiesItem.forEach(element => {
        let circleTL = gsap.timeline({ paused: true });
        circleTL.to(element.querySelector('svg'), { rotate: 180, duration: 3, });

        element.addEventListener('mouseenter', () => { circleTL.play(); });
        element.addEventListener('mouseleave', () => { circleTL.restart(); circleTL.pause(); });
    });
}