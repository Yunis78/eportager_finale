var map = L.map('myMap').setView([48.85, 2.34], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

//marker type goutte d'eau 
var marker = L.marker([48.85, 2.34]).addTo(map);
// nommination du marker
marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
