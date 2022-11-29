// document.addEventListener('DOMContentLoaded', () => {
//     'use strict';
//
//     // Variables
//     let $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
//
//
//     // Methods
//     let handleNavbarBurgers = function (el) {
//         const target = el.dataset.target;
//         const $target = document.getElementById(target);
//         el.classList.toggle('is-active');
//         $target.classList.toggle('is-active');
//     }
//
//     // Inits & Event Listeners
//     //handle navbar burgers
//     $navbarBurgers.forEach(el => {
//         el.addEventListener('click', () => {
//             handleNavbarBurgers(el);
//         });
//     });
//
//     const categories = getAll('.menu_category');
//     if (categories.length > 0) {
//         categories.forEach(function (el) {
//             const toggle_el = el.querySelector('.menu_label__toggle');
//             toggle_el.addEventListener('click', function () {
//                 // closeCategories(el);
//                 el.classList.toggle('is-active');
//             });
//         });
//     }
//
// });
