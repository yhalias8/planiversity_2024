function tabProcess(e, tab) {

    $(' li.tab-search h4').removeClass("active");
    $(this).addClass("active");
    $('.row.tab-place').removeClass("active");
    $('#' + tab).addClass("active");

}


$(function() {
    getFirstItemFocus();
});


function getFirstItemFocus() {

    var e = $("ul.list-unstyled.trip_event li.nav-item .tablinks:first");
    var get_type = $("ul.list-unstyled.trip_event li.nav-item .tablinks:first-child").data("type"); //.attr("data-type");
    var get_id = $("ul.list-unstyled.trip_event li.nav-item .tablinks:first-child").data("id"); //.attr("data-id");   
    if (get_type && get_id) {
        eventProcess(e, get_type, get_id);
        $(e).addClass("active");
    }

}

$(document).ready(function() {
    $('#keyword-input').keyup(function() {

        // Search text
        var text = $(this).val();

        // Hide all content class element
        $('.nav-item').hide();

        var isContains = $('.nav-item .tablinks .events_items_box_trips .event_items_left span.meet_btn').text().toUpperCase().indexOf(text.toUpperCase()) > -1;

        if (isContains) {
            $('#not-found').hide();
        } else {
            $('#not-found').show();
        }

        // Search 
        $('.nav-item .tablinks .events_items_box_trips .event_items_left span.meet_btn:contains("' + text + '")').closest('.nav-item').show();



    });

    $('#name-input').keyup(function() {

        // Search text
        var text = $(this).val();

        // Hide all content class element
        $('.nav-item').hide();

        var isContains = $('.nav-item .tablinks .events_items_box_trips .event_items_left h2').text().toUpperCase().indexOf(text.toUpperCase()) > -1;

        if (isContains) {
            $('#not-found').hide();
        } else {
            $('#not-found').show();
        }

        // Search 
        $('.nav-item .tablinks .events_items_box_trips .event_items_left h2:contains("' + text + '")').closest('.nav-item').show();

    });

});

$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function(elem) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});


function eventProcess(e, type, id) {

    if (type && id) {

        $('.tablinks.active').removeClass("active");
        $(this).addClass("active");


        $('#event_placeholder').addClass("loading");
        $('#event_content').html('<div class="spinner-grow text-info custom-load"></div>');

        var dataSet = 'type=' + type + '&id=' + id;

        $.ajax({
            url: SITE + "ajaxfiles/trip_dashboard/process.php",
            type: "POST",
            data: dataSet,
            dataType: 'html',
            success: function(response) {

                $('#event_content').html(response);
                $('#event_placeholder').removeClass("loading");


            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('#event_content').html("<h2>A system error has been encountered. Please try again</h2>");
                $('#event_placeholder').removeClass("loading");


            }

        });

    }


}