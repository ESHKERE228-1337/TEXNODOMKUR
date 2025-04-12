ymaps.ready(init);

function init() {
  // Главный магазин
  var map1 = new ymaps.Map("map1", {
    center: [55.7558, 37.6176], // Coordinates for Москва
    zoom: 10
  });

  var placemark1 = new ymaps.Placemark([55.7558, 37.6176], {
    balloonContent: 'Технодом - Главный магазин'
  });
  map1.geoObjects.add(placemark1);

  // Филиал Северный
  var map2 = new ymaps.Map("map2", {
    center: [55.7698, 37.6209], // Coordinates for Северный филиал
    zoom: 10
  });

  var placemark2 = new ymaps.Placemark([55.7698, 37.6209], {
    balloonContent: 'Технодом - Филиал Северный'
  });
  map2.geoObjects.add(placemark2);
}
