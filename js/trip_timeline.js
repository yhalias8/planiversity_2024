// see employee details
function see_detail(id) {
    $('#details' + id).toggle('slow');
}

// delete a timeline
function del_element(id) {
    var tmp = 'Are you sure you want to delete this Timeline?';
    $('#loading_list').hide('fast');
    $('#error_list').hide('fast');
    if (confirm(tmp)) {
        setTimeout(function () {
            $.post(SITE + "ajaxfiles/add_timeline.php", { id: id },
                function (data) {
                    if (data['error']) {
                        $('#error_list').html(data['error']);
                        $('#error_list').fadeIn(500);
                    } else {
                        $('#timeline_' + id).fadeOut(1000);
                    }
                }, "json");
        }, 0);
    } else
        return false;
}

// edit a timeline
function edit_element(id) {
    $('#loading_list').hide('fast');
    $('#error_list').hide('fast');
    $('#timeline_add').hide('fast');
    $('#timeline_edit').show('fast');
    $('#timeline_cancel').show('fast');
    setTimeout(function () {
        $.post(SITE + "ajaxfiles/edit_timeline.php", { id: id },
            function (data) {
                if (data['error']) {
                    $('#error_list').html(data['error']);
                    $('#error_list').fadeIn(500);
                } else {
                    $('#timeline_name').val(data['name']);
                    $('#timeline_date').val(data['fulldate']);
                    $('#timeline_id').val(id);
                    $('#datetimepicker3').datetimepicker('setOptions', { value: data['fulldate'] });
                }
            }, "json");
    }, 0);
}

$(document).ready(function () {

    $('#timeline_add').click(function (event) { // add timeline to DB
        //setTimeout(function() {
        $('#loading_list').show('fast');
        $('#error_list').hide('fast');
        $.post(SITE + "ajaxfiles/add_timeline.php", {
            name: $('#timeline_name').val(),
            date: $('#timeline_date').val(),
            time: $('#timeline_time').val(),
            trip: $('#timeline_idtrip').val()
        },
            function (data) {
                if (data['error']) {
                    $('#loading_list').hide('fast');
                    $('#error_list').html(data['error']);
                    $('#error_list').fadeIn(500);
                } else {
                    $('#loading_list').hide('fast');
                    $('#data_list').append(data['txt']);
                    $('#data_list').fadeIn(1000);
                    $('#timeline_name').val('');
                }
            }, "json");
    });

    $('#timeline_edit').click(function (event) { // edit timeline
        //setTimeout(function() {
        $('#loading_list').show('fast');
        $('#error_list').hide('fast');
        $.post(SITE + "ajaxfiles/edit_timeline.php", {
            idt: $('#timeline_id').val(),
            name: $('#timeline_name').val(),
            tdate: $('#timeline_date').val()
        },
            function (data) {
                if (data['error']) {
                    $('#loading_list').hide('fast');
                    $('#error_list').html(data['error']);
                    $('#error_list').fadeIn(500);
                } else {
                    $('#loading_list').hide('fast');
                    $('#timeline_add').show('fast');
                    $('#timeline_edit').hide('fast');
                    $('#timeline_cancel').hide('fast');
                    $('#data_list').fadeIn(1000);
                    $('#timeline_name').val('');
                    $('#timeline_date').val('');
                }
            }, "json");
    });

    $('#timeline_cancel').click(function (event) { // cancel timeline
        $('#error_list').hide('fast');
        $('#loading_list').hide('fast');
        $('#timeline_add').show('fast');
        $('#timeline_edit').hide('fast');
        $('#timeline_cancel').hide('fast');
        $('#timeline_name').val('');
        $('#timeline_date').val('');
    });

});

function timeline_edit(id, name, date) {
    $('#loading_list').show('fast');
    $('#error_list').hide('fast');
    $.post(SITE + "ajaxfiles/edit_timeline.php", { idt: id, name: $('#' + name).val(), tdate: date },
        function (data) {
            if (data['error']) {
                $('#loading_list').hide('fast');
                $('#error_list').html(data['error']);
                $('#error_list').fadeIn(500);
            } else {
                $('#loading_list').hide('fast');
                $('#timeline_add').show('fast');
                $('#timeline_edit').hide('fast');
                $('#timeline_cancel').hide('fast');
                $('#timeline_' + id).html(data['txt']);
                $('#data_list').fadeIn(1000);
                $('#timeline_name').val('');
                $('#timeline_date').val('');
            }
        }, "json");
}