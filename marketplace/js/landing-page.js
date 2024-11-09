let serviceList = [];
let next_page = null;
let category = 0;

$(function () {
  initialServiceLoad();
});

$("#search_form").validate({
  rules: {
    search: {
      required: true,
    },
    category_field: {
      required: true,
    },
  },
  messages: {
    search: {
      required: "Please type your keyword",
    },
    category_field: {
      required: "Please select a category",
    },
  },

  submitHandler: function (form) {
    
    var search = $("#search").val();
    var category_field = $("#category_field").val();
    var dataSet = "category=" + category_field + "&search=" + search;
    
    window.open(SITE + "marketplace?" + dataSet);     
    
  }, // Do not change code below
  errorPlacement: function (error, element) {
    //error.insertAfter(element.parent());
  },
});

function initialServiceLoad() {
  //let uuid = localStorageValueGet();
  var current_page = $(".view_more").val();
  var dataSet = "category=" + category + "&page=" + 1 + "&uuid=" + "";
  serviceListProcess(dataSet);
}

function serviceListProcess(
  dataSet,
  cat_mode = null,
  load_more = null,
  data_empty = null
) {
  if (data_empty) {
    $("#service_content").html("");
  }

  $(".loading_section").show();
  // $(".load_more").hide();
  // $(".target_action").attr("disabled", false);
  // $(".background_search").attr("disabled", true);

  $(".e_button").css("cursor", "wait");
  $(".e_button").attr("disabled", true);

  if (cat_mode) {
    $(".button-class").css("cursor", "wait");
    $(".button-class").attr("disabled", true);
  }

  $.ajax({
    url: SITE + "root/service/list",
    type: "GET",
    data: dataSet,
    dataType: "json",
    cache: false,
    success: function (response) {
      console.log("response", response);

      $(".loading_section").hide();
      if (load_more) {
        $("#service_content").append(response.data.responseList);
        serviceList = [...serviceList, ...response.data.results.data];
      } else {
        $("#service_content").html(response.data.responseList);
        serviceList = [...response.data.results.data];
      }

      next_page = response.data.next_page;
      // request_query = response.data.request_query;

      $('.service_count').html(response.data.total_count);

      console.log("serviceList", serviceList);

      $(".view_more").val(response.data.next_page);
      // $("#previous_button").val(response.data.previous_page);

      // if (response.data.responseList) {
      //     $("#api_proceced").val(1);
      //     $("#api_query").val(request_query);
      // }

      // if (next_page || previous_page) {
      //     $(".load_more").show();
      // }

      console.log(
        "response.data.results.next_page_url",
        response.data.results.next_page_url
      );

      if (response.data.results.next_page_url == null) {
        $(".view_more").hide();
      } else {
        $(".view_more").show();
      }

      // if (previous_page == null) {
      //     $("#previous_button").attr("disabled", true);
      // }

      // $(".background_search").attr("disabled", false);

      if (cat_mode) {
        $(".button-class").css("cursor", "pointer");
        $(".button-class").removeAttr("disabled");
      }

      $(".e_button").css("cursor", "pointer");
      $(".e_button").removeAttr("disabled");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // $(".loading_screen").hide();
      // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");
    },
  });
}

$(document).on("click", ".button-class", function (event) {
  var value_hold = $(this).attr("value");
  var current_page = $(".view_more").val();
  var dataSet = "category=" + value_hold + "&page=" + 1 + "&uuid=" + "";
  category = value_hold;
  serviceListProcess(dataSet, true);
  classToggle(this);
});

const classToggle = (e) => {
  $(".button-class").removeClass("active");
  $(e).addClass("active");
};

$(document).on("click", ".buy-btn", function (event) {
  var serviceUUID = $(this).val();
  window.location.href = SITE + "marketplace/order/" + serviceUUID;
});

$(document).on("click", ".back_button", function (e) {
  e.preventDefault();
  window.history.back();
});

$(document).on(
  "click",
  "img.service_image,.service-header h4",
  function (event) {
    //var service_id = $(".service-wrapper").data("service-id");
    var serviceID = $(this)
      .parent()
      .closest(".service-wrapper")
      .data("service-id");
    const matchValue = serviceList.findIndex((item) => item.id == serviceID);
    serviceData = serviceList[matchValue];

    console.log("serviceData", serviceData);

    serviceValueProcess(serviceData);

    $("#master_modal").modal("show");
  }
);

function serviceValueProcess(serviceData) {
  $(".service_heading h3").html(serviceData.service_title);
  $(".service_heading h5").html(serviceData.category.category_name);
  $("img.heading_img").attr(
    "src",
    FILE_PATH + "images/service/" + serviceData.service_image
  );
  $(".author_image").attr(
    "src",
    FILE_PATH + "images/author/" + serviceData.author_image
  );
  $(".seller_info").html(serviceData.author_description);
  $("#author_name").html(serviceData.author_name);
  $(".service_content").html(serviceData.service_description);
  $(".rating").html(serviceData.service_rating);
  $(".review").html(serviceData.reviews_count);
  $(".buy-btn").val(serviceData.service_uuid);

  servicePriceProcess(
    serviceData.regular_price,
    serviceData.sale_price,
    serviceData.member_price
  );
}

function servicePriceProcess(regular_price, sale_price, member_price) {
  var items = "";

  if (sale_price == 0) {
    items += "<p>Regular Price : <span>$" + regular_price + "</span></p>";
  } else {
    items +=
      "<p class='old-price'>Regular Price : <span>$" +
      regular_price +
      "</span></p>";
    items += "<p>Sale Price : <span>$" + sale_price + "</span></p>";
  }

  items += "<p>Member Price : <span>$" + member_price + "</span></p>";

  $(".payment_info").html(items);
}
