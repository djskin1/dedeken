document.addEventListener('DOMContentLoaded', function(){
  var el = document.getElementById('leaflet-map');
  if(!el) return;

  var markers = [];
  try {
    markers = JSON.parse(el.dataset.markers || '[]');
  } catch(e) {
    console.error('Markers JSON parse error', e, el.dataset.markers);
    return;
  }
  if (!markers.length) {
    console.warn('Geen markers gevonden');
    return;
  }

  // Startpositie (eerste marker)
  var map = L.map(el).setView([markers[0].lat, markers[0].lng], 13);

  // OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OSM</a>'
  }).addTo(map);

  var bounds = [];
  markers.forEach(function(m){
    if (typeof m.lat !== 'number' || typeof m.lng !== 'number') return;
    var mk = L.marker([m.lat, m.lng]).addTo(map);
    if (m.title || m.address) {
      mk.bindPopup(
        (m.title ? '<b>'+m.title+'</b><br>' : '') + (m.address || '')
      );
    }
    bounds.push([m.lat, m.lng]);
  });

  if (bounds.length > 1) map.fitBounds(bounds, {padding:[30,30]});
});
