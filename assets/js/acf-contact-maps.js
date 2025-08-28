(function(){
  function initMap(container){
    var lat = parseFloat(container.dataset.lat);
    var lng = parseFloat(container.dataset.lng);
    if (isNaN(lat) || isNaN(lng)) return;

    var map = new google.maps.Map(container, {
      center: {lat: lat, lng: lng},
      zoom: 15,
      mapTypeControl: false,
      streetViewControl: false
    });
    new google.maps.Marker({ position: {lat: lat, lng: lng}, map: map });
  }

  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.acf-location-map').forEach(initMap);
  });
})();
