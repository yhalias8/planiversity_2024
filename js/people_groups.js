var group_list = $("#group_list").DataTable({
  processing: true,
  serverSide: true,
  type: "POST",
  ajax: SITE + "ajaxfiles/people_group/group_list_processing.php",
  columns: [
    {
      data: "created_at",
    },
    {
      data: "group_name",
    },
    {
      data: "description",
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
        " <td><div align='center'><a id='view' href='#update_modal' class='btn btn-mini btn-info' role='button' data-toggle='modal' title='View Group'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete People'><i class='fa fa-trash'></i> Delete</a></div></td>",
    },
  ],
});

$("#group_list tbody").on("click", "#view", function () {
  var data = group_list.row($(this).parents("tr")).data();

  $("#group_name").val(data.group_name);
  $("#description").val(data.description);
  $("#eid").val(data.id);
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

          // swal({
          //   title: response.message,
          //   type: "success",
          //   timer: 2500,
          //   showConfirmButton: true,
          //   customClass: "swal-height",
          // });

          toastr.success(response.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          // swal({
          //   title: "Error Occured",
          //   type: "warning",
          //   timer: 2500,
          //   showConfirmButton: true,
          //   customClass: "swal-height",
          // });

          toastr.success("Error Occured");
        },
      });
    }
  );
});

$("#addForm").validate({
  rules: {
    group_name: {
      required: true,
    },
  },
  messages: {
    group_name: {
      required: "Please type group name",
    },
  },

  submitHandler: function (form) {
    $(".submit_button").css("cursor", "wait");
    $(".submit_button").attr("disabled", true);

    $.ajax({
      url: SITE + "ajaxfiles/people_group/group_process.php",
      type: "POST",
      data: $(form).serialize(),
      dataType: "json",
      success: function (response) {
        $(form).trigger("reset");
        $("#add_modal").modal("hide");
        group_list.ajax.reload();

        toastr.success(response.message);

        // swal({
        //   title: response.message,
        //   type: "success",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        $(".submit_button").css("cursor", "pointer");
        $(".submit_button").removeAttr("disabled");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // swal({
        //   title: "Error Occured",
        //   type: "warning",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        toastr.success("Error Occured");

        $(".submit_button").css("cursor", "pointer");
        $(".submit_button").removeAttr("disabled");
      },
    });
  }, // Do not change code below
  errorPlacement: function (error, element) {
    error.insertAfter(element.parent());
  },
});

$("#updateForm").validate({
  rules: {
    group_name: {
      required: true,
    },
  },
  messages: {
    group_name: {
      required: "Please type group name",
    },
  },

  submitHandler: function (form) {
    $(".submit_button").css("cursor", "wait");
    $(".submit_button").attr("disabled", true);

    $.ajax({
      url: SITE + "ajaxfiles/people_group/update_group_process.php",
      type: "POST",
      data: $(form).serialize(),
      dataType: "json",
      success: function (response) {
        $("#update_modal").modal("hide");

        group_list.ajax.reload();

        // swal({
        //   title: response.message,
        //   type: "success",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        toastr.success(response.message);

        $(".submit_button").css("cursor", "pointer");
        $(".submit_button").removeAttr("disabled");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // swal({
        //   title: "Error Occured",
        //   type: "warning",
        //   timer: 2500,
        //   showConfirmButton: true,
        //   customClass: "swal-height",
        // });

        toastr.success("Error Occured");

        $(".submit_button").css("cursor", "pointer");
        $(".submit_button").removeAttr("disabled");
      },
    });
  }, // Do not change code below
  errorPlacement: function (error, element) {
    error.insertAfter(element.parent());
  },
});
