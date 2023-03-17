/* const log = console.log;
console.log = () => {};
const warn = console.warn;
console.warn = () => {};
const error = console.error;
console.error = () => {}; */
/* const warn = console.warn;
console.warn = () => {}; */

// Side
let stretchToViewport = document.querySelectorAll('.stretch-to-viewport')
if(stretchToViewport.length) {
    stretchToViewport.forEach(element => {
        let container = element.closest('.wrapper');
        let stretchWrapper = element.closest('.stretch-wrapper');
        let size = ((window.innerWidth - container.offsetWidth)/2) + stretchWrapper.offsetWidth;
        element.style.width = `${size+30}px`
    });
}

// Date Picker
let dateElements = document.querySelectorAll('input[type="date"]');
dateElements.forEach(element => {
    flatpickr(element, {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
});

// Scroll Down
let scrollDown = document.querySelector(".scroll-down");
if(scrollDown)
scrollDown.addEventListener("click", () => {
    window.scrollTo({
        top: document.querySelector('.breadcrumb').nextElementSibling.offsetTop,
        behavior: 'smooth'
    });
}, false);

// Scroll Bar Down
let scrollDownBar = document.querySelector('.scroll-bar-field');
if(scrollDownBar)
scrollDownBar.addEventListener("click", () => {
    window.scrollTo({
        top: document.querySelector('.main-field > *').nextElementSibling.offsetTop,
        behavior: 'smooth'
    });
}, false);

// File Input Change
if(document.querySelector(".file-input"))
document.querySelector(".file-input").addEventListener('change', (event) => {
    let filename = event.target.files[0].name
    document.querySelector('.file-name').innerHTML = filename
});

// Menu Trigger
let menuTrigger = document.querySelector(".menu-trigger");
let menuField = document.querySelector(".menu-field");
let body = document.querySelector("body");
menuTrigger.addEventListener("click", () => { 
    menuTrigger.classList.toggle("active");
    menuField.classList.toggle("active");
    body.classList.toggle("overflow-hidden");
}, false);