<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */

include_once("config.ini.php");
include_once("class/class.UpdateStatus.php");
$updateStatus = new UpdateStatus();
if (!$auth->isLogged()) {
    header("Location:" . SITE . "login");
    die();
}

if (!$updateStatus->hasAccessToTrip($_GET['id'], $userdata['id'])) {
    header("Location:" . SITE . "login");
    die();
}

include_once('class/class.TripPlan.php');
$trip = new TripPlan();
$people = $trip->getPeopleRelatedToTrip($_GET['id']);

if (!is_array($people) || count($people) < 2) {
    die();
}

ob_start();
?>

    <script type="text/javascript">

        $(document).ready(function () {


            $(".update-status-btn").on("click", function () {
                $(".attend-people-error").hide();
                if ($("#attend-radio-selected").prop("checked") == true && $('.attend-checkbox:checked').length == 0) {
                    $(".attend-people-error").show();
                    return;
                }
                var $button = $(this);
                $button.prop("disabled", true);

                var formData = $("#update_status").serialize();

                $.ajax({
                    url: "<?=SITE;?>/update_status_send.php",
                    method: "POST",
                    data: formData,
                    format: "json",
                    success: function (response) {
                        toggleAlertForm();
                        toastr.success("Status has been sent.");

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Request failed:", textStatus, errorThrown);
                    },
                    complete: function () {
                        $button.prop("disabled", false);
                        checkinUpdateInfoProcess($("#attende-trip-id").val());
                    }
                });
            });

            $("#update_status").find("[name=for]").on("change", function () {
                if ($(this).val() == "all") {
                    $(".attend-badge-div").show();
                    $(".attend-checkbox").prop("checked", true);
                } else {
                    $(".attend-badge-div").hide();
                    $(".attend-checkbox").prop("checked", false);
                }
            });
        });
    </script>

    <form id="update_status">
        <input type="hidden" name="trip_id" id="attende-trip-id" value="<?= $_GET['id']; ?>">
        <div class="row">
            <div class="col-12 itinerary_section" style="text-align: left">
                <div class="form-check alert-form" style="display:none;margin-bottom:10px">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="for" id="for1" value="all" checked>
                        <label class="form-check-label" for="for1">
                            Everyone
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="for" id="attend-radio-selected" id="for2"
                               value="selected">
                        <label class="form-check-label" for="for2">
                            Specific people
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <div style="color:#c3c3c3;font-size:12px">(click on the attendee’s photo)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="update-status-people-list">
            <div class="row">
                <div class="alert alert-danger attend-people-error" style="display:none">
                    You need to select at least one person, or choose to send to everyone.
                </div>
            </div>
            <div class="row">

                <?php
                foreach ($people as $person) {
                    ?>
                    <div class="col-4">
                        <div class="people_left_side">
                            <div class="people_img">
                                <div class="avatar-icon-wrapper update-status-person">
                                    <div class="badge badge-bottom badge-success badge-dot badge-dot-lg attend-badge-div" style="display:none"></div>
                                    <div class="avatar-icon" style="width:58px;height:58px;">
                                        <img src="<?= $person->picture ? (SITE . 'ajaxfiles/profile/' . $person->picture) : (SITE . '/images/my_profile_icon.png'); ?>"
                                             alt="<?= $person->name; ?>" class="rounded-circle img-fluid">
                                    </div>

                                    <input type="checkbox" name="people[]" value="<?= $person->id; ?>"
                                           class="checkbox attend-checkbox"
                                           style="display:none" checked="checked">
                                </div>
                            </div>
                            <div class="people_info">
                                <h4><?= $person->name; ?> <?php
                                if ($person->role == TripPlan::ROLE_COLLABORATOR) {
                                    echo '<span style="color:#b4b4b4;font-size:14px">(Collaborator)</span>';
                                }
                                    ?></h4>
                                <p><?= $person->email; ?></p>
                            </div>
                        </div>
                    </div>


                <?php } ?>

            </div>
        </div>
        <div class="alert-form">
            <div class="row">
                <div class="col-12" style="text-align: left">
                        <input style="padding:6px;" autofocus="" name="update_status_text" id="update_status_text"
                                  maxlength="500" cols="" class="update-status-textarea input-lg"
                                  placeholder="Enter text">
                    <button type="button" class="btn btn-sm btn-primary update-status-btn"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-sm btn-default" style="background-color: #0c246b;color:#fff"
                            onclick="javascript:toggleAlertForm();"><i class="fa fa-ban" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

    </form>

<?php
$data = ob_get_contents();
ob_end_clean();
$notFound = <<<NOTFOUND
<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no attendess to display.</h3>
NOTFOUND;

if (count($people) > 0) {
    echo $data;
} else {
    echo $notFound;
}