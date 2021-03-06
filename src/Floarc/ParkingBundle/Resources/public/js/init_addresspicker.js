  $(function() {

    var addresspickerMap = $( "#address" ).addresspicker({
      componentsFilter: 'country:FR',
      regionBias: "fr",
      reverseGeocode: true,
      //updateCallback: showCallback,
      updateCallback: majResult,
      mapOptions: {
        zoom: 4,
        center: new google.maps.LatLng(46, 2),
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      },
      elements: {
        map:      "#map",
        lat:      "#lat",
        lng:      "#lng",
        street_number: '#street_number',
        route: '#route',
        locality: '#locality',
        administrative_area_level_2: '#administrative_area_level_2',
        administrative_area_level_1: '#administrative_area_level_1',
        country:  '#country',
        postal_code: '#postal_code',
        type:    '#type'
      }
    });

    
    var gmarker = addresspickerMap.addresspicker( "marker");
    gmarker.setVisible(true);
    addresspickerMap.addresspicker( "updatePosition");

    /*
    $('#reverseGeocode').change(function(){
      $("#addresspicker_map").addresspicker("option", "reverseGeocode", ($(this).val() === 'true'));
    });

    function showCallback(geocodeResult, parsedGeocodeResult){
      $('#callback_result').text(JSON.stringify(parsedGeocodeResult, null, 4));
    }
    */
    // Update zoom field
    var map = $("#addresspicker_map").addresspicker("map");
    google.maps.event.addListener(map, 'idle', function(){
      $('#zoom').val(map.getZoom());
    });
    
    
	 function majResult(){
		 console.clear();
		 console.log("majResult");
		 console.log(jQuery("form.search-form").serialize());
	 }    
    
    


  });