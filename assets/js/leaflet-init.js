document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.leaflet-map').forEach(function(el){
    var lat = parseFloat(el.dataset.lat);
    var lng = parseFloat(el.dataset.lng);
    if (isNaN(lat) || isNaN(lng)) return;

    // Maak de kaart
    var map = L.map(el).setView([lat, lng], 15);

    // OSM tiles (gratis)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/">OSM</a>'
    }).addTo(map);

    // Marker
    L.marker([lat, lng]).addTo(map);
  });
});
