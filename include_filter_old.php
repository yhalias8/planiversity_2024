<script src="<?= SITE; ?>js/flexcroll.js"></script>

<script type="text/javascript">
    /********************  weather begin  ****************************/
    function getAccuWeather(status) {
        if (!status) { // close div
            $('#weather_result').slideUp('slow');
            //document.getElementById("weather_result").innerHTML = '';
            return;
        }
        //var cityname = $("#location_to").val();
        //awxCityLookUp(cityname);
        $('#weather_result').slideDown('slow');
    }

    /********************  weather end  ****************************/
    function show_win(id) {
        $('#icon_details' + id).toggle();
        $('#win_details' + id).toggle('slow');
    }

    function initSubwayMap() {
        var latitud = Number(document.getElementById('lat_click').value); //40.7830603;
        var longitud = Number(document.getElementById('lng_click').value); //-73.97124880000001;
        var subway = new google.maps.Map(document.getElementById('subway_result'), {
            zoom: 12,
            //center: {lat: document.getElementById('lat_click').value, lng: document.getElementById('lng_click').value}
            //center: {lat: <?php if ($lat_to) echo $lat_to;
                            else { ?>latitud<?php } ?>, lng: <?php if ($lat_to) echo $lat_to;
                                                                else { ?>longitud<?php } ?>}
            center: {
                lat: latitud,
                lng: longitud
            }
        });
        var transitLayer = new google.maps.TransitLayer();
        transitLayer.setMap(subway);
    }

    function updateSubwayMap() {
        setTimeout(function() {
            if (document.getElementById('filter_subway').checked) {
                if (document.getElementById('location_to_latlng') && document.getElementById('location_to_latlng').value != '') {
                    var tmp = String(document.getElementById('location_to_latlng').value);
                    var str = tmp.replace('(', '');
                    tmp = str.replace(')', '');
                    str = tmp.replace(' ', '');
                    tmp = str.split(',');
                    document.getElementById('lat_click').value = tmp[0];
                    document.getElementById('lng_click').value = tmp[1];
                }
                initSubwayMap();
            }
        }, 5000);
    }

    function getSubwayMap(status) {
        if (document.getElementById('lat_click').value != '' || document.getElementById('location_to_latlng').value != '') {
            /* continue */
        } else {
            document.getElementById('filter_subway').checked = false;
            return;
        }
        $('#subway_result').toggle('slow');
        if (status) {
            if (document.getElementById('location_to_latlng') && document.getElementById('location_to_latlng').value != '') {
                var tmp = String(document.getElementById('location_to_latlng').value);
                var str = tmp.replace('(', '');
                tmp = str.replace(')', '');
                str = tmp.replace(' ', '');
                tmp = str.split(',');
                document.getElementById('lat_click').value = tmp[0];
                document.getElementById('lng_click').value = tmp[1];
            }
            initSubwayMap();
        } else
            document.getElementById("subway_result").innerHTML = '';
    }

    $(document).ready(function() {
        $('#filter_bus_station').click(function() {
            $('#busmap_result').toggle('slow');
        });
    });
    <?php if ($trip->trip_option_subway) { ?>setTimeout(function() {
        getSubwayMap(1);
    }, 5000);
    <?php } ?>
    <?php if ($trip->trip_option_weather) { ?>setTimeout(function() {
        getAccuWeather(1);
    }, 5000);
    <?php } ?>
</script>
<style>
    .modal .modal-dialog .close10 {

        top: 11px;
        position: absolute;
        right: 28px;
        height: 36px;
        /* width: 100%; */
        opacity: 1;
        /* text-shadow: none; */
        color: #0473ba;
        border-radius: 5px;
        font-size: 17px;
        /* text-align: end; */
        border: 1px solid #0473ba;
        padding-left: 1rem;
        padding-right: 1rem;
    }
</style>
<div class="filter-checks-wrapper  rounded p-0 h-100">
    <h6 class="modal-sub-title"><img class="modal-sub-title-img" src="<?= SITE; ?>/images/filters.png"></img>Filters</h6>
    <div class="modal-content rounded p-2 filter-modal-left-content border-grey">
        <h6 class="main-color"><img class="modal-sub-title-img" src="<?= SITE; ?>/images/sun.png"></img>Weather</h6>
        <div class="ml-2 checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_weather" <?php if ($trip && $trip->trip_option_weather) echo 'checked="checked"'; ?> value="weather" onchange="getAccuWeather(this.checked)" type="checkbox">
            <label for="filter_weather">
                Weather at Destination
            </label>
        </div>
        <div id="weather_result" style="display: none;" class="weather-wrapper">
            <?= str_replace("Array", "", $trip->getAccuWeatherFilters()); ?>
        </div>
        <div class="filter-modal-left-devider"></div>
        <ul>
            <li class="mb-10px"><a id="tgl-fascilities" href="#" class="main-color"><img class="modal-sub-title-img" src="<?= SITE; ?>/images/location.png"></img>Facilities <i class="facilities-arrow-right fa fa-chevron-right"></i></a></li>
            <!-- <li><a href = "" data-toggle="modal" data-target="#expanded-map-modal">Maps <i class = "fa fa-chevron-right"></i></a></li> -->
        </ul>
        <!--<h6>Facility</h6>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_hotels" type="checkbox" <?php if ($trip && $trip->trip_option_hotels) echo 'checked="checked"'; ?> value="hotels" onchange="NearbyPlacesHandler('lodging',this.checked)">-->
        <!--    <label for="filter_hotels">-->
        <!--        Hotels/Motels-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_police" type="checkbox" <?php if ($trip && $trip->trip_option_police) echo 'checked="checked"'; ?> value="police" onchange="NearbyPlacesHandler('police',this.checked)">-->
        <!--    <label for="filter_police">-->
        <!--        Police Stations-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_hospitals" type="checkbox" <?php if ($trip && $trip->trip_option_hospitals) echo 'checked="checked"'; ?> value="hospitals" onchange="NearbyPlacesHandler('hospital',this.checked)">-->
        <!--    <label for="filter_hospitals">-->
        <!--        Hospitals-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_gas" type="checkbox" <?php if ($trip && $trip->trip_option_gas) echo 'checked="checked"'; ?> value="gas" onchange="NearbyPlacesHandler('gas_station',this.checked)">-->
        <!--    <label for="filter_gas">-->
        <!--        Service Station (Gas/Petrol/Diesel)-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_taxi" type="checkbox" <?php if ($trip && $trip->trip_option_taxi) echo 'checked="checked"'; ?> value="taxi" onchange="NearbyPlacesHandler('taxi_stand',this.checked)">-->
        <!--    <label for="filter_taxi">-->
        <!--        Taxi Services-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_airport" type="checkbox" <?php if ($trip && $trip->trip_option_airfields) echo 'checked="checked"'; ?> value="airports" onchange="NearbyPlacesHandler('airport',this.checked)">-->
        <!--    <label for="filter_airport">-->
        <!--        Airports/Heliports-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_parking" type="checkbox" <?php if ($trip && $trip->trip_option_parking) echo 'checked="checked"'; ?> value="parking" onchange="NearbyPlacesHandler('parking',this.checked)">-->
        <!--    <label for="filter_parking">-->
        <!--        Parking-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_university" type="checkbox" <?php if ($trip && $trip->trip_option_university) echo 'checked="checked"'; ?> value="university" onchange="NearbyPlacesHandler('university',this.checked)">-->
        <!--    <label for="filter_university">-->
        <!--        Universities-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_atm" type="checkbox" <?php if ($trip && $trip->trip_option_atm) echo 'checked="checked"'; ?> value="atm" onchange="NearbyPlacesHandler('atm',this.checked)">-->
        <!--    <label for="filter_atm">-->
        <!--        ATM-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_museum" type="checkbox" <?php if ($trip && $trip->trip_option_museum) echo 'checked="checked"'; ?> value="museum" onchange="NearbyPlacesHandler('museum',this.checked)">-->
        <!--    <label for="filter_museum">-->
        <!--        Museums-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_church" type="checkbox" <?php if ($trip && $trip->trip_option_church) echo 'checked="checked"'; ?> value="church" onchange="NearbyPlacesHandler('church',this.checked)">-->
        <!--    <label for="filter_church">-->
        <!--        Religious Institutions-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_subway_station" type="checkbox" <?php if ($trip && $trip->trip_option_subway_station) echo 'checked="checked"'; ?> value="subway_station" onchange="NearbyPlacesHandler('subway_station',this.checked)">-->
        <!--    <label for="filter_metro">-->
        <!--        Subway Stations-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_metro" type="checkbox" <?php if ($trip && $trip->trip_option_metro) echo 'checked="checked"'; ?> value="metro" onchange="NearbyPlacesHandler('train_station',this.checked)">-->
        <!--    <label for="filter_metro">-->
        <!--        Metro Stations-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_playground" type="checkbox" <?php if ($trip && $trip->trip_option_playground) echo 'checked="checked"'; ?> value="playground" onchange="NearbyPlacesHandler('park',this.checked)">-->
        <!--    <label for="filter_playground">-->
        <!--        Parks-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_library" type="checkbox" <?php if ($trip && $trip->trip_option_library) echo 'checked="checked"'; ?> value="library" onchange="NearbyPlacesHandler('library',this.checked)">-->
        <!--    <label for="filter_library">-->
        <!--        Libraries-->
        <!--    </label>-->
        <!--</div>-->
        <!--<div class="checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_pharmacy" type="checkbox" <?php if ($trip && $trip->trip_option_pharmacy) echo 'checked="checked"'; ?> value="pharmacy" onchange="NearbyPlacesHandler('pharmacy',this.checked)">-->
        <!--    <label for="filter_pharmacy">-->
        <!--        Pharmacies-->
        <!--    </label>-->
        <!--</div>-->
        <div class="filter-modal-left-devider"></div>
        <h6 class="main-color"><img class="modal-sub-title-img" src="<?= SITE; ?>/images/map_copy.png"></img>Map</h6>
        <div class="ml-2 checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_embassis" type="checkbox" <?php if ($trip && $trip->trip_option_embassis) echo 'checked="checked"'; ?> value="embassis" onchange="NearbyPlacesHandler('embassy',this.checked)">
            <label for="filter_embassis">
                Embassy Map
            </label>
        </div>
        <div class="embassy-list-wrapper scrollbar" id="embassy_result" style="display:none;">
        </div>
        <!--<div class="ml-2 checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_bus_station"-->
        <!--           type="checkbox" <?php if ($trip && $trip->trip_option_busmap) echo 'checked="checked"'; ?>-->
        <!--           value="busmap">-->
        <!--    <label for="filter_bus_station">-->
        <!--        Bus Map-->
        <!--    </label>-->
        <!--</div>-->
        <div id="busmap_result" style="display:none;">
            <?= $busmapimg; ?>
        </div>
        <?php if ($trip->trip_transport == 'vehicle' || $trip->trip_location_from_drivingportion) { ?>
            <div class="ml-2 checkbox checkbox-primary">
                <input name="filter_option[]" id="filter_directions" type="checkbox" <?php if ($trip && $trip->trip_option_directions) echo 'checked="checked"'; ?> value="directions">
                <label for="filter_directions">
                    Directions
                </label>
            </div>
        <?php } ?>
        <?php if ($trip->trip_transport == 'train' || $trip_has_train) { ?>
            <div class="ml-2 checkbox checkbox-primary">
                <input name="filter_option[]" id="filter_directions_train" type="checkbox" <?php if ($trip && $trip->trip_option_directions) echo 'checked="checked"'; ?> value="directions">
                <label for="filter_directions_train">
                    Train Directions
                </label>
            </div>
        <?php } ?>
        <div class="ml-2 checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_subway" type="checkbox" <?php if ($trip && $trip->trip_option_subway) echo 'checked="checked"'; ?> value="subway" onchange="getSubwayMap(this.checked)">
            <label for="filter_subway">
                Subway Map
            </label>
        </div>
        <div id="subway_result" style="display:none;"></div>
        <input type="hidden" name="filter_click" id="filter_click" value="">
        <input type="hidden" name="lat_click" id="lat_click" value="<?= $lat_to; ?>">
        <input type="hidden" name="lng_click" id="lng_click" value="<?= str_replace(' ', '', $lng_to); ?>">
    </div>
</div>
<div data-backdrop="false" id="expanded-facilities-modal" class="modal fade bs-example-modal-lg show filter-details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-bg">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Facilities</h4>
                <button type="button" id="facility-cross" class="create-trip-btn text-dark" aria-hidden="true">Save And Close</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_hotels" type="checkbox" <?php if ($trip && $trip->trip_option_hotels) echo 'checked="checked"'; ?> value="hotels" onchange="NearbyPlacesHandler('lodging',this.checked)">
                            <label for="filter_hotels" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/lodging.png">
                                </span>
                                Hotels/Motels
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_police" type="checkbox" <?php if ($trip && $trip->trip_option_police) echo 'checked="checked"'; ?> value="police" onchange="NearbyPlacesHandler('police',this.checked)">
                            <label for="filter_police" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/police.png">
                                </span>
                                Police Stations
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_hospitals" type="checkbox" <?php if ($trip && $trip->trip_option_hospitals) echo 'checked="checked"'; ?> value="hospitals" onchange="NearbyPlacesHandler('hospital',this.checked)">
                            <label for="filter_hospitals" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/hospital.png">
                                </span>
                                Hospitals
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_airport" type="checkbox" <?php if ($trip && $trip->trip_option_airfields) echo 'checked="checked"'; ?> value="airports" onchange="NearbyPlacesHandler('airport',this.checked)">
                            <label for="filter_airport" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/airport.png">
                                </span>
                                Airports/Heliports
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_parking" type="checkbox" <?php if ($trip && $trip->trip_option_parking) echo 'checked="checked"'; ?> value="parking" onchange="NearbyPlacesHandler('parking',this.checked)">
                            <label for="filter_parking" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/parking.png">
                                </span>
                                Parking
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_subway_station" type="checkbox" <?php if ($trip && $trip->trip_option_subway_station) echo 'checked="checked"'; ?> value="subway_station" onchange="NearbyPlacesHandler('subway_station',this.checked)">
                            <label for="filter_subway_station" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/train_station.png">
                                </span>
                                Subway Stations
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_gas" type="checkbox" <?php if ($trip && $trip->trip_option_gas) echo 'checked="checked"'; ?> value="gas" onchange="NearbyPlacesHandler('gas_station',this.checked)">
                            <label for="filter_gas" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/gas_station.png">
                                </span>
                                Service Station (Gas/Petrol/Diesel)
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_taxi" type="checkbox" <?php if ($trip && $trip->trip_option_taxi) echo 'checked="checked"'; ?> value="taxi" onchange="NearbyPlacesHandler('taxi_stand',this.checked)">
                            <label for="filter_taxi" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/taxi_stand.png">
                                </span>
                                Taxi Services
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_university" type="checkbox" <?php if ($trip && $trip->trip_option_university) echo 'checked="checked"'; ?> value="university" onchange="NearbyPlacesHandler('university',this.checked)">
                            <label for="filter_university" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/university.png">
                                </span>
                                Universities
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_atm" type="checkbox" <?php if ($trip && $trip->trip_option_atm) echo 'checked="checked"'; ?> value="atm" onchange="NearbyPlacesHandler('atm',this.checked)">
                            <label for="filter_atm" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/atm.png">
                                </span>
                                ATM
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_library" type="checkbox" <?php if ($trip && $trip->trip_option_library) echo 'checked="checked"'; ?> value="library" onchange="NearbyPlacesHandler('library',this.checked)">
                            <label for="filter_library" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/library.png">
                                </span>
                                Libraries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_museum" type="checkbox" <?php if ($trip && $trip->trip_option_museum) echo 'checked="checked"'; ?> value="museum" onchange="NearbyPlacesHandler('museum',this.checked)">
                            <label for="filter_museum" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/museum.png">
                                </span>
                                Museums
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_church" type="checkbox" <?php if ($trip && $trip->trip_option_church) echo 'checked="checked"'; ?> value="church" onchange="NearbyPlacesHandler('church',this.checked)">
                            <label for="filter_church" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/church.png">
                                </span>
                                Religious Institutions
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_metro" type="checkbox" <?php if ($trip && $trip->trip_option_metro) echo 'checked="checked"'; ?> value="metro" onchange="NearbyPlacesHandler('train_station',this.checked)">
                            <label for="filter_metro" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/train_station.png">
                                </span>
                                Metro Stations
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_playground" type="checkbox" <?php if ($trip && $trip->trip_option_playground) echo 'checked="checked"'; ?> value="playground" onchange="NearbyPlacesHandler('park',this.checked)">
                            <label for="filter_playground" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/park.png">
                                </span>
                                Parks
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_pharmacy" type="checkbox" <?php if ($trip && $trip->trip_option_pharmacy) echo 'checked="checked"'; ?> value="pharmacy" onchange="NearbyPlacesHandler('pharmacy',this.checked)">
                            <label for="filter_pharmacy" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/hospital.png">
                                </span>
                                Pharmacy
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_covid" type="checkbox" <?php if ($trip && $trip->trip_option_covid) echo 'checked="checked"'; ?> value="covid" onchange="NearbyPlacesHandler('covid_testing_center',this.checked)">
                            <label for="filter_covid" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?= SITE; ?>images/map-icons/covid.png">
                                </span>
                                Covid Testing
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
