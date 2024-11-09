var idtrip = $('#notes_idtrip').val()

$(function () {
	getNotesList()
})

function getNotesList() {
	var items = ''
	$.getJSON(
		SITE + 'ajaxfiles/note/get_note.php',
		{ id_trip: idtrip },
		function (data) {
			$.each(data, function (index, item) {
				items += `<div class="itinerary-field" id="note_${item.id_note}">
            <div class="itinerary-field__content">
                <p>${item.text}</p>
                </div>
                <div class="itinerary-field__actions">
                    <button id="update" type="button" id='update' value="${item.id_note}" class="itinerary-field__button itinerary-field__button_edit edit_note" title="Edit Note" value="1">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button id="delete" type="button" id='delete' value="${item.id_note}" class="itinerary-field__button itinerary-field__button_delete" title="Delete Note" value="1">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>`
			})

			$('#data_list').html(items)
		}
	)
}

$(document).on('click', 'button#update', function (event) {
	var id = $(this).val()

	$('#update_note').modal('show')

	var dataSet = 'id=' + id

	$.ajax({
		url: SITE + 'ajaxfiles/note/get_note_single.php',
		type: 'GET',
		data: dataSet,
		dataType: 'json',
		success: function (response) {
			if (response) {
				$('#e_notes_text').html(response['text'])
				$('#item_id').val(response['id_note'])
			}
		},
	})
})

$('#notes_form').validate({
	rules: {
		notes_text: {
			required: true,
		},
	},
	messages: {
		notes_text: {
			required: 'Please type note text',
		},
	},

	submitHandler: function (form) {
		$('#notes_add').css('cursor', 'wait')
		$('#notes_add').attr('disabled', true)

		$.ajax({
			url: SITE + 'ajaxfiles/note/add_note.php',
			type: 'POST',
			data: $(form).serialize(),
			dataType: 'json',
			success: function (response) {
				$('#notes_form').trigger('reset')
				$('#timeline_date').val('')
				toastr.success('Successfully Note Added')
				getNotesList()

				$('#notes_add').css('cursor', 'pointer')
				$('#notes_add').removeAttr('disabled')
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toastr.error('A system error has been encountered. Please try again')

				$('#notes_add').css('cursor', 'pointer')
				$('#notes_add').removeAttr('disabled')
			},
		})
	}, // Do not change code below
	errorPlacement: function (error, element) {
		error.insertAfter(element.parent())
	},
})

$('#note_form_update').validate({
	rules: {
		notes_text: {
			required: true,
		},
	},
	messages: {
		notes_text: {
			required: 'Please type note text',
		},
	},

	submitHandler: function (form) {
		$('.update_submit_button').css('cursor', 'wait')
		$('.update_submit_button').attr('disabled', true)

		var trip_generated = $('#trip_generated').val()
		var trip_u_id = $('#trip_u_id').val()
		var trip_title = $('#trip_title').val()

		$.ajax({
			url: SITE + 'ajaxfiles/note/update_note.php',
			type: 'POST',
			data:
				$(form).serialize() +
				'&trip_generated=' +
				trip_generated +
				'&trip_u_id=' +
				trip_u_id +
				'&trip_title=' +
				trip_title,
			dataType: 'json',
			success: function (response) {
				$('#note_form_update').trigger('reset')
				toastr.success('Successfully Note Updated')
				getNotesList()

				$('.update_submit_button').css('cursor', 'pointer')
				$('.update_submit_button').removeAttr('disabled')
				$('#update_note').modal('hide')
			},
			error: function (jqXHR, textStatus, errorThrown) {
				toastr.error('A system error has been encountered. Please try again')

				$('.update_submit_button').css('cursor', 'pointer')
				$('.update_submit_button').removeAttr('disabled')
				$('#update_note').modal('hide')
			},
		})
	}, // Do not change code below
	errorPlacement: function (error, element) {
		error.insertAfter(element.parent())
	},
})

$(document).on('click', 'button#delete', function (event) {
	$('.note_action').css('cursor', 'wait')
	$('.note_action').attr('disabled', true)

	var id = $(this).val()

	swal(
		{
			title: 'Are you sure?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: 'Yes, delete it!',
			closeOnConfirm: true,
		},
		function () {
			$.ajax({
				type: 'POST',
				url: SITE + 'ajaxfiles/note/delete_note.php',
				data: {
					id: id,
				},
				dataType: 'json',
				success: function (response) {
					toastr.success(response.message)
					//$("#note_" + id + "").remove();
					$('#note_' + id).slideUp(150, function () {
						$('#note_' + id).remove()
					})
					$('.note_action').css('cursor', 'pointer')
					$('.note_action').removeAttr('disabled')
				},
				error: function (jqXHR, textStatus, errorThrown) {
					toastr.error(jqXHR.responseJSON)
				},
			})
		}
	)
})
