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

				let reservation_flag = ''

				if (item.reservation_flag == 1) {
					reservation_flag = `<span class="schedule_linked"><i class="fa fa-building" aria-hidden="true" title="Checked In"></i></span>`
				}

				let transportation_flag = ''

				if (item.transportation_flag == 1) {
					transportation_flag = `<span class="schedule_linked"><i class="fa fa-car" aria-hidden="true" title="Transportation"></i></span>`
				}

				items += `<div class="itinerary-field" id="plan_${item.id_plan}">
				<div class="itinerary-field__content">
				<p class="itinerary-field__icons" style="display:block">${schedule_linked} ${date_icon} ${checkin_flag} ${reservation_flag} ${transportation_flag}</p>				
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

function fillReservationData()
{

	var service = new google.maps.places.PlacesService(map);

	var location = {
		lat: parseFloat($("#location_to_lat").val()),
		lng: parseFloat($("#location_to_lng").val())
	};


	var request = {
		location: location,
		radius: '50',
		types: ['point_of_interest']
	};

	service.nearbySearch(request, function(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			var place = results[0];
			service.getDetails({
				placeId: place.place_id,
				fields: ['name', 'rating', 'formatted_phone_number', 'opening_hours', 'opening_hours.weekday_text', 'website']
			}, function(placeDetails, status) {

				let detailsContent = `

                                 ${place.name ?? ''} <span> ${place.vicinity ?? ''} </span><br>
                                `;
				if (placeDetails.formatted_phone_number) {
					if (placeDetails.formatted_phone_number !== 'N/A') {
						// Sprawdzanie, czy użytkownik jest na urządzeniu mobilnym
						const isMobile = /Mobi|Android/i.test(navigator.userAgent);

						// Wybór odpowiedniego linku w zależności od urządzenia
						const phoneLink = isMobile
							? 'tel:' + placeDetails.formatted_phone_number
							: 'skype:' + placeDetails.formatted_phone_number + '?call';

						detailsContent += '<b>Phone: </b><a href="' + phoneLink + '">' + placeDetails.formatted_phone_number + '</a><br>';
					} else {
						detailsContent += '<b>Phone: </b> N/A<br>';
					}
				}

				if (placeDetails.opening_hours) {
					var openingHours = placeDetails.opening_hours;

					if (openingHours.weekday_text) {
						detailsContent += '<b>Opening Hours:</b><br><ul>';
						for (var i = 0; i < openingHours.weekday_text.length; i++) {
							detailsContent += '<li>' + openingHours.weekday_text[i] + '</li>';
						}
						detailsContent += '</ul>';
					}
				} else {
					detailsContent += '<b>Status: </b> Closed<br>';
				}

				if (placeDetails.website) {
					detailsContent += '<b>Website: </b> <a href="' + placeDetails.website + '" target="_blank">' + placeDetails.website + '</a><br>';
				}

				detailsContent += "<br><br>You can come back after and confirm your reservations are set.";


				// Update the content of the info window with the details
				document.getElementById('info-content-reservation').innerHTML = detailsContent;



			});

		} else {

		}
	});
}

function fillTransportationData()
{

	var service = new google.maps.places.PlacesService(map);

	var location = {
		lat: parseFloat($("#location_to_lat").val()),
		lng: parseFloat($("#location_to_lng").val())
	};


	const requestCarRental = {
		location: location,
		radius: 32000,
		type: 'car_rental'
	};

	const requestTaxiStand = {
		location: location,
		radius: 32000,
		type: 'taxi_stand'
	};

	// Funkcja do przetwarzania wyników i aktualizacji zawartości
	function processResults(results) {
		results.forEach(function(result) {
			service.getDetails({
				placeId: result.place_id,
				fields: ['name', 'formatted_phone_number', 'website']
			}, function(placeDetails, status) {
				if (status === google.maps.places.PlacesServiceStatus.OK) {
					let phoneDisplay;
					if (placeDetails.formatted_phone_number && placeDetails.formatted_phone_number !== 'N/A') {
						const isMobile = /Mobi|Android/i.test(navigator.userAgent);
						const phoneLink = isMobile
							? 'tel:' + placeDetails.formatted_phone_number
							: 'skype:' + placeDetails.formatted_phone_number + '?call';

						phoneDisplay = `<a href="${phoneLink}">${placeDetails.formatted_phone_number}</a>`;
					} else {
						phoneDisplay = 'N/A';
					}

					let companyDetails = `
						<b>Name:</b> ${placeDetails.name ?? 'N/A'}<br>
						<b>Phone:</b> ${phoneDisplay}<br>
						<b>Website:</b> ${placeDetails.website ? `<a href="${placeDetails.website}" target="_blank">${placeDetails.website}</a>` : 'N/A'}<br>
						<br><br>
					`;

					document.getElementById('info-content-transportation').innerHTML += companyDetails;
				}
			});
		});
	}

	// Wykonanie zapytań równolegle
	service.nearbySearch(requestCarRental, function(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			processResults(results);
		} else {
			console.error('Error with car rental search: ' + status);
		}
	});

	service.nearbySearch(requestTaxiStand, function(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			processResults(results);
		} else {
			console.error('Error with taxi stand search: ' + status);
		}
	});

	/*
	var request = {
		location: location,
		radius: 32000,
		type: 'car_rental'
	};

	service.nearbySearch(request, function(results, status) {
		if (status === google.maps.places.PlacesServiceStatus.OK) {
			document.getElementById('info-content-transportation').innerHTML = '';
			results.forEach(function(result) {
				service.getDetails({
					placeId: result.place_id,
					fields: ['name', 'formatted_phone_number', 'website']
				}, function(placeDetails, status) {
					if (status === google.maps.places.PlacesServiceStatus.OK) {
						// Sprawdzanie, czy numer telefonu jest dostępny i nie jest "N/A"
						let phoneDisplay;
						if (placeDetails.formatted_phone_number && placeDetails.formatted_phone_number !== 'N/A') {
							const isMobile = /Mobi|Android/i.test(navigator.userAgent);
							const phoneLink = isMobile
								? 'tel:' + placeDetails.formatted_phone_number
								: 'skype:' + placeDetails.formatted_phone_number + '?call';

							phoneDisplay = `<a href="${phoneLink}">${placeDetails.formatted_phone_number}</a>`;
						} else {
							phoneDisplay = 'N/A';
						}

						// Składanie szczegółów firmy z dynamicznym numerem telefonu
						let companyDetails = `
                <b>Name:</b> ${placeDetails.name ?? 'N/A'}<br>
                <b>Phone:</b> ${phoneDisplay}<br>
                <b>Website:</b> ${placeDetails.website ? `<a href="${placeDetails.website}" target="_blank">${placeDetails.website}</a>` : 'N/A'}<br>
                <br><br>
            `;

						document.getElementById('info-content-transportation').innerHTML += companyDetails;
					}
				});
			});
		} else {
			console.error('Error occurred: ' + status);
		}
	});

	 */

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
				let reservation = response['reservation_flag']
				let transportation = response['transportation_flag']


				if (reservation == 1) {
					reservationField = '#plan_reservation_yes';
				} else if (reservation == 0) {
					reservationField = '#plan_reservation_not_required';
				} else if (reservation == -1) {
					reservationField = '#plan_reservation_no';
					fillReservationData();
				}


				if (transportation == 1) {
					transportationField = '#plan_transportation_yes';
				} else if (transportation == 0) {
					transportationField = '#plan_transportation_not_required';
				} else if (transportation == -1) {
					transportationField = '#plan_transportation_no';
					fillTransportationData();
				}

				let stepperPrefill = {
					[checkinVal ? '#plan_checkin_yes' : '#plan_checkin_no']: true,
					[dateAddedVal ? '#plan_date_yes' : '#plan_date_no']: true,
					[reservationField]:true,
					[transportationField]:true,
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

			var reservation = $('#plan_reservation_yes').is(':checked') ? 1 : -1;
			if (reservation == -1) {
				reservation = $('#plan_reservation_not_required').is(':checked') ? 0 : -1;
			}

			var transportation = $('#plan_transportation_yes').is(':checked') ? 1 : -1;
			if (transportation == -1) {
				transportation = $('#plan_transportation_not_required').is(':checked') ? 0 : -1;
			}


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
					checkIn +
				'&reservation=' + reservation +
				'&transportation=' + transportation,

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
						updated_element.reservation = reservation
						updated_element.transportation = transportation

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
							reservation: reservation,
							transportation: transportation
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
