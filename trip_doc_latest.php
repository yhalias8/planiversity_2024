<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'trip/travel-documents/' . $_GET['idtrip'];
  header("Location:" . SITE . "login");
}
$output = '';
include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip)) header("Location:" . SITE . "trip/how-are-you-traveling");
$trip->get_data($id_trip);
if ($trip->error) {
  if ($trip->error == 'error_access') { // popup and 
    header("Location:" . SITE . "trip/how-are-you-traveling");
    //$output = 'You do not have access to this trip';
  } else
    $output = 'A system error has been encountered. Please try again.';
}
$transport = (isset($trip->trip_transport) && !empty($trip->trip_transport)) ? $trip->trip_transport : '';
$tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_from = $tmp[0];
$lng_from = $tmp[1];
$tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_to = $tmp[0];
$lng_to = $tmp[1];

$travelmode = 'DRIVING';
switch ($transport) {
  case 'vehicle':
    $travelmode = 'DRIVING';
    break;
  case 'train':
    $travelmode = 'TRANSIT';
    break;
}

if (isset($_POST['documents_submit'])) {
  header("Location:" . SITE . "trip/add-employee-profile/" . $id_trip);
  /* $filter = $_POST['filter_option'];
     $embassis = $_POST['embassy_list'];
     // edit data trip in DB
     $trip->edit_data_filter($id_trip,$filter,$embassis);
     if (!$trip->error)
        header("Location:".SITE."trip/add-employee-profile/".$id_trip);
     else
        $output = 'A system error has been encountered. Please try again.';     */
}

include('include_doctype.php');
?>
<html>

<head>
  <meta charset="utf-8">
  <title>PLANIVERSITY - ADD YOUR TRAVEL DOCUMENTS</title>

  <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

  <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />

  <link href="<?php echo SITE; ?>js/upload/uploadfile.css" rel="stylesheet">
  <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
  <script>
    var SITE = '<?php echo SITE; ?>'
  </script>
  <script src="<?php echo SITE; ?>js/trip_documents.js"></script>
  <script src="<?php echo SITE; ?>js/upload/jquery.uploadfile.js"></script>
  <?php include('new_head_files.php'); ?>
  <script>
    function savedoctrip(doc, trip) {
      setTimeout(function() {
        $('#errordocuse_' + doc).hide('fast');
        $.post(SITE + "ajaxfiles/add_documents.php", {
            dt: doc,
            tp: trip
          },
          function(data) {
            if (data['error']) {
              $('#errordocuse_' + doc).html(data['error']);
              $('#errordocuse_' + doc).fadeIn(500);
            } else {
              $('#docuse_' + doc).fadeOut(1000);
            }
          }, "json");
      }, 0);
    }
  </script>

  <script src="<?php echo SITE; ?>js/js_map.js"></script>

  <style>
    input[type=button],
    a.button,
    input[type=submit] {
      text-indent: 0 !important;
    }

    .ajax-file-upload-red {
      background: none;
      color: #0473ba;
      text-shadow: none;
    }

    .ajax-file-upload {
      position: relative;
      overflow: hidden;
      cursor: default;
      float: right;
      padding: 3px;
    }

    label.input-file3 {
      padding: 4px 16px;
      background: #e8f5fd;
      display: inline-block;
      color: #0473ba;
      border: 1px solid #98cae9;
      font-size: 12px;
      margin-bottom: 5px;
      cursor: pointer;
      border-radius: 0px;
      float: none;
      width: 100%;
    }

    .modaltrans {
      height: 66px;
      width: 292px;
      overflow: hidden !important;
      -webkit-transition: all 2s ease;
      -moz-transition: all 2s ease;
      -o-transition: all 2s ease;
      transition: all 2s ease;
    }
  </style>
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
</head>

<body class="custom_documents">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <?php include('new_backend_header.php'); ?>

  <div class="navbar-custom old-site-colors">
    <div class="container-fluid">
      <div id="navigation">
        <ul class="navigation-menu text-center">
          <li>
            <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button top-progress">
              <img src="<?php echo SITE; ?>assets/images/step_calendar.png" alt="Schedule">
              <p>Schedule</p>
            </a>
          </li>
          <li class="step-arrow">
            <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="left-nav-button top-progress">
              <img src="<?php echo SITE; ?>assets/images/step_notes.png" alt="Notes">
              <p>Notes</p>
            </a>
          </li>
          <li class="step-arrow">
            <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>" class="left-nav-button top-progress">
              <img src="<?php echo SITE; ?>assets/images/step_filters.png" alt="Filters">
              <p>Filters</p>
            </a>
          </li>
          <li class="step-arrow box">
            <a href="" class="left-nav-button top-progress active-step scale" data-toggle="modal" data-target="#document-modal">
              <img src="<?php echo SITE; ?>assets/images/step_documents.gif" alt="Documents">
              <p>Documents</p>
            </a>
          </li>
          <li class="step-arrow">
            <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>" class="left-nav-button top-progress">
              <img src="<?php echo SITE; ?>assets/images/step_pdf.png" alt="Export">
              <p>Export</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  </header>

  <div data-backdrop="false" id="document-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myLargeModalLabel">Add Your Travel Documents</h4>
        </div>
        <div class="modal-body">
          <form name="documents_form" method="post" class="routemap">
            <?php //include('include_icondetails.php') 
            ?>

            <div class="error_style"><?php echo $output; ?></div>
            <input name="location_from" id="location_from" class="inp1" value="<?php echo $trip->trip_location_from; ?>" type="hidden">
            <input name="location_to" id="location_to" class="inp1" value="<?php echo $trip->trip_location_to; ?>" type="hidden">
            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
            <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
            <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
            <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
            <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">
            <fieldset>
              <div class="row">
                <div class="col-md-8">
                  <div class="one-doc-wrapper">
                    <h6 class="doc-title">Passport:
                      <label class="input-file3">
                        <div id="fileuploader_passport" class="uploaddocs"></div>
                      </label>
                    </h6>
                    <!--<div id="fileload_passport"  class = "doc-item-wrapper"></div>-->
                    <hr>
                    <!--<a href = "" class = "review-doc-btn">Review Passport Attachments</a>-->
                    <!--<input name="documents_loadpassport" id="documents_loadpassport" type="button" class="review-doc-btn" value="Review Passport Attachments">-->
                  </div>
                  <div class="one-doc-wrapper">
                    <h6 class="doc-title">Driver's License:
                      <label class="input-file3">
                        <div id="fileuploader_driver" class="uploaddocs"></div>
                      </label>
                    </h6>
                    <!--<div id="fileload_driver"  class = "doc-item-wrapper"></div>-->
                    <hr>
                    <!--<a href = "" class = "review-doc-btn">Review Driver's License Attachments</a>-->
                    <!--<input name="documents_loaddriver" id="documents_loaddriver" type="button" class="review-doc-btn" value="Review Driver's License Attachments">-->
                  </div>
                  <div class="one-doc-wrapper">
                    <h6 class="doc-title">Hotel Itinerary:
                      <label class="input-file3">
                        <div id="fileuploader_hotel" class="uploaddocs"></div>
                      </label>
                    </h6>
                    <!--<div id="fileload_hitinerary" class = "doc-item-wrapper"></div>-->
                    <hr>
                    <!--<a href = "" class = "review-doc-btn">Review Hotel Itinerary Attachments</a>-->
                    <!--<input name="documents_loadhitinerary" id="documents_loadhitinerary" type="button" class="review-doc-btn" value="Review Hotel Itinerary Attachments">-->
                  </div>
                  <div class="one-doc-wrapper">
                    <h6 class="doc-title">Travel Itinerary
                      <label class="input-file3">
                        <div id="fileuploader_flight" class="uploaddocs"></div>
                      </label>
                    </h6>
                    <!--<div id="fileload_fitinerary" class = "doc-item-wrapper"></div>-->
                    <hr>
                    <!--<a href = "" class = "review-doc-btn">Review Flight Itinerary Attachments</a>-->
                    <!--<input name="documents_loadfitinerary" id="documents_loadfitinerary" type="button" class="review-doc-btn" value="Review Flight Itinerary Attachments">-->
                  </div>
                  <div class="one-doc-wrapper">
                    <h6 class="doc-title">Additional Document:
                      <label class="input-file3">
                        <div id="fileuploader_additional" class="uploaddocs"></div>
                      </label>
                    </h6>
                    <!--<div id="fileload_additional" class = "doc-item-wrapper"></div>-->
                    <hr>
                    <!--<a href = "" class = "review-doc-btn">Review Additional Documents</a>-->
                    <!--<input name="documents_additional" id="documents_additional" type="button" class="review-doc-btn" value="Review Additional Documents"></div>-->
                  </div>
                </div>
                <?PHP
                $stmt = $dbh->prepare("SELECT * FROM config WHERE setting='Why_Add_Your_Documents'");
                $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                $tmp = $stmt->execute();
                if ($tmp && $stmt->rowCount() > 0) {
                  $text1 = $stmt->fetchAll(PDO::FETCH_OBJ);
                  $text1 = $text1[0]->value;
                }
                ?>
                <div class="col-md-4">
                  <div class="note-info-wrapper">
                    <h3>Why Add Your Documents</h3>
                    <p><?PHP echo $text1; ?></p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10">
                  <div class="form-group">
                    <!--<button type="submit" class="create-trip-btn">Save and Next</button>-->
                    <input name="documents_submit" id="documents_submit" type="submit" class="create-trip-btn" value="Save and Next">
                  </div>
                </div>
                <div class="col-md-2">
                  <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn">Skip This Section</a>
                </div>
              </div>
            </fieldset>
          </form>
          <br clear="all" />
        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {

      $("#fileuploader_passport").uploadFile({
        url: SITE + "ajaxfiles/upd_documents.php?type=passport&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>",
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        dragDrop: false,
        deleteStr: '<a><i class = "fa fa-trash"></i></a>',
        showDownload: false,
        statusBarWidth: '100%',
        dragdropWidth: '100%',
        maxFileCount: 4,
        acceptFiles: "image/*",
        onLoad: function(obj) {
          $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/load_documents.php?type=passport&tp=<?php echo $id_trip ?>",
            dataType: "json",
            success: function(data) {
              for (var i = 0; i < data.length; i++) {
                obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
              }
            }
          });
        },
        deleteCallback: function(data, pd) {
          for (var i = 0; i < data.length; i++) {
            $.post(SITE + "ajaxfiles/del_documents.php", {
                op: "delete",
                name: data[i]
              },
              function(resp, textStatus, jqXHR) {
                //Show Message 
                //alert("File Deleted"+resp+textStatus);
              });
          }
          pd.statusbar.hide(); //You choice.
        },
      });
      $("#fileuploader_driver").uploadFile({
        url: SITE + "ajaxfiles/upd_documents.php?type=driver&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>",
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        showDownload: false,
        dragDrop: false,
        deleteStr: '<a><i class = "fa fa-trash"></i></a>',
        statusBarWidth: 600,
        dragdropWidth: 600,
        maxFileCount: 4,
        acceptFiles: "image/*",
        onLoad: function(obj) {
          $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/load_documents.php?type=driver&tp=<?php echo $id_trip ?>",
            dataType: "json",
            success: function(data) {
              for (var i = 0; i < data.length; i++) {
                obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
              }
            }
          });
        },
        deleteCallback: function(data, pd) {
          for (var i = 0; i < data.length; i++) {
            $.post(SITE + "ajaxfiles/del_documents.php", {
                op: "delete",
                name: data[i]
              },
              function(resp, textStatus, jqXHR) {
                //Show Message 
                //alert("File Deleted");
              });
          }
          pd.statusbar.hide(); //You choice.
        },
      });
      $("#fileuploader_hotel").uploadFile({
        url: SITE + "ajaxfiles/upd_documents.php?type=hitinerary&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>",
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        showDownload: false,
        dragDrop: false,
        deleteStr: '<a><i class = "fa fa-trash"></i></a>',
        statusBarWidth: 600,
        dragdropWidth: 600,
        maxFileCount: 4,
        acceptFiles: "image/*",
        onLoad: function(obj) {
          $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/load_documents.php?type=hitinerary&tp=<?php echo $id_trip ?>",
            dataType: "json",
            success: function(data) {
              for (var i = 0; i < data.length; i++) {
                obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
              }
            }
          });
        },
        deleteCallback: function(data, pd) {
          for (var i = 0; i < data.length; i++) {
            $.post(SITE + "ajaxfiles/del_documents.php", {
                op: "delete",
                name: data[i]
              },
              function(resp, textStatus, jqXHR) {
                //Show Message 
                //alert("File Deleted");
              });
          }
          pd.statusbar.hide(); //You choice.
        },
      });
      $("#fileuploader_flight").uploadFile({
        url: SITE + "ajaxfiles/upd_documents.php?type=fitinerary&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>",
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        dragDrop: false,
        deleteStr: '<a><i class = "fa fa-trash"></i></a>',
        showDownload: false,
        statusBarWidth: 600,
        dragdropWidth: 600,
        maxFileCount: 4,
        acceptFiles: "image/*",
        onLoad: function(obj) {
          $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/load_documents.php?type=fitinerary&tp=<?php echo $id_trip ?>",
            dataType: "json",
            success: function(data) {
              for (var i = 0; i < data.length; i++) {
                obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
              }
            }
          });
        },
        deleteCallback: function(data, pd) {
          for (var i = 0; i < data.length; i++) {
            $.post(SITE + "ajaxfiles/del_documents.php", {
                op: "delete",
                name: data[i]
              },
              function(resp, textStatus, jqXHR) {
                //Show Message 
                //alert("File Deleted");
              });
          }
          pd.statusbar.hide(); //You choice.
        },
      });

      $("#fileuploader_additional").uploadFile({
        url: SITE + "ajaxfiles/upd_documents.php?type=additional&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>",
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        showDownload: false,
        dragDrop: false,
        deleteStr: '<a><i class = "fa fa-trash"></i></a>',
        statusBarWidth: 600,
        dragdropWidth: 600,
        maxFileCount: 4,
        acceptFiles: ".pdf,.jpg,.png",
        onLoad: function(obj) {
          $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/load_documents.php?type=additional&tp=<?php echo $id_trip ?>",
            dataType: "json",
            success: function(data) {
              for (var i = 0; i < data.length; i++) {
                obj.createProgress(data[i]["name"], data[i]["path"], data[i]["size"]);
              }
            }
          });
        },
        deleteCallback: function(data, pd) {
          for (var i = 0; i < data.length; i++) {
            $.post(SITE + "ajaxfiles/del_documents.php", {
                op: "delete",
                name: data[i]
              },
              function(resp, textStatus, jqXHR) {
                //Show Message 
                //alert("File Deleted");
              });
          }
          pd.statusbar.hide(); //You choice.
        },
      });

      $('#documents_loadpassport').click(function(event) { // add a passport document to DB  
        $('#documents_loadpassport').hide('fast');
        $('#fileload_passport').html('');
        $.post(SITE + "ajaxfiles/saved_documents.php", {
            type: 'passport',
            tp: '<?php echo $id_trip ?>'
          },
          function(data) {
            if (data['error']) {
              $('#fileload_passport').html(data['error']);
              $('#fileload_passport').fadeIn(500);
              $('#documents_loadpassport').show('fast');
            } else {
              $('#fileload_passport').append(data['txt']);
              $('#fileload_passport').fadeIn(1000);
            }
          }, "json");
      });
      $('#documents_loaddriver').click(function(event) { // add a driver document to DB   
        $('#documents_loaddriver').hide('fast');
        $('#fileload_driver').html('');
        $.post(SITE + "ajaxfiles/saved_documents.php", {
            type: 'driver',
            tp: '<?php echo $id_trip ?>'
          },
          function(data) {
            if (data['error']) {
              $('#fileload_driver').html(data['error']);
              $('#fileload_driver').fadeIn(500);
              $('#documents_loaddriver').show('fast');
            } else {
              $('#fileload_driver').append(data['txt']);
              $('#fileload_driver').fadeIn(1000);
            }
          }, "json");
      });
      $('#documents_loadhitinerary').click(function(event) { // add a hotel itinerary document to DB   
        $('#documents_loadhitinerary').hide('fast');
        $('#fileload_hitinerary').html('');
        $.post(SITE + "ajaxfiles/saved_documents.php", {
            type: 'hitinerary',
            tp: '<?php echo $id_trip ?>'
          },
          function(data) {
            if (data['error']) {
              $('#fileload_hitinerary').html(data['error']);
              $('#fileload_hitinerary').fadeIn(500);
              $('#documents_loadhitinerary').show('fast');
            } else {
              $('#fileload_hitinerary').append(data['txt']);
              $('#fileload_hitinerary').fadeIn(1000);
            }
          }, "json");
      });
      $('#documents_loadfitinerary').click(function(event) { // add a flight itinerary document to DB   
        $('#documents_loadfitinerary').hide('fast');
        $('#fileload_fitinerary').html('');
        $.post(SITE + "ajaxfiles/saved_documents.php", {
            type: 'fitinerary',
            tp: '<?php echo $id_trip ?>'
          },
          function(data) {
            if (data['error']) {
              $('#fileload_fitinerary').html(data['error']);
              $('#fileload_fitinerary').fadeIn(500);
              $('#documents_loadfitinerary').show('fast');
            } else {
              $('#fileload_fitinerary').append(data['txt']);
              $('#fileload_fitinerary').fadeIn(1000);
            }
          }, "json");
      });

      $('#documents_additional').click(function(event) { // add a flight itinerary document to DB   
        $('#documents_additional').hide('fast');
        $('#fileload_additional').html('');
        $.post(SITE + "ajaxfiles/saved_documents.php", {
            type: 'additional',
            tp: '<?php echo $id_trip ?>'
          },
          function(data) {
            if (data['error']) {
              $('#fileload_additional').html(data['error']);
              $('#fileload_additional').fadeIn(500);
              $('#documents_additional').show('fast');
            } else {
              $('#fileload_additional').append(data['txt']);
              $('#fileload_additional').fadeIn(1000);
            }
          }, "json");
      });

    });
  </script>


  <!--</div> -->

  <!--<input name="documents_save" id="documents_save" type="button" class="button bt_blue" value="SAVE DOCUMENTS">-->
  <!--<br clear="all" /><input name="documents_submit" id="documents_submit" type="submit" class="button" value="SAVE AND NEXT">-->
  <!--<input name="" type="button" class="button bt_grey" value="REVIEW ATTACHMENTS">-->
  <!--</div>-->
  <?PHP
  /*        $stmt = $dbh->prepare("SELECT * FROM config WHERE setting='Why_Add_Your_Documents'");                  
                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount()>0)
                           { $text1 = $stmt->fetchAll(PDO::FETCH_OBJ);
                             $text1 = $text1[0]->value;
                           }*/
  ?>
  <!--                 <div class="cont_blue_r">-->
  <!--                      <h3>Why Add Your Documents</h3>-->
  <!--                      <p><? PHP // echo $text1; 
                                ?></p>-->
  <!--                 </div>-->
  <!--                 <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>" class="skip_notes">Skip This Section</a>-->
  <!--            </div>-->
  <!--            </div>-->
  <!--            </form>-->
  <!--          </div>-->
  <!--        </div>-->
  <!--    </div>-->
  <!--</div>    -->

  <br clear="all" />
  <div id="map"></div>

  <?PHP
  $scale = 'METRIC';
  if ($userdata['scale'] == 'imperial') {
    $scale = 'IMPERIAL';
  }
  if (!empty($trip->trip_location_from_latlng_flightportion)) {
    $tmp = str_replace('(', '', $trip->trip_location_from_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_from_flightportion = $tmp[0];
    $lng_from_flightportion = $tmp[1];
    $tmp = str_replace('(', '', $trip->trip_location_to_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_to_flightportion = $tmp[0];
    $lng_to_flightportion = $tmp[1];
  }
  ?>
  <script>
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
      <?php if ($trip->trip_transport == 'plane') {
        $lat_from_plane = $lat_from;
        $lng_from_plane = $lng_from;
        $lat_to_plane   = $lat_to;
        $lng_to_plane   = $lng_to;
      ?>
        new DrawPlaneDirectionsHandler(map, "A", "B");
      <?php } ?>

      <?php if ($trip->trip_location_from_flightportion) {
        $lat_from_plane = $lat_from_flightportion;
        $lng_from_plane = $lng_from_flightportion;
        $lat_to_plane   = $lat_to_flightportion;
        $lng_to_plane   = $lng_to_flightportion;
      ?>
        new DrawPlaneDirectionsHandler(map, "", "C");
      <?php } ?>

      <?php if ($trip->trip_transport == 'vehicle') {
        $vehicle_location_from = 'location_from';
        $vehicle_location_to = 'location_to';
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from = $tmp[0];
        $lng_from = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to = $tmp[0];
        $lng_to = $tmp[1];
      ?>
        new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "A", "B");
      <?php } ?>

      <?php if ($trip->trip_location_from_drivingportion) {
        $vehicle_location_from = 'trip_location_from_drivingportion';
        $vehicle_location_to = 'trip_location_to_drivingportion';
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_fromd = $tmp[0];
        $lng_fromd = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_tod = $tmp[0];
        $lng_tod = $tmp[1];

        if ($trip->trip_transport == 'train')
          $end_marker = "D";
        else
          $end_marker = "C";
      ?>

        new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_fromd; ?>, <?PHP echo $lng_fromd; ?>, <?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>, "", "<?PHP echo $end_marker; ?>");
      <?php } ?>

      <?php if ($trip->trip_transport == 'train') {
        $train_location_from = 'location_from';
        $train_location_to = 'location_to';
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from = $tmp[0];
        $lng_from = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to = $tmp[0];
        $lng_to = $tmp[1];
      ?>
        new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "A", "B");
      <?php } ?>
      <?php if ($trip->trip_location_from_trainportion) {
        $train_location_from = 'trip_location_from_trainportion';
        $train_location_to = 'trip_location_to_trainportion';
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from = $tmp[0];
        $lng_from = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to = $tmp[0];
        $lng_to = $tmp[1];
      ?>
        new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "", "D");
      <?php } ?>

    }

    /************ Routes ******************/
    <?php if ($trip->trip_transport == 'plane' || $trip->trip_location_from_flightportion) { ?>

      function DrawPlaneDirectionsHandler(map, marker_a, marker_b) {
        var bounds = new google.maps.LatLngBounds();
        var marker_a = marker_a;
        var marker_b = marker_b;
        var marker_origin = new google.maps.Marker({
          position: new google.maps.LatLng(<?php echo $lat_from_plane; ?>, <?php echo $lng_from_plane; ?>),
          label: {
            text: marker_a,
            color: "#000000",
          },
        });
        var marker_destination = new google.maps.Marker({
          position: new google.maps.LatLng(<?php echo $lat_to_plane; ?>, <?php echo $lng_to_plane; ?>),
          label: {
            text: marker_b,
            color: "#000000",
          },
        });
        if (marker_a)
          marker_origin.setMap(map);
        if (marker_b)
          marker_destination.setMap(map);

        bounds.extend(marker_origin.position);
        bounds.extend(marker_destination.position);

        var flightPlanCoordinates = [{
            lat: <?php echo $lat_from_plane; ?>,
            lng: <?php echo $lng_from_plane; ?>
          },
          {
            lat: <?php echo $lat_to_plane; ?>,
            lng: <?php echo $lng_to_plane; ?>
          }
        ];

        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#F08A0D',
          strokeOpacity: 1.0,
          strokeWeight: 3
        });

        flightPath.setMap(map);
        map.fitBounds(bounds);
      }
    <?PHP } ?>

    function AutocompleteDirectionsHandler(map, transport, lat_from, lng_from, lat_to, lng_to, marker_a, marker_b) {
      var directionsService = new google.maps.DirectionsService();
      var directionsDisplay = new google.maps.DirectionsRenderer({
        polylineOptions: {
          strokeColor: "#F08A0D"
        }
      });
      var directionsDisplay1 = new google.maps.DirectionsRenderer({
        polylineOptions: {
          strokeColor: "#F08A0D"
        }
      });
      var transport = transport;
      var lat_from = lat_from;
      var lng_from = lng_from;
      var lat_to = lat_to;
      var lng_to = lng_to;
      var marker_a = marker_a;
      var marker_b = marker_b;


      var bounds = new google.maps.LatLngBounds();
      var marker_origin = new google.maps.Marker({
        position: new google.maps.LatLng(lat_from, lng_from),
        label: {
          text: marker_a,
          color: "#000000",
        },
      });
      var marker_destination = new google.maps.Marker({
        position: new google.maps.LatLng(lat_to, lng_to),
        label: {
          text: marker_b,
          color: "#000000",
        },
      });
      if (marker_a)
        marker_origin.setMap(map);
      if (marker_b)
        marker_destination.setMap(map);

      bounds.extend(marker_origin.position);
      bounds.extend(marker_destination.position);
      map.fitBounds(bounds);

      directionsDisplay.setMap(map);
      directionsDisplay1.setMap(map);
      directionsDisplay.setPanel(document.getElementById('panel'));
      if (transport == 'driving') {
        //alert('vehicle from: <?PHP echo $vehicle_location_from; ?>');
        var request = {
          origin: document.getElementById('<?PHP echo $vehicle_location_from; ?>').value,
          destination: document.getElementById('<?PHP echo $vehicle_location_to; ?>').value,
          travelMode: google.maps.DirectionsTravelMode.DRIVING,
          unitSystem: google.maps.UnitSystem.<?PHP echo $scale; ?>
        };
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            var line = new google.maps.Polyline({
              path: response.routes[0].overview_path,
              //geodesic: true,
              strokeColor: '#F08A0D',
              strokeOpacity: 1.0,
              strokeWeight: 3
            });
            line.setMap(map);
            //alert(response.routes[0].overview_path.length);  
            //directionsDisplay.setDirections(response);    
            /*var center_point = response.routes[0].overview_path.length/2;
            var infowindow = new google.maps.InfoWindow();
            infowindow.setContent("<div style='float:left; padding: 3px;'><img width='40' src='<?php echo SITE; ?>images/car_icon.png'></div><div style='float:right; padding: 3px;'>"+response.routes[0].legs[0].distance.text + "<br>" + response.routes[0].legs[0].duration.text + "</div>");
            infowindow.setPosition(response.routes[0].overview_path[center_point|0]);
            infowindow.open(map); */


          }
        });
      }
      if (transport == 'train') {
        //alert('train from: <?PHP echo $train_location_from; ?>');
        var request = {
          origin: document.getElementById('<?PHP echo $train_location_from; ?>').value,
          destination: document.getElementById('<?PHP echo $train_location_to; ?>').value,
          travelMode: google.maps.DirectionsTravelMode.TRANSIT,
          transitOptions: {
            modes: [google.maps.TransitMode.TRAIN]
          },
          unitSystem: google.maps.UnitSystem.<?PHP echo $scale; ?>

        };
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            var line2 = new google.maps.Polyline({
              path: response.routes[0].overview_path,
              //geodesic: true,
              strokeColor: '#F08A0D',
              strokeOpacity: 1.0,
              strokeWeight: 3
            });
            line2.setMap(map);
            /* var center_point2 = response.routes[0].overview_path.length/2;
             var infowindow2 = new google.maps.InfoWindow();
            infowindow2.setContent("<div style='float:left; padding: 3px;'><img width='40' src='<?php echo SITE; ?>images/train_icon2.png'></div><div style='float:right; padding: 3px;'>"+response.routes[0].legs[0].distance.text + "<br>" + response.routes[0].legs[0].duration.text + "</div>");
            infowindow2.setPosition(response.routes[0].overview_path[center_point2|0]);
            infowindow2.open(map); */

            //directionsDisplay1.setDirections(response);
          }
        });
      }

    }
  </script>
  <script>
    $(window).on('load', function() {
      $('#document-modal').modal('show');
    });
    $(".close").click(function() {
      if ($("#document-modal").hasClass('modaltrans')) {
        $("#document-modal").removeClass('modaltrans');
        $(this).html("×");
      } else {
        $("#document-modal").addClass('modaltrans');
        $(this).html("-");
      }
    });
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuanTgo4XIpBcYXIlImpkpR-npUAiLRfo&libraries=places&callback=initMap" async defer></script>

  <?php include('new_backend_footer.php'); ?>

</body>

</html>