/*
Author       : Dreams Technologies
Template Name: Doccure - Bootstrap Template
Version      : 1.3
*/

google.maps.visualRefresh = true;
var slider, infowindow = null;
var bounds = new google.maps.LatLngBounds();
var map, current = 0;

			
			
var locations =[{
	"id":01,
	"doc_name":"Dr. Doctor Demo",
	"speciality":"Urology",
	"address":"England",
	"next_available":" Not Available",
	"amount":"$100",
	"lat":53.470692,
	"lng":-2.220328,
	"icons":"default",
	"profile_link":"https://doccure-wp.dreamstechnologies.com/elementor/doctors/doctor-demo/",
	"image":'https://doccure-wp.dreamstechnologies.com/wp-content/uploads/2022/05/book-doctor-09-255x250.jpg'
	}, {
		
	"id":02,
	"doc_name":"Dr. Ruby Perrin",
	"speciality":"Neurology",
	"address":"England",
	"next_available":"Not Available",
	"amount":"$200",
	"lat":53.469189,
	"lng":-2.199262,
	"icons":"default",
	"profile_link":"https://doccure-wp.dreamstechnologies.com/elementor/doctors/ruby-perrin/",
	"image":'https://doccure-wp.dreamstechnologies.com/wp-content/uploads/2022/05/book-doctor-12-255x250.jpg'
	}, {
	"id":03,
	"doc_name":"Dr. Olga Barlow",
	"speciality":"Urology",
	"address":"India",
	"next_available":"Not Available",
	"amount":"$200",
	"lat":53.468665,
	"lng":-2.189269,
	"icons":"default",
	"profile_link":"hhttps://doccure-wp.dreamstechnologies.com/elementor/doctors/olga-barlow/",
	"image":'https://doccure-wp.dreamstechnologies.com/wp-content/uploads/2022/05/book-doctor-10-255x250.jpg'
	}, {
	"id":04,
	"doc_name":"Dr. Paul Richard",
	"speciality":"MRI Scans",
	"address":"Australia",
	"next_available":"Not Available",
	"amount":"$200",
	"lat":53.463894,
	"lng":-2.177880,
	"icons":"default",
	"profile_link":"https://doccure-wp.dreamstechnologies.com/elementor/doctors/paul-richard/",
	"image":'https://doccure-wp.dreamstechnologies.com/wp-content/uploads/2022/05/doctor-thumb-08-255x250.jpg'
	}, {
	"id":05,
	"doc_name":"Dr. Edward Willey",
	"speciality":"Cardiologist",
	"address":"England",
	"next_available":" Not Available",
	"amount":"$200",
	"lat":53.466359,
	"lng":-2.213314,
	"icons":"default",
	"profile_link":"https://doccure-wp.dreamstechnologies.com/elementor/doctors/edward-willey/",
	"image":'https://doccure-wp.dreamstechnologies.com/wp-content/uploads/2022/03/book-doctor-14-255x250.jpg'
	}
	
	];

var icons = {
  'default':'https://doccure-wp.dreamstechnologies.com/elementor/wp-content/themes/doccure/assets/images/marker.png'
};

function show() {
    infowindow.close();
  if (!map.slide) {
    return;
  }
    var next, marker;
    if (locations.length == 0 ) {
       return
     } else if (locations.length == 1 ) {
       next = 0;
     }
    if (locations.length >1) {
      do {
        next = Math.floor (Math.random()*locations.length);
      } while (next == current)
    }
    current = next;
    marker = locations[next];
    setInfo(marker);
    infowindow.open(map, marker);
}

function initialize() {
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        zoom: 14,
		center: new google.maps.LatLng(53.470692, -2.220328),
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		
    };
  
     map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.slide = true;

    setMarkers(map, locations);
    infowindow = new google.maps.InfoWindow({
        content: "loading..."
    });
    google.maps.event.addListener(infowindow, 'closeclick',function(){
       infowindow.close();
    });
    slider = window.setTimeout(show, 3000);
}

function setInfo(marker) {
  var content = 
'<div class="profile-widget" style="width: 100%; display: inline-block;">'+
	'<div class="doc-img">'+
		'<a href="' + marker.profile_link + '" tabindex="0" target="_blank">'+
			'<img class="img-fluid" alt="' + marker.doc_name + '" src="' + marker.image + '">'+
		'</a>'+
	'</div>'+
	'<div class="pro-content">'+
		'<h3 class="title">'+
			'<a href="' + marker.profile_link + '" tabindex="0">' + marker.doc_name + '</a>'+
			'<i class="fas fa-check-circle verified"></i>'+
		'</h3>'+
		'<p class="speciality">' + marker.speciality + '</p>'+
		'<div class="rating">'+
			'<i class="fas fa-star filled"></i>'+
			'<i class="fas fa-star filled"></i>'+
			'<i class="fas fa-star filled"></i>'+
			'<i class="fas fa-star filled"></i>'+
			'<i class="fas fa-star filled"></i>'+
		'</div>'+
		'<ul class="available-info">'+
			'<li><i class="fas fa-map-marker-alt"></i> ' + marker.address + ' </li>'+
			'<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>'+
			'<li><i class="far fa-money-bill-alt"></i> ' + marker.amount + '</li>'+
		'</ul>'+
	'</div>'+
'</div>';
  infowindow.setContent(content);
}

function setMarkers(map, markers) {
  for (var i = 0; i < markers.length; i++) {
    var item = markers[i];
    var latlng = new google.maps.LatLng(item.lat, item.lng);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        doc_name: item.doc_name,
        address: item.address,
        speciality: item.speciality,
        next_available: item.next_available,
        amount: item.amount,
        profile_link: item.profile_link,
        total_review: item.total_review,
        animation: google.maps.Animation.DROP,
        icon: icons[item.icons],
        image: item.image
        });
        bounds.extend(marker.position);
        markers[i] = marker;
        google.maps.event.addListener(marker, "click", function () {
            setInfo(this);
            infowindow.open(map, this);
            window.clearTimeout(slider);
        });
    }
    map.fitBounds(bounds);
  google.maps.event.addListener(map, 'zoom_changed', function() {
    if (map.zoom > 16) map.slide = false;
  });
}

google.maps.event.addDomListener(window, 'load', initialize);