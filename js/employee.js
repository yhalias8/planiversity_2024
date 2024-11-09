const fileInput = document.getElementById("upload");
const dropArea = document.getElementById("drop-area");

var employee_list = $("#employee_list").DataTable({
  processing: true,
  serverSide: true,
  type: "POST",
  ajax: SITE + "ajaxfiles/employee_list/employees_list_processing.php",
  columns: [
    {
      data: "photo",
      render: function (data, type, row) {
        let photo = SITE + "assets/images/user_profile.png";

        let path_folder = "people";

        if (row.photo_connect == "1") {
          path_folder = "profile";
        }

        if (data) {
          photo = SITE + `ajaxfiles/${path_folder}/${data}`;
        }

        return "<img class='img-responsive table-image' src='" + photo + "' />";
      },
    },
    {
      data: "f_name",
    },
    {
      data: "l_name",
    },
    {
      data: "email",
    },
    {
      data: "employee_id",
    },
    {
      data: "phone",
    },

    {
      data: "action",
      name: "action",
      className: "center",
      orderable: false,
      searchable: false,
    },
  ],
  columnDefs: [
    {
      targets: -1,
      data: null,
      defaultContent:
        " <td><div align='center'><a id='view' class='btn btn-mini btn-info' title='View People'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete People'><i class='fa fa-trash'></i> Delete</a></div></td>",
    },
  ],
});

$("#employee_list tbody").on("click", "#view", function () {
  var data = employee_list.row($(this).parents("tr")).data();
  var url = SITE + "people/" + data.id_employee;
  window.location.href = url;
});

$("#employee_list tbody").on("click", "#delete", function () {
  var data = employee_list.row($(this).parents("tr")).data();

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
        url: SITE + "ajaxfiles/employee_list/delete_employee.php",
        data: {
          id: data.id_employee,
        },
        dataType: "json",
        success: function (response) {
          employee_list.ajax.reload();

          swal({
            title: response.message,
            type: "success",
            timer: 2500,
            showConfirmButton: true,
            customClass: "swal-height",
          });
        },
        error: function (jqXHR, textStatus, errorThrown) {
          swal({
            title: "Error Occured",
            type: "warning",
            timer: 2500,
            showConfirmButton: true,
            customClass: "swal-height",
          });
        },
      });
    }
  );
});

$("#employee_form").validate({
  ignore: ":hidden:not(.image_field)",
  rules: {
    employee_fname: {
      required: true,
    },
    base64_image: {
      //required: true,
      required: function () {
        return $("#photo_connect").val() == 0;
      },
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
    /*
    travel_group: {
      required: true,
    },
     */
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

    var upload_item = $(".upload_item")
      .map(function () {
        return this.value;
      })
      .get();

    $.ajax({
      url: SITE + "ajaxfiles/employee_list/employees_data_processing.php",
      type: "POST",
      data: $(form).serialize() + "&upload_item=" + upload_item,
      dataType: "json",
      success: function (response) {
        $(form).trigger("reset");
        $("#uploaded-group1").html("");

        employee_list.ajax.reload();

        // swal({
        //   title: response.message,
        //   type: "success",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        toastr.success(response.message);

        $(".profile_picture_place").attr(
          "src",
          SITE + "assets/images/user_profile.png"
        );
        $("#base64_image").val("");
        $("#customer_name").html("New User");
        $("#customer_ref_number").html("");

        $(".submit_action_button").css("cursor", "pointer");
        $(".submit_action_button").removeAttr("disabled");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // swal({
        //   title: "Error Occured",
        //   type: "warning",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        toastr.error("Error Occured");

        $(".submit_action_button").css("cursor", "pointer");
        $(".submit_action_button").removeAttr("disabled");
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

// fileInput.addEventListener("change", () => {
//   const file = fileInput.files[0];
//   // Use the selected file or do something with it
//   readFile(fileInput);
// });

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
      $(".profile_picture_place").attr("src", resp);
      $("#photo_connect").val(0);
      $("#photo").val("");
      $("#base64_image").val(resp);
      $("#cropImagePop").modal("hide");
    });
});

// Auto Populate Process

// Function to display error message for a field
function showError(field, message) {
  $(field).html(message);
}

// toastr.options = {
//   closeButton: true,
//   debug: false,
//   newestOnTop: false,
//   progressBar: false,
//   positionClass: "toast-bottom-right",
//   preventDuplicates: false,
//   onclick: null,
//   showDuration: "300",
//   hideDuration: "1000",
//   timeOut: "5000",
//   extendedTimeOut: "1000",
//   showEasing: "swing",
//   hideEasing: "linear",
//   showMethod: "fadeIn",
//   hideMethod: "fadeOut",
// };

const responseDataProcess = (data) => {
  if (data.name) {
    $("#employee_fname").val(data.name);
    $("#customer_name").html(data.name);
  }

  if (data.email) {
    $("#employee_email").val(data.email);
  }

  if (data.customer_number) {
    $("#employee_id").val(data.customer_number);
    $("#customer_ref_number").html(data.customer_number);
  }

  if (data.picture) {
    let image_path = SITE + "ajaxfiles/profile/" + data.picture;
    $("#photo_connect").val(1);
    $("#photo").val(data.picture);
    $("#base64_image").val("");
    $(".profile_picture_place").attr("src", image_path);
  }
};

$(".email_process").on("click", function (e) {
  var employee_email = $("#employee_email");

  if ($.trim(employee_email.val()) === "") {
    showError("#employee_email-error", "Please enter a email address");
    return;
  }

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(employee_email.val())) {
    showError("#employee_email-error", "Please enter a valid email address");
    return;
  }

  $(this).css("cursor", "wait");
  $(this).attr("disabled", true);

  $.ajax({
    url: SITE + "root/people/email_process",
    type: "GET",
    data: "email_trigger=" + employee_email.val(),
    dataType: "json",
    success: function (response) {
      console.log(response);
      //$("#commentForm").trigger("reset");
      toastr.success("Successfully Process Data");
      responseDataProcess(response.data);

      $(".email_process").css("cursor", "pointer");
      $(".email_process").removeAttr("disabled");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#employee_email").val("");
      swal({
        title: "No Data Found",
        type: "error",
      });
      $(".email_process").css("cursor", "pointer");
      $(".email_process").removeAttr("disabled");
    },
  });
});

$(".employee_process").on("click", function (e) {
  var employee_id = $("#employee_id");

  if ($.trim(employee_id.val()) === "") {
    showError("#employee_id-error", "Please type customer number");
    return;
  }

  $(this).css("cursor", "wait");
  $(this).attr("disabled", true);

  $.ajax({
    url: SITE + "root/people/customer_number_process",
    type: "GET",
    data: "customer_number_trigger=" + employee_id.val(),
    dataType: "json",
    success: function (response) {
      console.log(response);
      toastr.success("Successfully Process Data");
      responseDataProcess(response.data);

      $(".employee_process").css("cursor", "pointer");
      $(".employee_process").removeAttr("disabled");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#employee_id").val("");
      swal({
        title: "No Data Found",
        type: "error",
      });
      $(".employee_process").css("cursor", "pointer");
      $(".employee_process").removeAttr("disabled");
    },
  });
});
