// Global Variables -------------------------------------------------
window.body = document.querySelector('body');
window.header = document.querySelector('.header-field');
window.footer = document.querySelector('.footer-field');
window.main = document.querySelector('.main-field');
window.windowWidth = window.innerWidth;
window.windowHeight = window.innerHeight;
window.mobileLimit = 1280;

// jQuery ----------------------------------------------------------- 
import jQuery from 'jquery'
window.jQuery = jQuery
window.$ = jQuery

// FancyBox ---------------------------------------------------------
import { Fancybox } from '@fancyapps/ui'
window.Fancybox = Fancybox

// FlatPickr --------------------------------------------------------
import flatpickr from "flatpickr";
window.flatpickr = flatpickr