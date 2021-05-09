/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'remixicon/fonts/remixicon.css';
import './styles/custom_product.css';
import './styles/custom_producteur.css';

// start the Stimulus application
// import './bootstrap';
const $ = require('jquery');
global.jQuery = $;
global.$ = $;

import 'bootstrap';
import 'popper.js';

require('./js/input.js');
require('./js/collection.js');

$("#product-medias-collection").collection();
window.component_input();
