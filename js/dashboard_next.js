let trip_ref;
const fileInput = $("#fileInput");
const dropArea = document.getElementById("drop-area");

function getRandomColor() {
  return "#" + Math.floor(Math.random() * 16777215).toString(16);
}

function generateCustomAvatar(name) {
  const initialLetter = name.charAt(0).toUpperCase();
  //const randomBackgroundColor = getRandomColor();
  const randomBackgroundColor = "#18427f";

  // Create an SVG element
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
      <rect width="100" height="100" rx="50" ry="50" fill="${randomBackgroundColor}" />
      <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-size="50">${initialLetter}</text>
  </svg>`;

  // Set the SVG as the content of the avatar
  return "data:image/svg+xml," + encodeURIComponent(svg);
}

const profileImageProcess = (user) => {
  if (user.picture) {
    newImageUrl = SITE + "/ajaxfiles/profile/" + user.picture;
  } else {
    newImageUrl = generateCustomAvatar(user.name);
  }

  return newImageUrl;
};

$(".trip_expand").on("click", function () {
  trip_ref = $(this).attr("data-trip_ref");
  console.log("trip_ref", trip_ref);
  attendeesInfoProcess(trip_ref);
  checkinUpdateInfoProcess(trip_ref);
  documentInfoProcess(trip_ref);
  commentInfoProcess(trip_ref);
  updateStatusProcess(trip_ref)
  $("#trip_details").modal("show");
});

const tripInfoProcess = (trip_ref) => {
  $.ajax({
    url: SITE + "ajaxfiles/dashboard/get_trip_info.php",
    type: "GET",
    data: { id_trip: trip_ref },
    dataType: "json",
    success: function (response) {
      let trip_name = "";
      if (response.title == "" || response.title == null) {
        trip_name = "Incomplete Trip Plan";
      } else {
        trip_name = response.title;
      }

      $(".modal_trip_name").html(trip_name);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
    },
  });
};

const attendeesInfoProcess = (trip_ref) => {
  $("#attendee_loading").show();

  $.ajax({
    url: SITE + "root/dashboard/attendees",
    type: "GET",
    data: { id_trip: trip_ref },
    dataType: "json",
    success: function (response) {
      let trip_name = "";
      if (
        response.data.trip_info.title == "" ||
        response.data.trip_info.title == null
      ) {
        trip_name = "Incomplete Trip Plan";
      } else {
        trip_name = response.data.trip_info.title;
      }

      $(".modal_trip_name").html(trip_name);

      $("#attendee_count").html(`( ${response.data.user_count} )`);

      let items = `<div class="row">`;
      $.each(response.data.user_list, function (index, item) {
        let path_folder = "people";

        let photo = SITE + "assets/images/user_profile.png";

        if (item.photo_connect == "1") {
          path_folder = "profile";
        }

        if (item.photo) {
          photo = SITE + `ajaxfiles/${path_folder}/${item.photo}`;
        }

        items += `<div class="col-md-3">
        <div class="people_left_side">
            <div class="people_img"><img src="${photo}"></div>
            <div class="people_info text-black">
                <h4>${item.name}</h4>
                <p>${item.email}</p>
            </div>
        </div>
    </div>`;
      });

      items += `</div>`;

      if (response == null || response.length === 0 || response.data.user_count == 0) {
        items = "";
        // items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no attendess to display.</h3>`;
        items += `<h3 class="no-found"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> There are no attendess to display.</h3>`;
      }


      if (response.data.user_count == 1) {
        $("#status-update-btn").hide();
      } else {
        $("#status-update-btn").show();
      }
      $("#attendee_loading").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
      $("#attendee_loading").hide();
    },
  });
};

const checkinUpdateInfoProcess = (trip_ref) => {
  $("#update_loading").show();
  $.ajax({
    url: SITE + "root/dashboard/checked_in",
    type: "GET",
    data: { id_trip: trip_ref },
    dataType: "json",
    success: function (data) {
      let items = `<ul class="list-group" style="margin-left:0;padding-left:0px">`;

      $.each(data, function (index, item) {

        if (item.type == 'status_update') {
          items += ` <li class="list-group-item update-item" style="padding: .75rem 1.25rem;border:0">
        <div class="people_left_side update" style="width:auto">
            <div class="people_img"><img src="${profileImageProcess(
              item
          )}"></div>
            <div class="people_info text-black">
            <b>${item.name}</b> ${item.event_name} at ${item.checked_in_date}
                
            </div>
        </div>

    </li>`;
        } else {
          items += ` <li class="list-group-item update-item" style="border:0">
        <div class="people_left_side update">
            <div class="people_img"><img src="${profileImageProcess(
              item
          )}"></div>
            <div class="people_info text-black">
                <h4>${item.name}</h4>
            </div>
        </div>
        <div class="update-info">
            <h6> Checked-in ${item.event_name}</h6>
            <p><i class="fa fa-calendar-o" aria-hidden="true"></i> ${
              item.checked_in_date
          }</p>
        </div>
    </li>`;
        }
      });
      items += `</ul>`;

      if (data == null || data.length === 0) {
        items = "";
        // items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no updates to display.</h3>`;
        items += `<h3 class="no-found"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> There are no updates to display.</h3>`;
      }

      $("#update_details .statuses").html(items);

      $("#update_loading").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
      $("#update_loading").hide();
    },
  });


};

const updateStatusProcess = (trip_ref) => {
  $(".message_form").hide();
  $.ajax({
    url: SITE + "update_status.php?id=" + trip_ref,
    type: "GET",
    dataType: "html",
    success: function (data) {
      $("#attendee_details").html(data);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
    },
  });
};

const documentInfoProcess = (trip_ref) => {
  $("#document_loading").show();
  $.ajax({
    url: SITE + "root/dashboard/documents",
    type: "GET",
    data: { id_trip: trip_ref },
    dataType: "json",
    success: function (data) {
      let items = `<ul class="list-group striped-list">`;

      $.each(data, function (index, item) {
        items += `<li class="list-group-item document-item">
          <div class="document-body">
              <div class="document-info">
                  <p>
                      <i class="fa ${item.class}" aria-hidden="true"></i>
                      <div class="document-content">
                      <h4>${item.document_name}</h4>
                      <p>by <span>${item.user_name}</span> at <span>${item.checked_in_date}</span> </p>
                      <div>
                  </p>
              </div>
          </div>
      </li>`;
      });
      items += `</ul>`;

      if (data == null || data.length === 0) {
        items = "";
        // items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no documents to display.</h3>`;
        items += `<h3 class="no-found"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i> There are no documents to display.</h3>`;
      }

      $("#document_list").html(items);

      $("#document_loading").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
      $("#document_loading").hide();
    },
  });
};

const commentInfoProcess = (trip_ref) => {
  $("#comment_loading").show();
  $.ajax({
    url: SITE + "root/dashboard/comments",
    type: "GET",
    data: { id_trip: trip_ref },
    dataType: "json",
    success: function (response) {

      let data = response.data;
      let items = `<ul class="list-group">`;

      $.each(data, function (index, item) {
        items += `<li class="list-group-item comment-item">
          <div class="comment-body" style="border:0;background-color:#f3f3f3">
              <p>${item.comment}.</p>
          </div>
      </li>`;
      });
      items += `</ul>`;

      console.log("items", items);

      if (data == null || data.length === 0) {
        items = "";
        items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no comments to display.</h3>`;
      }

      $("#comment_list").html(items);

      $("#comment_loading").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
      $("#comment_loading").hide();
    },
  });
};

$("#commentForm").on("submit", function (event) {
  event.preventDefault(); // Prevent form submission

  var commentfield = $("#commentfield");

  commentfield.removeClass("error-comment");

  // Validate name field
  if ($.trim(commentfield.val()) === "") {
    commentfield.addClass("error-comment");
    return;
  }

  $(".comment-action").css("cursor", "wait");
  $(".comment-action").attr("disabled", true);

  $.ajax({
    url: SITE + "root/dashboard/comment_process",
    type: "POST",
    data: $("#commentForm").serialize() + "&id_trip=" + trip_ref,
    dataType: "json",
    success: function (response) {
      $("#commentForm").trigger("reset");
      toastr.success("Successfully Comment Added");
      commentInfoProcess(trip_ref);

      $(".comment-action").css("cursor", "pointer");
      $(".comment-action").removeAttr("disabled");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");

      $(".comment-action").css("cursor", "pointer");
      $(".comment-action").removeAttr("disabled");
    },
  });
});

// Prevent the default behavior of file drop (open as a URL)
dropArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  //dropArea.classList.add("drag-over");
  $("#drop-area").css({
    "border-color": "#666",
    "border-style": "dashed",
  });
});

dropArea.addEventListener("dragleave", (e) => {
  e.preventDefault();

  $("#drop-area").css({
    "border-color": "#eaecf0",
    "border-style": "solid",
  });
});

// fileInput.addEventListener("drop", function (e) {
//   e.preventDefault();
//   e.stopPropagation();

//   $("#drop-area").css({
//     "border-color": "#eaecf0",
//     "border-style": "solid",
//   });

//   const formData = new FormData();
//   formData.append("id_trip", trip_ref);
//   const droppedFiles = e.dataTransfer.files;
//   // for (const file of droppedFiles) {
//   //     const listItem = document.createElement('div');
//   //     listItem.className = 'mb-2';
//   //     listItem.textContent = file.name;
//   //     fileList.appendChild(listItem);
//   // }
//   // this.files = droppedFiles;
//   for (const file of fileInput[0].files) {
//     formData.append("files[]", file);
//   }

//   // AJAX upload
//   $.ajax({
//     url: SITE + "root/dashboard/document_process",
//     type: "POST",
//     data: formData,
//     contentType: false,
//     processData: false,
//     success: function (response) {
//       // Handle the server's response, if needed
//       documentInfoProcess(trip_ref);
//     },
//     error: function () {
//       // Handle errors, if any
//       console.log("Error uploading files.");
//     },
//   });
// });

// Add an event listener for the 'drop' event
dropArea.addEventListener("drop", function (e) {
  e.preventDefault();
  e.stopPropagation();

  // Reset the border style
  $("#drop-area").css({
    "border-color": "#eaecf0",
    "border-style": "solid",
  });

  const formData = new FormData();
  formData.append("id_trip", trip_ref); // Replace 'trip_ref' with the actual trip reference

  // Get the dropped files
  const droppedFiles = e.dataTransfer.files;

  // Append each file to the FormData
  for (const file of droppedFiles) {
    formData.append("files[]", file);
  }

  documentAjaxCall(formData);
});

fileInput.on("change", function () {
  const formData = new FormData();
  formData.append("id_trip", trip_ref);
  for (const file of fileInput[0].files) {
    formData.append("files[]", file);
  }

  documentAjaxCall(formData);
});

const documentAjaxCall = (formData) => {
  // AJAX upload
  $.ajax({
    url: SITE + "root/dashboard/document_process",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      // Handle the server's response, if needed
      documentInfoProcess(trip_ref); // Replace 'trip_ref' with the actual trip reference
    },
    error: function () {
      // Handle errors, if any
      console.log("Error uploading files.");
    },
  });
};
