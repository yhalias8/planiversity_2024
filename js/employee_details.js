const fileInput = document.getElementById("upload");
const dropArea = document.getElementById("drop-area");

$(function () {
  var dataSet = "people_uid=" + ID;
  initialProcess(dataSet);
  previousPlanProcess(EID);
});

function initialProcess(dataSet) {
  $.ajax({
    url: SITE + "ajaxfiles/employee_list/get_people_single.php",
    type: "GET",
    dataType: "json",
    data: dataSet,
    //cache: false,
    success: function (response) {
      console.log("response", response.employee_id);
      loadProperty(response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // $(".loading_screen").hide();
      // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");
    },
  });
}

function loadProperty(data) {
  let username = data.f_name + " " + data.l_name;

  $("#employee_fname").val(data.f_name);
  $("#employee_lname").val(data.l_name);
  $("#employee_email").val(data.email);
  $("#employee_id").val(data.employee_id);
  $("#employee_phone").val(data.phone);

  $("#customer_name").html(username);
  $("#customer_ref_number").html(data.employee_id);

  $("#employee_ssn").val(data.social_number);
  $("#employee_address").val(data.address);

  $("#employee_city").val(data.city);
  $("#employee_state").val(data.state);
  $("#employee_zcode").val(data.zip_code);

  $("#employee_dlnumber").val(data.driver_number);
  $("#employee_dlstate").val(data.driver_state);
  $("#employee_dldate").val(data.driver_expiration);
  $("#employee_b").val(data.birthdate);
  $("#employee_gender").val(data.gender);
  $("#employee_race").val(data.race);
  $("#travel_group").val(data.travel_group);
  $('#role').val(data.role);

  let photo = SITE + "assets/images/user_profile.png";
  let path_folder = "people";

  if (data.photo_connect == "1") {
    path_folder = "profile";
  }

  if (data.photo) {
    photo = SITE + `ajaxfiles/${path_folder}/${data.photo}`;
    $(".profile_picture_place").attr("src", photo);
  }

  $(
    'input[type="radio"][name=employee_veteran][value=' + data.veteran + "]"
  ).prop("checked", true);

  $(".rid").val(data.id_employee);
}

var migration_list = $("#migration_list").DataTable({
  processing: true,
  serverSide: true,
  ajax: {
    url: SITE + "ajaxfiles/employee_list/migration_list_processing.php",
    type: "POST",
    data: function (d) {
      d.uid = ID;
    },
  },
  columns: [
    {
      data: "created_at",
    },
    {
      data: "trip_id",
    },
    {
      data: "status",
    },
    {
      data: "updated_at",
    },
  ],
});

$("#people_update_form").validate({
  ignore: ":hidden:not(.image_field)",
  rules: {
    employee_fname: {
      required: true,
    },
    base64_image: {
      required: true,
    },
    employee_lname: {
      required: true,
    },
    employee_id: {
      required: true,
    },
    employee_email: {
      required: true,
      email: true,
    },
  },
  messages: {
    employee_fname: {
      required: "Please type first name",
    },
    base64_image: {
      required: "Please select a photo",
    },
    employee_lname: {
      required: "Please type last name",
    },
    employee_id: {
      required: "Please type customer number",
    },
    employee_email: {
      required: "Please type email",
    },
    travel_group: {
      required: "Please select a group",
    },
  },

  submitHandler: function (form) {
    $(".submit_action_button").css("cursor", "wait");
    $(".submit_action_button").attr("disabled", true);

    const e_fname = $("#employee_fname").val();
    const e_lname = $("#employee_lname").val();

    const e_username = e_fname + " " + e_lname;
    const c_ref_number = $("#customer_ref_number").html();

    $.ajax({
      url: SITE + "ajaxfiles/employee_list/update_employee_processing.php",
      type: "POST",
      data: $(form).serialize(),
      dataType: "json",
      success: function (response) {
        swal({
          title: response.message,
          type: "success",
          timer: 2500,
          showConfirmButton: true,
          customClass: "swal-height",
        });

        $("#customer_name").html(e_username);
        $("#customer_ref_number").html(c_ref_number);

        $("#base64_image").val("");

        $(".submit_action_button").css("cursor", "pointer");
        $(".submit_action_button").removeAttr("disabled");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        swal({
          title: "Error Occured",
          type: "warning",
          timer: 2500,
          showConfirmButton: true,
          customClass: "swal-height",
        });

        $(".submit_action_button").css("cursor", "pointer");
        $(".submit_action_button").removeAttr("disabled");
      },
    });
  }, // Do not change code below
  errorPlacement: function (error, element) {
    error.insertAfter(element.parent());
  },
});

$("#migration_process").validate({
  rules: {
    migration_customer_number: {
      required: true,
    },
    migration_packet_number: {
      required: true,
    },
  },
  messages: {
    migration_customer_number: {
      required: "Please type customer number",
    },
    migration_packet_number: {
      required: "Please type packet number",
    },
  },

  submitHandler: function (form) {
    $(".process_action_button").css("cursor", "wait");
    $(".process_action_button").attr("disabled", true);

    $.ajax({
      url: SITE + "root/migration/process",
      type: "POST",
      data: $(form).serialize(),
      dataType: "json",
      success: function (response) {
        $(form).trigger("reset");
        swal({
          title: response.data.message,
          type: "success",
          timer: 2500,
          showConfirmButton: true,
          customClass: "swal-height",
        });

        migration_list.ajax.reload();

        $(".process_action_button").css("cursor", "pointer");
        $(".process_action_button").removeAttr("disabled");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("jqXHR", jqXHR);
        let errorList = jqXHR.responseJSON;
        swal({
          title: errorList.data.message,
          type: "warning",
          timer: 2500,
          showConfirmButton: true,
          customClass: "swal-height",
        });

        $(".process_action_button").css("cursor", "pointer");
        $(".process_action_button").removeAttr("disabled");
      },
    });
  }, // Do not change code below
  errorPlacement: function (error, element) {
    error.insertAfter(element.parent());
  },
});

$(".update_profile").click(function () {
  $("#upload").click();
});

$(".uploaded_image").click(function () {
  $("#upload").click();
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

dropArea.addEventListener("drop", (e) => {
  e.preventDefault();

  $("#drop-area").css({
    "border-color": "#eaecf0",
    "border-style": "solid",
  });

  const file = e.dataTransfer.files[0];

  readFile(e.dataTransfer);
});

var $uploadCrop, tempFilename, rawImg, imageId;

function readFile(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(".upload-demo").addClass("ready");
      //$('#btn-crop-image').click();
      $("#cropImagePop").modal("show");
      rawImg = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    alert("Sorry - you're browser doesn't support the FileReader API");
  }
}

$uploadCrop = $("#upload-demo").croppie({
  viewport: {
    width: 200,
    height: 200,
    type: "circle",
  },
  enableExif: true,
  showZoomer: true,
  enableResize: true,
  enableOrientation: true,
  mouseWheelZoom: "ctrl",
});

$("#cropImagePop").on("shown.bs.modal", function () {
  $uploadCrop
    .croppie("bind", {
      url: rawImg,
    })
    .then(function () {
      console.log("jQuery bind complete");
    });
});

$("#upload").on("change", function () {
  readFile(this);
});

$("#cropImageBtn").on("click", function (ev) {
  $uploadCrop
    .croppie("result", {
      type: "base64",
      format: "png",
    })
    .then(function (resp) {
      $.ajax({
        url: SITE + "ajaxfiles/employee_list/upload_profile.php",
        method: "POST",
        data: {
          image: resp,
          useId: ID,
        },
        success: function (data) {
          $(".profile_picture_place").attr("src", data);
          //$('#upload-demo').croppie('destroy');
          //$("#formId").trigger("reset");
          $("#cropImagePop").modal("hide");
          //$('.btn-close-modal').click();
          toastr.success("Profile Picture Successfully Updated");

          //console.log('data',data);

          //location.reload();
        },
      });

      // $(".profile_picture_place").attr("src", resp);
      // $("#base64_image").val(resp);
      // $("#cropImagePop").modal("hide");
    });
});

const previousPlanProcess = (eid) => {
  $("#previous_plan_loading").show();
  $.ajax({
    url: SITE + "root/people/previous_plans",
    type: "GET",
    data: { eid: eid },
    dataType: "json",
    success: function (data) {
      console.log("Data", data);
      let items = "";

      $.each(data, function (index, item) {
        items += `        
        <div class="event_item" id="event_620" data-id="620">
        <div class="event_header">
            <h4> ${item.trip_name} </h4>

        </div>
        <div class="event_body">
            <div class="event_date">
                <p>${item.trip_date} </p>
            </div>

        </div>
    </div>        
        `;

       
      });
     

      if (data == null || data.length === 0) {
        items = "";
        items += `<h3 class="no-found"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There are no data to display.</h3>`;
      }

      $("#event_list").html(items);

      $("#previous_plan_loading").hide();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      toastr.error("A system error has been encountered. Please try again");
      $("#previous_plan_loading").hide();
    },
  });
};
