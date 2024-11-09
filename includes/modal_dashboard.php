<div class="modal" id="upgrade" style="margin-top: 100px;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">UPGRADE PLAN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Upgrade your plan to access all benefits</p>
                <a href="<?= SITE ?>billing">
                    <button class="btn btn-info">UPGRADE NOW</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-blur" id="trip_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-trip-lg" role="document">
        <div class="modal-content trip-content">
            <div class="modal-header">
                <h4 class="modal-title modal_trip_name" id="myModalLabel"> - </h4>
                <button type="button" class="close trip_close" data-dismiss="modal">&times;</button>

            </div>

            <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 10000">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                    <input type="text" class="form-control" id="recipient-name">
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Message:</label>
                                    <textarea class="form-control" id="message-text"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Send message</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body text-center trip-body">

                <div class="row">

                    <div class="col-md-8 p-0 segment-item">

                        <div class="card trip_info">
                            <div class="trip_heading" style="border-bottom: 1px solid #c8ccd5;">
                                <div class="row">
                                    <div class="col-8" style="padding-left:0;magin-left:0">
                                        <h5 style="border:0">Attendees <span id="attendee_count"></span></h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="start_a_plan_btn" style="text-align: right; margin-top:10px;margin-right:10px;">

                                            <a href="javascript:void(0)" id="status-update-btn" style="background-color:#f00;color: #fff;border-radius: 5px; padding: 6px 10px; font-size: 12px;font-weight: 500;line-height: 5px; text-decoration: none; text-transform: capitalize; display: inline; text-align: center; margin-left: auto;">
                                                <span>Alert</span>
                                            </a>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div>
                                <div class="loading_screen" style="display: none;" id="attendee_loading">
                                    <div class="spinner-border text-primary"></div>
                                </div>
                                <div class="attendee_details" id="attendee_details">
                                    <!-- <div class="row">
                                        <div class="col-md-3">
                                            <div class="people_left_side">
                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>
                                                <div class="people_info">
                                                    <h4>David</h4>
                                                    <p>Customer Name</p>
                                                </div>
                                            </div>
                                        </div>
                                                                                
                                        <div class="col-md-3">
                                            <div class="people_left_side">
                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>
                                                <div class="people_info">
                                                    <h4>Robert</h4>
                                                    <p>Customer Name</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                            <div class="trip_heading top-border">
                                <h5 style="border-bottom:0">Updates</h5>
                            </div>
                            <div>
                                <div class="loading_screen" style="display: none;" id="update_loading">
                                    <div class="spinner-border text-primary"></div>
                                </div>

                                <div class="update_details" id="update_details">
                                    <div class="message_form" style="display:none"></div>
                                    <div class="statuses overflow-container"></div>
                                    <!-- <ul class="list-group striped-list">
                                        <li class="list-group-item update-item">

                                            <div class="people_left_side update">
                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>
                                                <div class="people_info">
                                                    <h4>David</h4>
                                                </div>
                                            </div>

                                            <div class="update-info">
                                                <h6> Checked-in to his flight at PHL</h6>
                                                <p><i class="fa fa-calendar-o" aria-hidden="true"></i> June 2nd, 2023 1:17pm</p>
                                            </div>
                                        </li>                                                                               
                                        <li class="list-group-item update-item">

                                            <div class="people_left_side update">
                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>
                                                <div class="people_info">
                                                    <h4>Jhon</h4>
                                                </div>
                                            </div>

                                            <div class="update-info">
                                                <h6>Checked-in to his flight at PHL</h6>
                                                <p><i class="fa fa-calendar-o" aria-hidden="true"></i> June 23rd, 2023 10:17am</p>
                                            </div>
                                        </li>
                                    </ul> -->
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4 segment-item">

                        <div class="card other_info" style="background: none">
                            <img style="height: -webkit-fill-available" src="<?= SITE; ?>/images/comming-2025.png" />
                        </div>

                        <div class="card other_info mt-3">
                            <div class="trip_heading document">
                                <h5 style="color:#4a6875;font-weight: normal">Your Private Plan Notes</h5>
                            </div>

                            <div class="comment_details">

                                <div class="loading_screen" style="display: none;" id="comment_loading">
                                    <div class="spinner-border text-primary"></div>
                                </div>

                                <div class="comment_list" id="comment_list">

                                    <!-- <ul class="list-group">

                                            <li class="list-group-item comment-item">
                                                <div class="comment-body">
                                                    <p>Team received updated copy of business agreement.</p>
                                                    <div class="comment-info">
                                                        <p>by <span>David</span> at <span>June 23rd, 2023 10:17am</span> </p>
                                                    </div>
                                                </div>
                                            </li>                                            

                                            <li class="list-group-item comment-item">
                                                <div class="comment-body">
                                                    <p>Team received updated copy of business agreement.</p>
                                                    <div class="comment-info">
                                                        <p>by <span>David</span> at <span>June 23rd, 2023 10:17am</span> </p>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul> -->

                                </div>

                                <div class="comment_entry">

                                    <form id="commentForm">
                                        <div class="comment_input">
                                            <div class="input-group comment-group" style="background: none; border:0">
                                                <input type="text" class="form-control comment-field" style="border-radius: 5px;border:solid 1px #dddddd; color: #999;font-size:14px" name="commentfield" id="commentfield" placeholder="Enter Note">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default comment-action" style="background-color: #f8bb4f;padding:.375rem .75rem;margin-left:9px" type="button" onclick="commentAction()"><i class="bi bi-send"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>

                                </div>



                            </div>
                        </div>
                    </div>

                </div>



            </div>

        </div>

    </div>
</div>