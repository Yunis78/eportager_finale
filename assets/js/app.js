/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

require('@popperjs/core');
require('bootstrap');

const $ = require('jquery');
global.jQuery = $;
global.$ = $;

require('leaflet');
require('../../node_modules/slick-carousel/slick/slick');
require('./leafletListMaker');

require('./input.js');
require('./collection.js');
require('./map.js');

$("#product-medias-collection").collection();
window.component_input();
