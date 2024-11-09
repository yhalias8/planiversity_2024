<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'trip/how-are-you-traveling';
  header("Location:" . SITE . "login");
}

include('include_doctype.php');
?>
<html>

<head>
  <meta charset="utf-8">
  <title>PLANIVERSITY - HOW ARE YOU TRAVELING?</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
  <meta name="keywords" content="Consolidated Travel Information Management">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

  <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
  <?php include('new_head_files.php'); ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-146873572-1');
  </script>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-PBF3Z2D');
  </script>
  <!-- End Google Tag Manager -->

  <style type="text/css">
    .footer {
        position: fixed !important;
    }
</style>
</head>

<body class="custom_howtravelling">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?php include('new_backend_header.php'); ?>

  <?php include_once('includes/top_bar.php'); ?>
  </header>

  <?php //include('include_filter.php') 
  ?>

  <div class="wrapper">                       
    <section class="map_with_content_Sec">
      <div id="map"></div> 
               
      <div class="how-travel-wrapper how_travel_mobile_bg">
        <div class="row">
          <div class="col-mdgoogleapis-12 col-sm-12 text-center pt-5">
            <!--<img src = "<?php echo SITE; ?>assets/images/logo2x.png" alt = "logo icon">-->
            <h4 class="Planiversity_head">Planiversity</h4> 
            <h3 class="where-to-hd">How Are You Traveling?</h3>
            <span class="where-select-method">Select your method of travelling</span>
          </div>
        </div>
        <div class="row justify-content-md-center mx-5" style="padding-left: 5rem; padding-right: 5rem">
          <div class="col-md-6">
            <!-- <div style="border: 1px dashed #2477B8;position: relative; top: 80px;"></div> -->
          </div>
        </div>
        <div class="row justify-content-md-center mx-5 pb-5 map_vehicle_plane_icon">
          <div class="col-md-2 col-sm-12 col-4 border_line_right1">
            <a href = "<?php echo SITE; ?>trip/origin-destination/vehicle"> 
              <div class = "how-travel-ico-wrapper">
                <img src = "<?php echo SITE; ?>assets/images/car.png" class="default_icon_des" width="30px" height="30px" alt = "logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src = "<?php echo SITE; ?>assets/images/by_vical_icon.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">By Vehicle</p>
            </a>
          </div>
          <div class="col-md-2 col-sm-12 col-4 border_line_right1"> 
            <a href = "<?php echo SITE; ?>trip/origin-destination/plane">
              <div class = "how-travel-ico-wrapper">
                <img src = "<?php echo SITE; ?>assets/images/flight.png" class="default_icon_des" width="30px" height="30px" alt = "logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src = "<?php echo SITE; ?>assets/images/by_plan_icon.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">By Plane</p>
            </a>
          </div>
          <div class="col-md-2 col-sm-12 col-4"> 
            <a href = "<?php echo SITE; ?>trip/origin-destination/train">
              <div class = "how-travel-ico-wrapper">
                <img src = "<?php echo SITE; ?>assets/images/train.png" class="default_icon_des" width="30px" height="30px" alt = "logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src = "<?php echo SITE; ?>assets/images/by_train_icon.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">By Train</p>
            </a>
          </div>
        </div>
      </div>
    </section> 
  </div>

  <script>
    var marker_origin = null;
    var marker_destination = null;
    var flightPath = null;

    var map = null;
    var bounds = null;

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        center: {
          lat: 40.730610,
          lng: -73.968285
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 7
      });

      <?php if ($transport == 'plane') { ?>
        // drawing line 
        new DrawPlaneDirectionsHandler(map);
      <?php } else { ?>
        // tracing the route
        new AutocompleteDirectionsHandler(map);
      <?php } ?>
    }

    /************ filters ******************/

    <?php /*if ($trip->trip_option_hotels) { ?>NearbyPlacesHandler('lodging',1)<?php } ?>
<?php if ($trip->trip_option_police) { ?>NearbyPlacesHandler('police',1)<?php } ?>
<?php if ($trip->trip_option_hospitals) { ?>NearbyPlacesHandler('hospital',1)<?php } ?>
<?php if ($trip->trip_option_gas) { ?>NearbyPlacesHandler('gas_station',1)<?php }*/ ?>

    /*var marker_hotels = [];
    var marker_police = [];
    var marker_hospital = [];
    var marker_gasstation = [];
    var marker_embassy = [];
    function NearbyPlacesHandler(filter,status) {
       document.getElementById('filter_click').value = filter;
       if (!status)
          { // erase all markers
            if (filter=='lodging'){ for (i = 0; i < marker_hotels.length; i++) { marker_hotels[i].setMap(null); }}
            if (filter=='police'){ for (i = 0; i < marker_police.length; i++) { marker_police[i].setMap(null); }}
            if (filter=='hospital'){ for (i = 0; i < marker_hospital.length; i++) { marker_hospital[i].setMap(null); }}
            if (filter=='gas_station'){ for (i = 0; i < marker_gasstation.length; i++) { marker_gasstation[i].setMap(null); }}
            if (filter=='embassy'){ for (i = 0; i < marker_embassy.length; i++) { marker_embassy[i].setMap(null); } $('#embassy_result').slideUp('slow'); document.getElementById("embassy_result").innerHTML=''; }
          }
       else
          { var tmp = document.getElementById('location_to_latlng').value;
            if (tmp=='') 
               { if (filter=='lodging'){ document.getElementById('filter_hotels').checked = false; }
                 if (filter=='police'){ document.getElementById('filter_police').checked = false; }
                 if (filter=='hospital'){ document.getElementById('filter_hospitals').checked = false; }
                 if (filter=='gas_station'){ document.getElementById('filter_gas').checked = false; }
                 if (filter=='embassy'){ document.getElementById('filter_embassis').checked = false; }
                 return;  
               }
            var aux = tmp.split(',');
            var des_lat = aux[0]; des_lat = des_lat.replace('(','');
            var des_lng = aux[1]; des_lng = des_lng.replace(')','');        
            bounds = new google.maps.LatLngBounds();
            var myLocation = { lat: Number(des_lat),
                               lng: Number(des_lng)
                             };
            var request = {
             location: myLocation,
             radius: 16093.4, // 10 miles in meters
             types: [filter],
            };        
            infowindow = new google.maps.InfoWindow();        
            var service = new google.maps.places.PlacesService(map);        
            service.nearbySearch(request, callback);  
          }   
    }
    function callback(results, status) {
      "use strict";   
      if (status == google.maps.places.PlacesServiceStatus.OK) {
       for (var i = 0; i < results.length; i++) {
        createMarker(results[i]);
       }
       if (document.getElementById('filter_click').value=='embassy') $('#embassy_result').slideDown('slow'); 
      } 
    }
    //var tmpi = 0;
    function createMarker(place) {
      "use strict";
      var place_icon;
      place_icon =  "<?php echo SITE; ?>images/map-icons/" + place.types['0'] + ".png";
      
      var placeLoc = place.geometry.location;
      var marker = new google.maps.Marker({
       map: map,
       position: place.geometry.location,
       icon: { url: place_icon },
       animation: google.maps.Animation.DROP
      });
      
      var datainfo;
      if (document.getElementById('filter_click').value=='lodging') marker_hotels.push(marker);
      if (document.getElementById('filter_click').value=='police') marker_police.push(marker);
      if (document.getElementById('filter_click').value=='hospital') marker_hospital.push(marker);
      if (document.getElementById('filter_click').value=='gas_station') marker_gasstation.push(marker);
      if (document.getElementById('filter_click').value=='embassy') marker_embassy.push(marker);
      if (place.types[0]=='embassy')   
         { //datainfo = '<b><a onclick="google.maps.event.trigger(marker_embassy['+tmpi+'], \'click\')">'+place.name+'</a></b>'+'<br />'+place.vicinity+'<br /> Phone: '+place.formatted_phone_number+'<br />';
           //datainfo = '<a target="_blank" href="<?php echo SITE; ?>trip/embassy/'+place.place_id+'" >'+place.name+'</a>'+'<br />';
           //datainfo = '<a>'+place.name+'</a>'+'<br />';
           var listembassis = String('<?php echo $trip->trip_list_embassis; ?>');
           var check = '';
           if (listembassis.includes(place.place_id)) check = 'checked="checked"';
           datainfo = '<a><input type="checkbox" name="embassy_list[]" value="'+place.place_id+'" '+check+'/>'+place.name+'</a>'+'<br />';
           //tmpi = tmpi + 1;
           document.getElementById("embassy_result").innerHTML = document.getElementById("embassy_result").innerHTML + datainfo;
         }     
      
      google.maps.event.addListener(marker, 'click', function() {
       infowindow.setContent(place.name+ '<br>' +place.vicinity);
       infowindow.open(map, this);
      });

      bounds.extend(marker.position);

      //now fit the map to the newly inclusive bounds
      map.fitBounds(bounds);
     }*/
    /******************************/

    function DrawPlaneDirectionsHandler(map) {
      this.map = map;
      this.originPlaceId = null;
      this.destinationPlaceId = null;
      this.originPlaceLocation = null;
      this.destinationPlaceLocation = null;
      var originInput = document.getElementById('location_from');
      var destinationInput = document.getElementById('location_to');

      var originAutocomplete = new google.maps.places.Autocomplete(originInput, {
        placeIdOnly: false
      });
      var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
        placeIdOnly: false
      });

      this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
      this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
    }
    DrawPlaneDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
      var me = this;
      autocomplete.bindTo('bounds', this.map);
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.place_id) {
          window.alert("Please select an option from the dropdown list.");
          return;
        }
        if (mode === 'ORIG') {
          me.originPlaceId = place.place_id;
          me.originPlaceLocation = place.geometry.location;
        } else {
          me.destinationPlaceId = place.place_id;
          me.destinationPlaceLocation = place.geometry.location;
        }
        me.route();
      });
    };
    DrawPlaneDirectionsHandler.prototype.route = function() {
      if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
      }
      var me = this;

      // clean map before start
      if (marker_origin != null) {
        marker_origin.setMap(null);
        marker_origin = null;
      };
      if (marker_destination != null) {
        marker_destination.setMap(null);
        marker_destination = null;
      };
      if (flightPath != null) {
        flightPath.setMap(null);
        flightPath = null;
      };

      var bounds = new google.maps.LatLngBounds();
      marker_origin = new google.maps.Marker({
        position: this.originPlaceLocation,
        label: {
          text: "A",
          color: "#000000",
        },
      });
      marker_destination = new google.maps.Marker({
        position: this.destinationPlaceLocation,
        label: {
          text: "B",
          color: "#000000",
        },
      });
      marker_origin.setMap(this.map);
      marker_destination.setMap(this.map);
      bounds.extend(marker_origin.position);
      bounds.extend(marker_destination.position);

      document.getElementById('location_from_latlng').value = this.originPlaceLocation;
      document.getElementById('location_to_latlng').value = this.destinationPlaceLocation;

      var flightPlanCoordinates = [{
          lat: this.originPlaceLocation.lat(),
          lng: this.originPlaceLocation.lng()
        },
        {
          lat: this.destinationPlaceLocation.lat(),
          lng: this.destinationPlaceLocation.lng()
        }
      ];

      flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        strokeColor: '#F08A0D',
        strokeOpacity: 1.0,
        strokeWeight: 3
      });

      flightPath.setMap(this.map);
      this.map.fitBounds(bounds);

      // hide to and from content    
      //show_win(2);  
    };

    /**
     * @constructor
     */
    function AutocompleteDirectionsHandler(map) {
      this.map = map;
      this.originPlaceId = null;
      this.destinationPlaceId = null;
      this.originPlaceLocation = null;
      this.destinationPlaceLocation = null;
      this.travelMode = '<?php echo $travelmode; ?>';
      var originInput = document.getElementById('location_from');
      var destinationInput = document.getElementById('location_to');
      this.directionsService = new google.maps.DirectionsService;
      this.directionsDisplay = new google.maps.DirectionsRenderer({
        polylineOptions: {
          strokeColor: "#F08A0D"
        }
      });
      this.directionsDisplay.setMap(map);

      var originAutocomplete = new google.maps.places.Autocomplete(originInput, {
        placeIdOnly: false
      });
      var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
        placeIdOnly: false
      });

      this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
      this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');
    }
    AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
      var me = this;
      autocomplete.bindTo('bounds', this.map);
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.place_id) {
          window.alert("Please select an option from the dropdown list.");
          return;
        }
        if (mode === 'ORIG') {
          me.originPlaceId = place.place_id;
          me.originPlaceLocation = place.geometry.location;
        } else {
          me.destinationPlaceId = place.place_id;
          me.destinationPlaceLocation = place.geometry.location;
        }
        me.route();
      });
    };
    AutocompleteDirectionsHandler.prototype.route = function() {
      if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
      }
      var me = this;

      document.getElementById('location_from_latlng').value = me.originPlaceLocation;
      document.getElementById('location_to_latlng').value = me.destinationPlaceLocation;

      this.directionsService.route({
        origin: {
          'placeId': this.originPlaceId
        },
        destination: {
          'placeId': this.destinationPlaceId
        },
        travelMode: this.travelMode,
        <?php if ($transport == 'train') { ?>
          transitOptions: {
            modes: [google.maps.TransitMode.TRAIN]
          },
        <?php } ?>
      }, function(response, status) {
        if (status === 'OK') {
          me.directionsDisplay.setDirections(response);
        } else {
          window.alert('Directions request failed due to ' + status);
        }
      });
    };
    SITE = 'www.planiversity.com/';
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

  <?php include('new_backend_footer.php'); ?>

</body>

</html>