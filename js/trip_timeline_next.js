let calendar = null;
let item_no = null;
let event_entry = {};
var idtrip = $("#timeline_idtrip").val();
var dataSet = "id_trip=" + idtrip;
const stepper = new ModalStepper('#advanced_popup')
const editStepper = new ModalStepper('#edit_timeline_popup')
stepper.init()
editStepper.init()
$("button.btn.timeline-action-btn").on("click", function (e) {
  console.log("e", e);
  let mode = $(this).data("mode");
  classToggle(this);
  if (mode == "calender") {
    $("#calendar").show();
    $("h4.calendar-title").html("Your Calendar");
    $("#preview-section").hide();
  } else {
    $("#preview-section").show();
    $("h4.calendar-title").html("Your Events");
    $("#calendar").hide();
  }
});
const classToggle = (e) => {
  $("button.btn.timeline-action-btn").removeClass("active");
  $(e).addClass("active");
  console.log("thisValue", e);
};
$(function () {
  getTimelineList();
  getTimelinePreviewList();
  fullCalenderLoad();
  initTripDocumentsLoad();
});
function initTripDocumentsLoad() {
	const $file = $('.timeline_document'),
		$label = $file.next('label'),
		$labelText = $label.find('span'),
		labelDefault = 'Upload file'
	$file.on('change', function (event) {
    $file.attr('data-no-validate', 0)
		const fileName = $(this).val().split('\\').pop()
		if (fileName) {
			$labelText.text(fileName)
		} else {
			$labelText.text(labelDefault)
		}
	})
}
function class_calculation(index) {
  var remainder = (index + 1) % 3;
  return remainder;
}

function fullCalenderLoad() {
  calendar = $("#calendar").fullCalendar({
    //height: 650,
    //contentHeight: "auto",
    header: {
      left: "title prev,next",
      //left: "",
      right: "month,timeGridWeek",
    },
    themeSystem: "bootstrap4",
    bootstrapFontAwesome: !0,
    defaultView: "month",
    eventRender: function (eventObj, $el) {
      $el.popover({
        title: eventObj.title,
        content: eventObj.title,
        trigger: "hover",
        placement: "top",
        container: "body",
      });
    },
    events: function (start, end, timezone, callback) {
      // Make an AJAX request to fetch events from a server
      $.ajax({
        url: SITE + "ajaxfiles/timeline/get_timeline.php",
        data: dataSet,
        dataType: "json",
        success: function (response) {
          // Parse the response and format it as FullCalendar events
          var events = response.map(function (event, i) {
            let index = i + 1;
            return {
              id: event.id_timeline,
              title: event.title,
              start: moment(event.date).format(),
              allDay: true,
              secondTitle: moment(event.date).format("MMMM D, YYYY h:mm a"),
              color: "#000",
              className: "tag_" + class_calculation(index),
            };
          });

          // Call the callback function with the parsed events
          callback(events);
          $(document).trigger("eventsLoaded");
        },
        error: function () {
          // Call the callback function with an error message
          callback("There was an error fetching events.");
        },
      });
    },
    eventRender: function (event, element) {
      element.find(".fc-title").html(""); // Replace the event title with a custom format
    //   element.append(
    //     '<div class="second-title">' + event.secondTitle + "</div>"
    //   ); 
      // Add the second title to the event element
      element.popover({
        title: "Event",
        content: event.title,
        trigger: "hover",
        placement: "top",
        container: "body",
      });
    },
    css: {
      // Customize FullCalendar with your own CSS here
      background: "#FFFFFF", // Set the background color of the calendar
      textColor: "#000000", // Set the text color of the calendar
      // eventColor: '#d1d1d1', // Set the background color of events
      // eventTextColor: '#000000', // Set the text color of events
      // Add more custom styles here as needed
    },
  });
}

$(".datepicker").datepicker({
  format: "yyyy-mm-dd",
  autoclose: true,
});

function updateCalendarHeight() {
  var calendarHeight = 500; // Set the fixed height here
  $("#calendar").fullCalendar("option", "height", calendarHeight);
}

$(document).on("eventsLoaded", function () {
  var calendarHeight = 500;
  $("#calendar").fullCalendar("option", "height", calendarHeight);  
});

$(window).on("resize", updateCalendarHeight);

function getTimelineList() {
  var items = "";
  $.getJSON(
    SITE + "ajaxfiles/timeline/get_timeline.php",
    { id_trip: idtrip },
    function (data) {
      $.each(data, function (index, item) {
        console.log(item);
        let is_checked = "";
        let schedule_checkin = "";
        
				let checkin_icon = ''
				let docs_icon = ''
				let note_icon = ''
        if (item.checked_in == "1") {
          schedule_checkin = "checked disabled";
        }
        
				if (item.document) {
					docs_icon = `<span class="schedule_linked"><i class="fa fa-file-o" aria-hidden="true"></i></span>`
				}
				if (item.note) {
					note_icon = `<span class="schedule_linked"><i class="fa fa-comment-o" aria-hidden="true"></i></span>`
				}
				if (item.is_checked == '1') {
					checkin_icon = `<span class="schedule_linked"><i class="fa fa-check-square-o" aria-hidden="true" title="Check-in Required"></i></span>`
				}
                var address = '';
                if (item.plan_address != "" && item.plan_address != null) {
                  console.log(item.plan_address);
                  address = item.plan_address;
                } else {
                  address = '';
                }
        items += `<div class="event_item" id="event_${
          item.id_timeline
        }"  data-id="${item.id_timeline}">
        <div class="event_header">
        <h4>${checkin_icon} ${docs_icon} ${note_icon}  ${item.title} </h4>
        <div class="event_action">
        <button id="edit" type="button" class="btn btn-mini btn-info event_edit_button" title="Edit Schedule" value="${
          item.id_timeline
        }" aria-invalid="false"><i class="fa fa-pencil"></i></button>
          <button id="delete" type="button" class="btn btn-mini btn-danger event_action_button" title="Delete Schedule" value="${
            item.id_timeline
          }"
           aria-invalid="false" onclick="remove_schedule(${item.id_timeline},${
          item.plan_linked
        })"><i class="fa fa-trash"></i> </button> 
          </div>
          </div>
          <div class="event_body">
          <div class="event_date"><p>${moment(item.date).format(
            "MMMM D, YYYY h:mm a"
          )}</p></div>
          </div>
          <div class="event_body"><div class="event_date"><p>${address}</p></div></div>
          </div>`;
      });
      item_no = data.length;
      if (data == null || data.length === 0) {
        items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no scheduled events to display yet.</h3>`;
      }
      $(".event_list").html(items);
    }
  );
}

function getTimelinePreviewList() {
  $(".loading_section").show();
  var items = "";
  $.getJSON(
    SITE + "ajaxfiles/timeline/get_timeline_preview.php",
    { id_trip: idtrip },
    function (data) {
      $(".loading_section").hide();
      $.each(data, function (index, item) {
        items += `<div class="preview-group">
        <h3 class="group-heading"><i class="fa fa-info-circle" aria-hidden="true"></i>
        ${item.date_top}</h3>
        `;
        $.each(item.list, function (innerIndex, listItem) {
          let address = "(No address is linked)";
          if (listItem.address) {
            address = listItem.address;
          }

          items += `<div class="preview-single-box">
          <h3 class="box-title"><i class="fa fa-check-circle" aria-hidden="true"></i> ${listItem.title}</h3>
          <p><i class="fa fa-map-marker" aria-hidden="true"></i> ${address}</p>
          <div class="box-timestamp">
              <p>
             
              <i class="fa fa-calendar-o" aria-hidden="true"></i>
                ${listItem.date} 
                
              <span>
              <i class="fa fa-clock-o" aria-hidden="true"></i>
              ${listItem.time}
              </span>
              </p>
          </div>
        </div>`;
        });
        items += `</div>`;
      });

      if (data == null || data.length === 0) {
        items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no scheduled events to display yet.</h3>`;
      }
      $("#preview-section").html(items);
    }
  );
}

function calenderRender(event_entry, event, id = null) {
  if (id) {
    event_entry.id = id;
  }
  console.log("event_entry", event_entry);
  console.log("event_type", event);
  $("#calendar").fullCalendar(event, event_entry);
  var calendarHeight = 500; // Set the fixed height here
  $("#calendar").fullCalendar("option", "height", calendarHeight);  
}
function calendarUpdate(event_entry) {
  var cal = $("#calendar").fullCalendar("getCalendar");
  console.log("event_entry", event_entry);
  console.log("cal", cal);
  var event = cal.clientEvents(function (event) {
    return event.id == event_entry.id;
  })[0];
  if (!event) return;
  //var event = calendar.getEventById(event_entry.id);
  //var event = calendar.getEventById(event_entry.id);
  event.title = event_entry.title;
  event.start = event_entry.start;
  event.secondTitle = event_entry.secondTitle;
  cal.updateEvent(event);
  var calendarHeight = 500; // Set the fixed height here
  $("#calendar").fullCalendar("option", "height", calendarHeight);  
}
$("#timeline_form").validate({
  rules: {
    event_name: {
      required: true,
    },
    event_type: {
      required: true,
    },
    event_date: {
      required: true,
    },
    event_time: {
      required: true,
    },
  },
  messages: {
    event_name: {
      required: "Please type event title",
    },
    event_type: {
      required: "Please select event type",
    },
    event_date: {
      required: "Please select date",
    },
    event_time: {
      required: "Please select time",
    },
  },
  submitHandler: function (form) {
    var advance_check = $("#advance_check").val();
    if (advance_check == 1) {
			stepper.reset()
      $("#advanced_popup").modal("show")
    } else {
      
    $(".event-process-btn").css("cursor", "wait");
    $(".event-process-btn").attr("disabled", true);

    let title = $("#event_name").val();
    let e_date = $("#event_date").val() + " " + $("#event_time").val();
    let e_time = $("#event_time").val();
    let event_type = $("#event_type").val();
    item_no = item_no + 1;
    event_entry = {
      id: null,
      title: title,
      start: moment(e_date).format(),
      allDay: true,
      secondTitle: moment(e_date).format("MMMM D, YYYY h:mm a"),
      color: "#000",
      className: "tag_" + class_calculation(item_no),
    };
    try {
      const form = document.getElementById('timeline_form')
      let formData = new FormData(form)
      let docFile = '';
      if ($('#trip_additional_docs_yes').is(':checked')) {
        docFile = $('#trip_additional_docs_file').prop('files')[0] ?? ''
      }
      formData.append('trip_document', docFile)
      $.ajax({
        url: SITE + 'ajaxfiles/timeline/add_timeline.php',
        type: 'POST',
        data: formData,
        contentType: false,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        success: function (response) {
          $('#timeline_form').trigger('reset')
          toastr.success('Successfully Schedule Added')
          getTimelineList()
          getTimelinePreviewList()
          calenderRender(event_entry, 'renderEvent', response.id)
          $('#plan_linked').val(0)
          $('#advance_check').val(1)
          $('#advanced_form').trigger('reset')
          $('#advance_note').val('')
          $('#trip_additional_docs_file').val('')
          $('.event-process-btn').css('cursor', 'pointer')
          $('.event-process-btn').removeAttr('disabled')
        },
        error: function (jqXHR, textStatus, errorThrown) {
          toastr.error(
            'A system error has been encountered. Please try again'
          )
          $('.event-process-btn').css('cursor', 'pointer')
          $('.event-process-btn').removeAttr('disabled')
        },
      })
    } catch (e) {
      console.log(e)
    }
    }
  }, // Do not change code below
  //   errorPlacement: function (error, element) {
  //     error.insertAfter(element.parent());
  //   },
});

$("#timeline_form_update").validate({
  ignore: ":hidden:not(.validy)",
  rules: {
    timeline_name: {
      required: true,
    },
    timeline_time: {
      required: true,
    },
    timeline_date: {
      required: true,
    },
  },
  messages: {
    timeline_name: {
      required: "Please type event title",
    },
    timeline_time: {
      required: "Please select time",
    },
    timeline_date: {
      required: "Please select date",
    },
  },

  submitHandler: function (form) {
    $(".update_submit_button").css("cursor", "wait");
    $(".update_submit_button").attr("disabled", true);

    let e_id = $("#item_id").val();
    let title = $("#e_timeline_name").val();
    let e_date =
      $("#e_timeline_date").val() + " " + $("#e_timeline_time").val();
    let e_time = $("#e_timeline_time").val();
    
    var trip_generated = $("#trip_generated").val();
    var trip_u_id = $("#trip_u_id").val();
    var trip_title = $("#trip_title").val();    

    event_entry = {
      id: e_id,
      title: title,
      start: moment(e_date).format(),
      allDay: true,
      secondTitle: moment(e_date).format("MMMM D, YYYY h:mm a"),
      color: "#000",
      className: "_tag",
    };
    try {
      const formComponent = document.getElementById('timeline_form_update')
      let formData = new FormData(formComponent)
      
      let checkin = $('#edit_timeline_checkin_yes').is(':checked');
      let note = $('#edit_timeline_note_text').val();
      let documents = $('#edit_timeline_document_file').prop('files');
      let timelineDocument = documents[0] ?? null;
      
      if ($('#edit_timeline_document_yes').is(':checked')) {
        formData.append('timeline_document', timelineDocument)
      } 
      if ($('#edit_timeline_note_yes').is(':checked')) {
        formData.append('timeline_note', note)
      }
      formData.append('timeline_checkin', checkin)
      formData.append('trip_generated', trip_generated)
      formData.append('trip_u_id', trip_u_id)
      formData.append('trip_title', trip_title)
      for (const [key, value] of formData) {
        console.log('»', key, value)
      }
      $.ajax({
        url: SITE + "ajaxfiles/timeline/update_timeline.php",
        type: "POST",
        data: formData,
        contentType: false,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        success: function (response) {
          editStepper.reset()
          $("#timeline_form_update").trigger("reset");
          toastr.success("Successfully Schedule Updated");
          getTimelineList();
          getTimelinePreviewList();
          calendarUpdate(event_entry, "updateEvent");
          $(".update_submit_button").css("cursor", "pointer");
          $(".update_submit_button").removeAttr("disabled");
          $("#update_schedule").modal("hide");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          toastr.error("A system error has been encountered. Please try again");
          $(".update_submit_button").css("cursor", "pointer");
          $(".update_submit_button").removeAttr("disabled");
          $("#update_schedule").modal("hide");
        },
      });
    } catch (error) {
      console.log(error);
    }

  }, // Do not change code below
  errorPlacement: function (error, element) {
    error.insertAfter(element.parent());
  },
});

function remove_schedule(id, plan_linked) {
  swal(
    {
      title: "Are you sure?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true,
    },
    function () {
      $.ajax({
        type: "POST",
        url: SITE + "ajaxfiles/timeline/delete_timeline.php",
        data: {
          id: id,
          plan_linked: plan_linked,
        },
        dataType: "json",
        success: function (response) {
          item_no = item_no - 1;
          toastr.success(response.message);
          $("#event_" + id).slideUp(150, function () {
            $("#event_" + id).remove();
          });
          getTimelinePreviewList();
          $("#calendar").fullCalendar("removeEvents", id);
    
          var calendarHeight = 500; // Set the fixed height here
          $("#calendar").fullCalendar("option", "height", calendarHeight);          
        },
        error: function (jqXHR, textStatus, errorThrown) {
          toastr.error(jqXHR.responseJSON);
        },
      });
    }
  );
};

$(document).on("click", "button#edit", function (event) {
  var data_id = $(this).val();
  $("#update_schedule").modal("show");
  var dataSet = "id=" + data_id;
  $.ajax({
    url: SITE + "ajaxfiles/timeline/get_timeline_single.php",
    type: "GET",
    data: dataSet,
    dataType: "json",
    success: function (response) {
      if (response) {
        var date = response.date.substr(0, 10);
        var time = response.date.substr(11, 19);
        console.log("response.title", response.title);
        const date_formated = moment(response.date).format("MM/DD/YYYY");

        $("#e_timeline_name").val(response.title);
        $("#e_timeline_date").val(date_formated);
        $("#e_timeline_time").val(time);
        $("#item_id").val(response.id_timeline);
        console.log('resp', response);
        editStepper.prefill({
          '#edit_timeline_checkin_yes': !!Number(response.is_checked),
          '#edit_timeline_checkin_no': !Number(response.is_checked),
          '#edit_timeline_note_yes': !!response.note,
          '#edit_timeline_note_no': !response.note,
          '#edit_timeline_note_text': response.note ?? '',
          '#edit_timeline_document_yes': response.document,
          '#edit_timeline_document_no': !response.document,
          '#edit_timeline_document_file': response.document ?? '',
        });
        editStepper.setOnSubmit(() => {
          console.log($('#timeline_form_update').serialize());
        });
        $("#plan_linked_flag").val(response.plan_linked);
      }
    },
  });
});

function checkin_action_taken(event_id, event_title) {
  // Check if the checkbox is checked
  if (event_id && event_title) {
    var value = event_id;
    var title = event_title;

    var trip_generated = $("#trip_generated").val();
    var trip_u_id = $("#trip_u_id").val();
    var trip_title = $("#trip_title").val();
    //Send AJAX request
    $.ajax({
      url: SITE + "ajaxfiles/timeline/checkin_process.php",
      method: "POST", // or 'GET' based on your API
      data: {
        id: value,
        title: title,
        trip_generated: trip_generated,
        trip_u_id: trip_u_id,
        trip_title: trip_title,
      }, // Data to send to the server
      dataType: "json",
      success: function (response) {
        // Handle successful response from the server
        if (response) {
          $('.schedule-checkin[value="' + value + '"]').prop("checked", true);
          $('.schedule-checkin[value="' + value + '"]').prop("disabled", true);
          toastr.success(response.message);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Handle error condition if AJAX request fails
        toastr.error(jqXHR.responseJSON);
      },
    });
  }
}

$("#advanced_form").validate({
  // rules: {
  //   option_requirement: {
  //     required: true,
  //   },
  // },
  // messages: {
  //   option_requirement: {
  //     required: "Please select option",
  //   },
  // },

  submitHandler: function (form) {
		$('#advance_check').val(0)
    if ($('#trip_additional_note_yes').is(':checked')) {
      const noteInput = $('#trip_additional_note_text').val()
		  $('#advance_note').val(noteInput)
    }
		$('#advanced_popup').modal('hide')
		$('#save_event').trigger('click')
  },
});

$('.edit-timeline-button').on('click', event => {
  event.preventDefault();
  editStepper.moveToStep(1);
  editStepper.open();
})
$('.close_edit_timeline').on('click', event => {
  editStepper.reset()
})

