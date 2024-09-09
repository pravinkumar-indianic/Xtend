$(document).ready(function(){
    //$('.secondary.menu .item').tab();
    $("#sameaddress").prop('checked', false);
});

function initMap(mapid, edit) {

	var latlng = new google.maps.LatLng(-33.8826010361421, 151.20655059814453);	
	
	var myOptions = {
      zoom: 15,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false
    };

    var map = new google.maps.Map(document.getElementById('map' + mapid), myOptions);
    //document.getElementById('map' + mapid).gMap = map;

    var infowindow = new google.maps.InfoWindow;
    
    var marker = new google.maps.Marker({
      position: latlng,
      map: map
    }); 

    var address = document.getElementById('fulladdress-' + mapid);
    if (address.value != ""){
		var geocoder = new google.maps.Geocoder;

	    geocoder.geocode( { 'address': address.value}, function(results, status) {
	      	if (status == 'OK') {

	        	marker.setPosition(results[0].geometry.location);
    			map.setCenter(marker.getPosition());

    			map.setZoom(17);

		        infowindow.close();
		        infowindow.setContent(address.value);
		        infowindow.open(map, marker);

      		} 
	    });

	}

	if(edit == 1){

        var autocomplete = new google.maps.places.Autocomplete(document.getElementById('pac-input-' + mapid));

        // Set initial restrict to the greater list of countries.
        autocomplete.setComponentRestrictions(
            {'country': ['au', 'nz']});

	    google.maps.event.addListener(map, 'click', function(event) {
	      placeMarker(event.latLng, map, marker, infowindow, mapid);
	    });

        autocomplete.addListener('place_changed', function() {
          	fillAddress(map, marker, autocomplete, mapid);
        });

    }

    //google.maps.event.addDomListener(window, 'load', function(){
 //    google.maps.event.addListener(map, "idle", function(){
	//   google.maps.event.trigger(map, 'resize'); 
	//   map.setCenter(marker.location);
	// });

}


function fillAddress(map, marker, autocomplete, mapid){
	var place = autocomplete.getPlace();
      if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        $('#confirmaddress').addClass('disabled');
        $('#checkoutbutton').addClass('disabled');
        window.alert("No details available for : '" + place.name + "'");
        return;
      }

       $('#confirmaddress').removeClass('disabled');

      parseAddresstoForm(place.address_components, mapid);

      map.panTo(place.geometry.location);
      map.setZoom(15);

      marker.setPosition(place.geometry.location);
      //infowindow.open(map, marker);
}

function placeMarker(location, map, marker, infowindow, mapid) {

    marker.setPosition(location);
    map.setCenter(marker.getPosition());
    
    //convert lat/lang position to string address
    geocoder = new google.maps.Geocoder;
    

    var latlng = {lat: marker.getPosition().lat(), lng: marker.getPosition().lng()};
    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        if (results[0]) {
          map.setZoom(17);

          infowindow.close();
          infowindow.setContent(results[0].formatted_address);
          infowindow.open(map, marker);
          
          $("#pac-input-" + mapid).val(results[0].formatted_address);

          parseAddresstoForm(results[0]['address_components'], mapid);

        } else {
          window.alert('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });
  }

function parseAddresstoForm(component, mapid){
    $("#" + mapid + "address").val("");
    $("#" + mapid + "suburb").val("");
    $("#" + mapid + "city").val("");
    $("#" + mapid + "state").val("");
    $("#" + mapid + "postcode").val("");

    for (i = 0; i < component.length; ++i) {
        if (component[i]['types'][0] == 'country'){
          $("#" + mapid + "country_id option").each(function() {
            if($(this).text() == component[i]['long_name']) {
              $(this).attr('selected', 'selected');
            }
          });
        }else if(component[i]['types'][0] == 'subpremise'){
          $("#" + mapid + "address").val(component[i]['short_name'] + "/");
        }else if(component[i]['types'][0] == 'street_number'){
          $("#" + mapid + "address").val($("#" + mapid + "address").val() + component[i]['short_name'] + " ");
        }else if(component[i]['types'][0] == 'route'){
          $("#" + mapid + "address").val($("#" + mapid + "address").val() + component[i]['short_name']);
          if (component[i]['long_name'].indexOf("Road") >= 0){
            $("#" + mapid + "street_type").val('Road');
          }else if (component[i]['long_name'].indexOf("Avenue") >= 0){
            $("#" + mapid + "street_type").val('Avenue');
          }else if (component[i]['long_name'].indexOf("Lane") >= 0){
            $("#" + mapid + "street_type").val('Lane');
          }else{
            $("#" + mapid + "street_type").val('Street');
          }
          $("#" + mapid + "street_type").change();
        }else if(component[i]['types'][0] == 'locality'){
          $("#" + mapid + "suburb").val(component[i]['short_name']);
        }else if(component[i]['types'][0] == 'administrative_area_level_2'){
          $("#" + mapid + "city").val(component[i]['short_name']);
        }else if(component[i]['types'][0] == 'administrative_area_level_1'){
          $("#" + mapid + "state").val(component[i]['short_name']);
        }else if(component[i]['types'][0] == 'postal_code'){
          $("#" + mapid + "postcode").val(component[i]['short_name']);
        }
    }
}

function initEditMap(render){
  /** render = shipping || home || both **/

	callInitMap(1, render);				
}

function initViewMap(render){
	callInitMap(0, render);				
}

function callInitMap(edit, render){

  if(render == 'home' || render == 'both')
  {
  	//$("#pac-input-home").val("");
  	setTimeout(initMap('home', edit), 2000);
  }

  if(render == 'shipping' || render == 'both')
  {	
  	//$("#pac-input-shipping").val("");
  	setTimeout(initMap('shipping', edit), 2000);
	}
}