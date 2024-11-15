var markerAlpaArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB'];
var mark_number = 0;
// "40.6021563,-75.47121229999999" to location obj
function convertLatLngStrToObj(str) {
    var arr = str.split(",");
    var pt = new google.maps.LatLng(arr[0], arr[1]);
    // new google.maps.Marker({
    //     position: new google.maps.LatLng(arr[0],arr[1]),
    //     icon:'https://planiversity.com/assets/images/icon.png'
    // });
    return pt;
}
// "(40.6021563,-75.47121229999999)" to location array
function convertLatLngStrToArray(str) {
    str = str.replace('(', '');
    str = str.replace(')', '');
    res = str.split(',');
    return res;
}

function convertJsonToWayPoints(arr) {
    var result = [];
    for (var i = 0; i < arr.length; i++) {
        var obj = new google.maps.LatLng(arr[i].lat, arr[i].lng);
        var dt = { location: obj, stopover: false };
        result.push(dt);
    }
    return result;
}
function calcTotalDistance(result) {
    var totalDistance = 0;
    for (var j = 0; j < result.routes[0].legs.length; j++) {
        totalDistance += result.routes[0].legs[j].distance.value;
    }
    return totalDistance;
}
function calcTotalDistanceText(result) {
  var totalDistance = 0;
  for (var j = 0; j < result.routes[0].legs.length; j++) {
    totalDistance += result.routes[0].legs[j].distance.value;
  }
  var distanceInMiles = totalDistance * 0.000621371; // Convert distance to miles
  var distanceText =
    distanceInMiles.toLocaleString(undefined, { maximumFractionDigits: 0 }) +
    " mi"; // Add commas and concatenate "mi"
  return distanceText;
}
function calcTotalDuration(result) {
    var totalDuration = 0;
    for (var j = 0; j < result.routes[0].legs.length; j++) {
        totalDuration += result.routes[0].legs[j].duration.value;
    }
    return totalDuration;
}
function calcTotalDurationText(result) {
  var totalDuration = calcTotalDurationSec(result);
  var totalSeconds = totalDuration % 60;
  var totalMinutes = Math.floor((totalDuration / 60) % 60);
  var totalHours = Math.floor((totalDuration / (60 * 60)) % 24);
  var totalDays = Math.floor(totalDuration / (60 * 60 * 24));

  if (totalSeconds > 30) {
    totalMinutes += 1;
  }

  var durationText = "";
  if (totalDays > 0) {
    durationText += totalDays + (totalDays === 1 ? " day " : " days ");
  }
  if (totalHours > 0) {
    durationText += totalHours + (totalHours === 1 ? " hour " : " hours ");
  }
  if (totalMinutes > 0) {
    durationText += totalMinutes + (totalMinutes === 1 ? " min " : " mins ");
  }

  return durationText.trim();
}

function calcTotalDurationSec(result) {
    var totalDuration = 0;
    for (var j = 0; j < result.routes[0].legs.length; j++) {
        totalDuration += result.routes[0].legs[j].duration.value;
    }

    return totalDuration;
}
// markup a point
function markupPoint(bounds, lat, lng, index, map) {
    var marker_imageA = new google.maps.MarkerImage(
        'https://planiversity.com/assets/images/Selected_A.png',
        null,
        // The origin for my image is 0,0.
        new google.maps.Point(0, 0),
        // The center of the image is 50,50 (my image is a circle with 100,100)
        new google.maps.Point(50, 50)
    );
    var mk = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        label: {
            text: index == false ? " " : markerAlpaArr[index],
            color: "#ffffff",
        },
        icon: marker_imageA
    });
    mk.setMap(map);
    bounds.extend(mk.position);
}
// draw flight with multi-stops
function DrawPlaneRoutes(map, lat_from_plane, lng_from_plane, lat_to_plane, lng_to_plane, location_multi_waypoint_latlng, type = 'flight') {
    //////////////////////////////////////
    const lineSymbol = {
        path: "M 0,0 0,1",
        strokeOpacity: 1,
        scale: 4,
    };
    const lineSymbol1 = {
        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
        strokeOpacity: 1,
    };
    //////////////////////////////////////////////////
    var bounds = new google.maps.LatLngBounds();
    var location_from_index = type === 'flight' ? 0 : (location_multi_waypoint_latlng.length + 1);
    // location_multi_waypoint_latlng = JSON.parse(location_multi_waypoint_latlng);
    var flightPlanCoordinates = [
        { lat: lat_from_plane, lng: lng_from_plane },
    ];
    if (type === 'flight') {
        new markupPoint(bounds, lat_from_plane, lng_from_plane, location_from_index, map); // location from
        for (var i = 0; i < location_multi_waypoint_latlng.length; i++) {
            var coord = location_multi_waypoint_latlng[i].location_multi_waypoint_latlng;
            coord = coord.split(',');
            flightPlanCoordinates.push({ lat: coord[0] * 1, lng: coord[1] * 1 });
            new markupPoint(bounds, coord[0] * 1, coord[1] * 1, i + 1, map); // location from
        }
        new markupPoint(bounds, lat_to_plane, lng_to_plane, location_multi_waypoint_latlng.length + 1, map); // location to
    } else { // portion
        new markupPoint(bounds, lat_from_plane, lng_from_plane, false , map); // location from
        new markupPoint(bounds, lat_to_plane, lng_to_plane, location_multi_waypoint_latlng.length + 2, map); // location to
    }
    flightPlanCoordinates.push({ lat: lat_to_plane, lng: lng_to_plane });
    var flightPath = new google.maps.Polyline({
        path: flightPlanCoordinates,
        geodesic: true,
        icons: [
            {
                icon: lineSymbol,
                offset: "0",
                repeat: "20px",
            },
            {
                icon: lineSymbol1,
                offset: "100%",
            },
        ],
        strokeColor: '#0688E9',
        strokeOpacity: 0,
        strokeWeight: 3
    });
    flightPath.setMap(map);
    map.fitBounds(bounds);
}


function addMarker(my_route,type){
    mark_number = 0
    console.log(my_route);
    var path = [];
    for (let j = 0; j < my_route.overview_path.length; j++) {
        subpath = [[my_route.overview_path[j]['lng'](),my_route.overview_path[j]['lat']()],[my_route.overview_path[j]['lng'](),my_route.overview_path[j]['lat']()]];
        path.push(subpath[0]);
    }
    for (var i = 0; i < my_route.legs.length; i++) {
        var marker = new google.maps.Marker({
            position: my_route.legs[i].start_location,
            //icon: 'https://planiversity.com/assets/images/icon.png',
              icon: {
                url: "https://planiversity.com/assets/images/Selected_A.png",
                size: new google.maps.Size(100, 100),
                anchor: new google.maps.Point(40, 40),
              },            
            label: {
                text: markerAlpaArr[i],
                color: "#ffffff",
            },
            visible: type == "event" ? false : true,
            map: map
        });
    }
    
  if (type == "event") {
    var labelProp = null;
    map.setZoom(14);
  } else {
    var labelProp = {
      text: markerAlpaArr[i],
      color: "#ffffff",
    };
  }
    var marker = new google.maps.Marker({
        position: my_route.legs[i-1].end_location,
        //icon: 'https://planiversity.com/assets/images/icon.png',
        icon: {
        url:
        type == "event"
          ? "https://planiversity.com/assets/images/Selected_B.png"
          : "https://planiversity.com/assets/images/Selected_A.png",
        size: new google.maps.Size(100, 100),
        anchor: new google.maps.Point(40, 40),
        },       
        label: labelProp,
        map: map
    });
    console.log(JSON.stringify(path))
    $("#localhost_full_path").val(JSON.stringify(path));
    mark_number = i;
}

function AutocompleteDirectionsHandler(map, transport,lat_from, lng_from, lat_to, lng_to, location_multi_waypoint_latlng, trip_via_waypoints, portion_v  = false ,marker_b,driving_start_indicate ="on") {
    var waypoints = [];
    console.log(portion_v)
    var location_multi_waypoint_latlng_string = location_multi_waypoint_latlng;
    var via_waypoints = trip_via_waypoints.length > 0 ? trip_via_waypoints : [];
    var location_multi_waypoint_latlng = location_multi_waypoint_latlng_string.length ? location_multi_waypoint_latlng_string : [];
    for (var k = 0; k < location_multi_waypoint_latlng.length + 1; k++) {
        for (var kk = 0; kk < via_waypoints.length; kk++) {
            if (k == via_waypoints[kk].index) {
                var pt = new google.maps.LatLng(via_waypoints[kk].lat, via_waypoints[kk].lng);
                waypoints.push({
                    location: pt,
                    stopover: false
                });
            }
        }
        if (k < location_multi_waypoint_latlng.length) {
            var location = location_multi_waypoint_latlng[k].location_multi_waypoint_latlng;
            waypoints.push({
                location: location,
                stopover: true
            })
        }

    }
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById('panel'));
    console.log(portion_v)
    if (transport == 'driving') {
        var request = {
            origin: document.getElementById((portion_v == true?"trip_location_from_drivingportion":"location_from")).value,
            destination: document.getElementById((portion_v == true ?"trip_location_to_drivingportion":"location_to")).value,
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
    }
    if (transport == 'train') {
        var request = {
            origin: document.getElementById((portion_v == true?"trip_location_from_trainportion":"location_from")).value,
            destination: document.getElementById((portion_v == true ?"trip_location_to_trainportion":"location_to")).value,
            waypoints: waypoints,
            optimizeWaypoints: true,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
    }

    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            map.fitBounds(response.routes[0].bounds);
            var line = new google.maps.Polyline({
                path: response.routes[0].overview_path,
                strokeColor: '#0688E9',
                strokeOpacity: 1.0,
                strokeWeight: 3
            });
            line.setMap(map);
            if(portion_v == true){
                var marker1 = new google.maps.Marker({
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: " ",
                        color: "#ffffff",
                    },
                    position:response.routes[0].legs[0].start_location,
                    map: map,
                    visible: driving_start_indicate == "on" ? true : false,
                });
                var marker2 = new google.maps.Marker({
                    icon: {
                    url: "https://planiversity.com/assets/images/Selected_A.png",
                    size: new google.maps.Size(100, 100),
                    anchor: new google.maps.Point(40, 40),
                    },
                    label: {
                        text: marker_b,
                        color: "#ffffff",
                    },
                    position: response.routes[0].legs[0].end_location,
                    map: map
                });
            }else{
                console.log(response);
                addMarker(response.routes[0],itinerary_type_mode);
                var center_point = response.routes[0].overview_path.length / 2;
                
                if(transport == "driving"){
                    if (itinerary_type_mode != "event") {
                        var infowindow = new google.maps.InfoWindow();
                        infowindow.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>" + calcTotalDistanceText(response) + "<br>" + calcTotalDurationText(response) + "</div>");
                    }
                    
                }
                if(transport == "train"){
                    var infowindow = new google.maps.InfoWindow();
                    infowindow.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/train_icon2.png'></div><div style='float:right; padding: 3px;'>" + response.routes[0].legs[0].distance.text + "<br>" + response.routes[0].legs[0].duration.text + "</div>");
                }
                infowindow.setPosition(response.routes[0].overview_path[center_point | 0]);
                infowindow.open(map);
            }
        }
    });
}