var idtrip = $('#plans_idtrip').val()
var location_to_lat = $('#location_to_lat').val()
var location_to_lng = $('#location_to_lng').val()

const planStepper = new ModalStepper('#advanced_popup')

$(function () {
	getPlansList()
	planStepper.init()
})

function formatDateTime(dateString) {

	const dateObj = new Date(dateString);

	const day = dateObj.getDate();
	let month = dateObj.getMonth();
	const year = dateObj.getFullYear();
	const hours = dateObj.getHours();
	const minutes = dateObj.getMinutes();


	if (month < 10) {
		month = "0" + month;
	}

	const period = hours >= 12 ? 'PM' : 'AM';
	const formattedHours = hours % 12 || 12;
	const formattedMinutes = minutes.toString().padStart(2, '0');

	return `${day}.${month}.${year} at ${formattedHours}:${formattedMinutes}${period}`;
}

function getPlansList() {
	var items = ''
	$.getJSON(
		SITE + 'ajaxfiles/plan/get_plan.php',
		{ id_trip: idtrip },
		function (data) {
			$.each(data, function (index, item) {
				if (item.schedule_linked == '1') {
					return
				}

				let schedule_linked = ''
				let schedule_flag_status = ''
				let date_icon = ''
				let checkin_flag = ''
				console.log('item', item)
				//items += "<div class='note-result-wrap' id='note_"+item.id_note+"'><p><span style='color:#78859A;' class='load_item'>" + item.text + "</span> <span class='button_action'><a href='#'' onclick='del_element("+item.id_note+")' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times-circle edit-icon' style='color:#058BEF;'></i></a><a href='#' onclick='edit_form("+item.id_note+");' data-toggle='tooltip' data-placement='top' data-original-title='Edit'><i class='fa fa-pencil (alias) edit-icon' style='color:#058BEF;'></i></a></span></p></div>";
				// if (item.schedule_linked == '1') {
				// 	schedule_linked = `<span class="schedule_linked"><i class="fa fa-calendar-check-o" aria-hidden="true" title="Schedule Linked"></i></span>`
				// }
				// if (item.schedule_flag == '1') {
				// 	schedule_flag_status = 'disabled'
				// }
				if (item.plan_date) {
					date_icon = `<span class="schedule_linked"><i class="fa fa-calendar-o" aria-hidden="true" title="Date Linked"></i></span> ` + formatDateTime(item.plan_date);
				}
				if (item.plan_checked_in === '1') {
					checkin_flag = `<span class="schedule_linked"><i class="fa fa-check" aria-hidden="true" title="Checked In"></i></span>`
				}

				items += `<div class="itinerary-field" id="plan_${item.id_plan}">
				<div class="itinerary-field__content">
				<p class="itinerary-field__icons" style="display:block">${schedule_linked} ${date_icon} ${checkin_flag}</p>				
				<h4 id="plan_name"> ${item.plan_name}</h4>
				<p> <i class="fa fa-map-marker" aria-hidden="true"></i>  ${item.plan_address}</p>
				<p id="plan_address">${item.plan_type}</p>
				</div>
				<div class="itinerary-field__actions">
						<button id="update" type="button" onclick="edit_plan(${item.id_plan})" class="itinerary-field__button itinerary-field__button_edit action_button" title="Edit Note" value="1">
								<i class="fa fa-pencil"></i>
						</button>
						<button id="delete" type="button" onclick="remove_plan(${item.id_plan},${item.schedule_linked},${item.schedule_id})"  class="itinerary-field__button itinerary-field__button_delete action_button" title="Delete Note" value="1">
								<i class="fa fa-trash"></i>
						</button>
				</div>
		</div>`
			})

			$('#plan_list').html(items)
		}
	)
}

function edit_plan(id) {
	var dataSet = 'id=' + id

	$('#advance_check').val(1)
	$('#schedule_flag').val(0)

	const matchValue = markers_list.findIndex(item => item.id == id)

	$('.action_button').css('cursor', 'not-allowed')
	$('.action_button').attr('disabled', true)

	$.ajax({
		url: SITE + 'ajaxfiles/plan/get_plan_single.php',
		type: 'GET',
		data: dataSet,
		dataType: 'json',
		success: function (response) {
			if (response) {
				$('#plan_address').prop('disabled', false)
				var icon_image = iconSelect(response['plan_type'])
				DeleteMarker(id)
				changeMarkerPosition(
					response['plan_lat'],
					response['plan_lng'],
					icon_image,
					'bounce'
				)
				$('#btn-plan').html('Update')
				$('#plan_title').val(response['plan_name'])
				$('#plan_id').val(response['id_plan'])
				$('#plan_type').val(response['plan_type'])
				$('#plan_address').val(response['plan_address'])
				$('#location_to_lat').val(response['plan_lat'])
				$('#location_to_lng').val(response['plan_lng'])
				$('body').scrollTop(0)
				$('#plan_title').focus()

				let checkinVal = response['plan_checked_in'] == 1
				let dateAddedVal = !!response['plan_date']

				let stepperPrefill = {
					[checkinVal ? '#plan_checkin_yes' : '#plan_checkin_no']: true,
					[dateAddedVal ? '#plan_date_yes' : '#plan_date_no']: true,
				}

				if (dateAddedVal) {
					stepperPrefill['#event_date'] = moment(response['plan_date']).format(
						'L'
					)
					stepperPrefill['#event_time'] = moment(response['plan_date']).format(
						'hh:mm'
					)
				}

				planStepper.prefill(stepperPrefill)
			}
		},
	})
}

$.validator.addMethod(
	'checkHiddenValue',
	function (value, element) {
		if (value == 1) {
			console.log('value return first', value)
			$('#advanced_popup').modal('show')
			return false
		} else {
			console.log('value return second', value)
			return true
		}
	},
	''
)

$('#form-plan').validate({
	//ignore: ":hidden:not(.input_option_opacity)",
	rules: {
		plan_title: {
			required: true,
		},
		plan_type: {
			required: true,
		},
		plan_address: {
			required: true,
		},
	},
	messages: {
		plan_title: {
			required: 'Please type plan title',
		},
		plan_type: {
			required: 'Please select activity type',
		},
		plan_address: {
			required: 'Please type address',
		},
	},
	submitHandler: function (form) {
		var advance_check = $('#advance_check').val()
		if (advance_check == 1) {
			planStepper.open()
		} else {
			console.log('Submitted')

			$('#btn-plan').css('cursor', 'wait')
			$('#btn-plan').attr('disabled', true)

			var plan_id = $('#plan_id').val()
			var plan_title = $('#plan_title').val()
			var plan_type = $('#plan_type').val()
			var plan_address = $('#plan_address').val()
			var loc_lat = $('#location_to_lat').val()
			var loc_lng = $('#location_to_lng').val()

			var event_date = ''
			var event_time = ''

			if ($('#plan_date_yes').is(':checked')) {
				event_date = $('#event_date').val()
				event_time = $('#event_time').val()
			}

			var checkIn = $('#plan_checkin_yes').is(':checked')

			$.ajax({
				url: SITE + 'ajaxfiles/plan/add_plan.php',
				type: 'POST',
				data:
					$(form).serialize() +
					'&event_date=' +
					event_date +
					'&event_time=' +
					event_time +
					'&event_checkin=' +
					checkIn,
				dataType: 'json',
				success: function (response) {
					var icon_image = iconSelect(plan_type)

					if (response.action == 'Update') {
						const matchValue = markers_list.findIndex(
							item => item.id == plan_id
						)

						let updated_state = [...markers_list]

						let updated_element = {
							...updated_state[matchValue],
						}
						let plan_date = event_date ?? ''
						if (plan_date && event_time) {
							plan_date = `${plan_date} ${event_time}`
						}
						updated_element.title = plan_title
						updated_element.lat = loc_lat
						updated_element.lng = loc_lng
						updated_element.type = plan_type
						updated_element.address = plan_address
						updated_element.plan_checked_in = checkIn ? 1 : 0
						updated_element.plan_date = plan_date
						updated_state[matchValue] = updated_element
						markers_list = [...updated_state]

						action_marker.setVisible(false)

						addMarkerProcess(
							plan_id,
							loc_lat,
							loc_lng,
							icon_image,
							plan_title,
							0,
							1
						)
						toastr.success('Successfully updated plan')
						$('#plan_id').val('')

						$('.action_button').css('cursor', 'pointer')
						$('.action_button').removeAttr('disabled')
					} else {
						let plan_date = event_date ?? ''
						if (plan_date && event_time) {
							plan_date = `${plan_date} ${event_time}`
						}

						const dataS = {
							id: response.id,
							title: plan_title,
							lat: loc_lat,
							lng: loc_lng,
							type: plan_type,
							address: plan_address,
							plan_checked_in: checkIn ? 1 : 0,
							plan_date: plan_date,
							schedule_linked: response.flag,
						}

						markers_list.push(dataS)
						addMarkerProcess(
							response.id,
							loc_lat,
							loc_lng,
							icon_image,
							plan_title,
							0,
							1
						)
						action_marker.setVisible(false)
						toastr.success('Successfully added plan')
						$('#plan_id').val('')
					}

					planStepper.reset()

					$('#schedule_flag').val(0)
					$('#advance_check').val(1)
					$('#advanced_form').trigger('reset')
					$('#date-content').hide()
					$('#schedule-content').hide()

					$('#plan_address').prop('disabled', true)
					$('#btn-plan').html('Add')
					getPlansList()

					$('#form-plan').trigger('reset')
					$('#btn-plan').css('cursor', 'pointer')
					$('#btn-plan').removeAttr('disabled')
				},
				error: function (jqXHR, textStatus, errorThrown) {
					toastr.error('A system error has been encountered. Please try again')

					$('#btn-plan').css('cursor', 'pointer')
					$('#btn-plan').removeAttr('disabled')
				},
			})
		}
	}, // Do not change code below
	errorPlacement: function (error, element) {
		error.insertAfter(element.parent())
	},
})

function remove_plan(id, schedule_linked, schedule_id) {
	const matchValue = markers_list.findIndex(item => item.id == id)

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
				url: SITE + 'ajaxfiles/plan/delete_plan.php',
				data: {
					id: id,
					schedule_linked: schedule_linked,
					schedule_id: schedule_id,
				},
				dataType: 'json',
				success: function (response) {
					DeleteMarker(id)
					markers_list.splice(matchValue, 1)
					toastr.success(response.message)
					$('#plan_' + id + '').remove()
				},
				error: function (jqXHR, textStatus, errorThrown) {
					toastr.error(jqXHR.responseJSON)
				},
			})
		}
	)
}

$('#advanced_form').validate({
	submitHandler: function (form) {
		$('#advance_check').val(0)
		$('#advanced_popup').modal('hide')
		$('#btn-plan').trigger('click')
	},
})
