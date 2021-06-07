const { map } = require("jquery");


var positions = $('#map').data('positions');

for (let i = 0; i < positions.length; i++) {

    const element = positions[i];
    var nom = element['name'];

}

var carte = L.map('map').setView([46.00, 2.00], 6);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18,
    tileSize: 512,
    zoomOffset: -1,
    doubleClickZoom: true,
    dragging: true,
}).addTo(carte);

	

var path = $('#map').data('path');


var epotagerIcon = L.icon({
    iconUrl: path,
    iconSize:     [25, 41], // size of the icon
    iconAnchor:   [12, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [0, -38] // point from which the popup should open relative to the iconAnchor
});



var markersLayer = new L.LayerGroup();	//layer contain searched elements
	carte.addLayer(markersLayer);


for (let i = 0; i <Object.keys(positions).length; i++) {

    const element = positions[i];

    L.marker([element['latitude'], element['longitude']], {icon: epotagerIcon ,title: positions}).addTo(markersLayer).bindPopup(element['name']);

}

var list = new L.Control.ListMarkers({layer: markersLayer, itemIcon: null});

carte.addControl( list );

var cardproducer = document.getElementById('cardproducer');
cardproducer.classList.remove('leaflet-control');
var mapping = document.getElementById('mapping');
mapping.appendChild(cardproducer);

