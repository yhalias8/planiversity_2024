<script src="<?php echo SITE; ?>js/flexcroll.js"></script>

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
    .resource-list::-webkit-scrollbar {
        width: 8px;
        background-color: #e0eef9;
    }

    .resource-list::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #e0eef9;
        border-radius: 8px;
    }

    .resource-list::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #a1d1f5;
        border-radius: 8px;
    }

    .resource-list {
        /*margin-top: 24px !important;*/
        max-height: 430px;
        overflow-y: auto;
        overflow-x: hidden;
    }


    .resource-list__type {
        margin-left: -6px;
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .resource-list__type img {
        width: 20px;
        height: 20px;
    }

    .resource-list__item {
        color: #000 !important;
        width: 100%;
        border: 1px solid #b4dbf8;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        padding: 6px 12px;
        background: #fff;
        margin-bottom: 5px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
    }

    .resource-list__actions {
        display: flex;
        gap: 5px;
        justify-content: end;
    }

    .resource-list__action-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        padding: 8px;
    }

    .resource-list__action-btn i {
        font-size: 13px;
        justify-content: center;
    }

    .resource-list__action-btn_edit {
        background: #c8dbeb;
        color: #fff;
        border-color: #c8dbeb;
    }

    .resource-list__action-btn_delete {
        background: #e7dfd5;
        border-color: #e7dfd5;
    }


    .resource-list__heading h4 {
        color: #2298f0;
        font-size: 15px;
        word-break: break-all;
    }

    .resource-list__body {
        margin-top: 16px;
        font-size: 12px !important;
    }

    .resource-list__body i {
        margin-right: 5px;
        color: #2298f0;
    }

    .resource-list__body h6 {
        color: #000;
        font-size: 13px;
    }

    .resource-list__heading {
        display: grid;
        grid-template-columns: 1fr 80px;
        align-items: center;
    }



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
<div class="filter-checks-wrapper rounded p-0 rounded-plus">
    <h6 class="modal-sub-title"><img class="modal-sub-title-img" src="<?php echo SITE; ?>/images/filters.png"></img>Resources</h6>
    <div class="rounded p-2 filter_modal_left border-grey filter-content rounded-plus ">
        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>

        <label for="resource_address">Add your own point</label>
        <input type="text" id="resource_address" name="resource_address" class="dashboard-form-control mb-2 form-control input-lg clearable" placeholder="Enter address, click on map, or search" />

        <button id="resource_add" class="btn btn-resource-add">Add</button>
        <?php } ?>

        <ul id="resource-list" class="resource-list">

        </ul>






        <!--        <h6 class="main-color"><img class="modal-sub-title-img" src="--><?php //echo SITE; 
                                                                                    ?><!--/images/sun.png"></img>Weather</h6>-->
        <!--        <div class="ml-2 checkbox checkbox-primary">-->
        <!--            <input name="filter_option[]" id="filter_weather" --><?php //if ($trip && $trip->trip_option_weather) echo 'checked="checked"'; 
                                                                                ?><!-- value="weather" onchange="getAccuWeather(this.checked)" type="checkbox">-->
        <!--            <label for="filter_weather">-->
        <!--                <p class="left_option">Weather at Destination</p>-->
        <!--            </label>-->
        <!--        </div>-->
        <!--        <div id="weather_result" style="display: none;" class="weather-wrapper">-->
        <!--            --><?PHP //echo str_replace("Array", "", $trip->getAccuWeatherNew()); 
                            ?>
        <!--        </div>-->
        <!--        <div class="filter-modal-left-devider pt-2 mb-2"></div>-->
        <!-- <ul> -->
        <!-- <li class="mb-10px"><a id="tgl-fascilities" href="javascript:void(0)" class="main-color"><img class="modal-sub-title-img" src="<?php echo SITE; ?>/images/location.png"></img>Facilities <i class="facilities-arrow-right fa fa-chevron-right"></i></a></li> -->
        <!-- <li><a href = "" data-toggle="modal" data-target="#expanded-map-modal">Maps <i class = "fa fa-chevron-right"></i></a></li> -->
        <!-- </ul> -->
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
        <!-- <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_university" type="checkbox" <?php if ($trip && $trip->trip_option_university) echo 'checked="checked"'; ?> value="university" onchange="NearbyPlacesHandler('university',this.checked)">
            <label for="filter_university">
                Universities
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_atm" type="checkbox" <?php if ($trip && $trip->trip_option_atm) echo 'checked="checked"'; ?> value="atm" onchange="NearbyPlacesHandler('atm',this.checked)">
            <label for="filter_atm">
                ATM
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_museum" type="checkbox" <?php if ($trip && $trip->trip_option_museum) echo 'checked="checked"'; ?> value="museum" onchange="NearbyPlacesHandler('museum',this.checked)">
            <label for="filter_museum">
                Museums
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_church" type="checkbox" <?php if ($trip && $trip->trip_option_church) echo 'checked="checked"'; ?> value="church" onchange="NearbyPlacesHandler('church',this.checked)">
            <label for="filter_church">
                Religious Institutions
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_subway_station" type="checkbox" <?php if ($trip && $trip->trip_option_subway_station) echo 'checked="checked"'; ?> value="subway_station" onchange="NearbyPlacesHandler('subway_station',this.checked)">
            <label for="filter_metro">
                Subway Stations
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_metro" type="checkbox" <?php if ($trip && $trip->trip_option_metro) echo 'checked="checked"'; ?> value="metro" onchange="NearbyPlacesHandler('train_station',this.checked)">
            <label for="filter_metro">
                Metro Stations
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_playground" type="checkbox" <?php if ($trip && $trip->trip_option_playground) echo 'checked="checked"'; ?> value="playground" onchange="NearbyPlacesHandler('park',this.checked)">
            <label for="filter_playground">
                Parks
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_library" type="checkbox" <?php if ($trip && $trip->trip_option_library) echo 'checked="checked"'; ?> value="library" onchange="NearbyPlacesHandler('library',this.checked)">
            <label for="filter_library">
                Libraries
            </label>
        </div>
        <div class="checkbox checkbox-primary">
            <input name="filter_option[]" id="filter_pharmacy" type="checkbox" <?php if ($trip && $trip->trip_option_pharmacy) echo 'checked="checked"'; ?> value="pharmacy" onchange="NearbyPlacesHandler('pharmacy',this.checked)">
            <label for="filter_pharmacy">
                Pharmacies
            </label>
        </div> -->
        <!-- <div class="filter-modal-left-devider"></div> -->
        <!--        <h6 class="main-color"><img class="modal-sub-title-img" src="--><?php //echo SITE; 
                                                                                    ?><!--/images/map_copy.png"></img>Map</h6>-->
        <!--        <div class="ml-2 checkbox checkbox-primary">-->
        <!--            <input name="filter_option[]" id="filter_embassis" type="checkbox" --><?php //if ($trip && $trip->trip_option_embassis) echo 'checked="checked"'; 
                                                                                                ?><!-- value="embassis" onchange="NearbyPlacesHandler('embassy',this.checked)">-->
        <!--            <label for="filter_embassis">-->
        <!--                <p class="left_option">Embassy Map</p>-->
        <!--            </label>-->
        <!--        </div>-->
        <!--         <div class="embassy_search" id="embassy_search" style="display:none">-->
        <!--            <input class="form-control embassy_field" id="embassy_search_field" placeholder="search">-->
        <!--        </div>        -->
        <!--        <div class="embassy-list-wrapper scrollbar" id="embassy_result" style="display:none;">-->
        <!--        </div>-->
        <!--<div class="ml-2 checkbox checkbox-primary">-->
        <!--    <input name="filter_option[]" id="filter_bus_station"-->
        <!--           type="checkbox" <?php if ($trip && $trip->trip_option_busmap) echo 'checked="checked"'; ?>-->
        <!--           value="busmap">-->
        <!--    <label for="filter_bus_station">-->
        <!--        Bus Map-->
        <!--    </label>-->
        <!--</div>-->
        <!--        <div id="busmap_result" style="display:none;">-->
        <!--            --><?PHP //echo $busmapimg; 
                            ?>
        <!--        </div>-->
        <!--        --><?php //if ($trip->trip_transport == 'vehicle' || $trip->trip_location_from_drivingportion) { 
                        ?>
        <!--            <div class="ml-2 checkbox checkbox-primary">-->
        <!--                <input name="filter_option[]" id="filter_directions" type="checkbox" --><?php //if ($trip && $trip->trip_option_directions) echo 'checked="checked"'; 
                                                                                                    ?><!-- value="directions">-->
        <!--                <label for="filter_directions">-->
        <!--                    <p class="left_option">Directions</p>-->
        <!--                </label>-->
        <!--            </div>-->
        <!--        --><?php //} 
                        ?>
        <!--        --><?php //if ($trip->trip_transport == 'train' || $trip_has_train) { 
                        ?>
        <!--            <div class="ml-2 checkbox checkbox-primary">-->
        <!--                <input name="filter_option[]" id="filter_directions_train" type="checkbox" --><?php //if ($trip && $trip->trip_option_directions) echo 'checked="checked"'; 
                                                                                                            ?><!-- value="directions">-->
        <!--                <label for="filter_directions_train">-->
        <!--                    <p class="left_option">Train Directions</p>-->
        <!--                </label>-->
        <!--            </div>-->
        <!--        --><?php //} 
                        ?>

        <div id="subway_result" style="display:none;"></div>
        <input type="hidden" name="filter_click" id="filter_click" value="">
        <input type="hidden" name="lat_click" id="lat_click" value="<?php echo $filter_lat_to; ?>">
        <input type="hidden" name="lng_click" id="lng_click" value="<?php echo str_replace(' ', '', $filter_lng_to); ?>">
    </div>
</div>
<!-- <div data-backdrop="false" id="expanded-facilities-modal" class="modal fade bs-example-modal-lg show filter-details-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                    <img src="<?php echo SITE; ?>images/map-icons/lodging.png">
                                </span>
                                Hotels/Motels
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_police" type="checkbox" <?php if ($trip && $trip->trip_option_police) echo 'checked="checked"'; ?> value="police" onchange="NearbyPlacesHandler('police',this.checked)">
                            <label for="filter_police" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/police.png">
                                </span>
                                Police Stations
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_hospitals" type="checkbox" <?php if ($trip && $trip->trip_option_hospitals) echo 'checked="checked"'; ?> value="hospitals" onchange="NearbyPlacesHandler('hospital',this.checked)">
                            <label for="filter_hospitals" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/hospital.png">
                                </span>
                                Hospitals
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_airport" type="checkbox" <?php if ($trip && $trip->trip_option_airfields) echo 'checked="checked"'; ?> value="airports" onchange="NearbyPlacesHandler('airport',this.checked)">
                            <label for="filter_airport" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/airport.png">
                                </span>
                                Airports/Heliports
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_parking" type="checkbox" <?php if ($trip && $trip->trip_option_parking) echo 'checked="checked"'; ?> value="parking" onchange="NearbyPlacesHandler('parking',this.checked)">
                            <label for="filter_parking" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/parking.png">
                                </span>
                                Parking
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_subway_station" type="checkbox" <?php if ($trip && $trip->trip_option_subway_station) echo 'checked="checked"'; ?> value="subway_station" onchange="NearbyPlacesHandler('subway_station',this.checked)">
                            <label for="filter_subway_station" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/train_station.png">
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
                                    <img src="<?php echo SITE; ?>images/map-icons/gas_station.png">
                                </span>
                                Service Station (Gas/Petrol/Diesel)
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_taxi" type="checkbox" <?php if ($trip && $trip->trip_option_taxi) echo 'checked="checked"'; ?> value="taxi" onchange="NearbyPlacesHandler('taxi_stand',this.checked)">
                            <label for="filter_taxi" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/taxi_stand.png">
                                </span>
                                Taxi Services
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_university" type="checkbox" <?php if ($trip && $trip->trip_option_university) echo 'checked="checked"'; ?> value="university" onchange="NearbyPlacesHandler('university',this.checked)">
                            <label for="filter_university" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/university.png">
                                </span>
                                Universities
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_atm" type="checkbox" <?php if ($trip && $trip->trip_option_atm) echo 'checked="checked"'; ?> value="atm" onchange="NearbyPlacesHandler('atm',this.checked)">
                            <label for="filter_atm" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/atm.png">
                                </span>
                                ATM
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_library" type="checkbox" <?php if ($trip && $trip->trip_option_library) echo 'checked="checked"'; ?> value="library" onchange="NearbyPlacesHandler('library',this.checked)">
                            <label for="filter_library" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/library.png">
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
                                    <img src="<?php echo SITE; ?>images/map-icons/museum.png">
                                </span>
                                Museums
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_church" type="checkbox" <?php if ($trip && $trip->trip_option_church) echo 'checked="checked"'; ?> value="church" onchange="NearbyPlacesHandler('church',this.checked)">
                            <label for="filter_church" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/church.png">
                                </span>
                                Religious Institutions
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_metro" type="checkbox" <?php if ($trip && $trip->trip_option_metro) echo 'checked="checked"'; ?> value="metro" onchange="NearbyPlacesHandler('train_station',this.checked)">
                            <label for="filter_metro" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/train_station.png">
                                </span>
                                Metro Stations
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_playground" type="checkbox" <?php if ($trip && $trip->trip_option_playground) echo 'checked="checked"'; ?> value="playground" onchange="NearbyPlacesHandler('park',this.checked)">
                            <label for="filter_playground" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/park.png">
                                </span>
                                Parks
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_pharmacy" type="checkbox" <?php if ($trip && $trip->trip_option_pharmacy) echo 'checked="checked"'; ?> value="pharmacy" onchange="NearbyPlacesHandler('pharmacy',this.checked)">
                            <label for="filter_pharmacy" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/hospital.png">
                                </span>
                                Pharmacy
                            </label>
                        </div>
                        <div class="checkbox checkbox-primary ml-4">
                            <input name="filter_option[]" id="filter_covid" type="checkbox" <?php if ($trip && $trip->trip_option_covid) echo 'checked="checked"'; ?> value="covid" onchange="NearbyPlacesHandler('covid_testing_center',this.checked)">
                            <label for="filter_covid" class="black-checkbox-label">
                                <span class="map-icons">
                                    <img src="<?php echo SITE; ?>images/map-icons/covid.png">
                                </span>
                                Covid Testing
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->