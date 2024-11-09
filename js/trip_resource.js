'use strict'

class ResourcePopup extends ModalStepper {
	constructor(rootNode) {
		super(rootNode)
		this.init()

		this.setOnSubmit(() => {
			action_marker.setVisible(false)

			let resourceId = $('#resource_id').val()
			let url = SITE + 'ajaxfiles/trip_resource.php'
			let action = 'store'

			if (resourceId) {
				url += '/' + resourceId
				action = 'update'
			}

			$('#resource_add').css('cursor', 'not-allowed')
			$('#resource_add').attr('disabled', true)

			const formData = new FormData(document.getElementById('resource-form'))
			formData.append('resource_address', $('#resource_address').val())

			$.ajax({
				url: url,
				method: 'post',
				processData: false,
				contentType: false,
				cache: false,
				data: formData,
				success: function (response) {
					if (action === 'store') {
						toastr.success('Successfully added resource')
						resourceId = response.data.resource_id
					} else {
						toastr.success('Successfully updated resource')
						$('#resource_add').html('Add')
					}

					$('#resource_add').css('cursor', 'pointer')
					$('#resource_add').removeAttr('disabled')

					const lat = $('#location_to_lat').val()
					const lng = $('#location_to_lng').val()

					const type = $('#resource_type').val()
					const title = $('#resource_title').val()

					const icon = getResourceIcon(type)

					addMarkerProcess(resourceId, lat, lng, icon, title)

					resourcePopup.reset()
					$('#resource_trip_id').val(TRIP_ID)
					$('#resource_custom').val(1)

					$('#resource_address').val('')
					$('#resource_id').val('')

					getResourceList()
				},
				error: function (jqXHR, textStatus, errorThrown) {
					toastr.error('A system error has been encountered. Please try again')
					resourcePopup.reset()

					$('#resource_add').css('cursor', 'pointer')
					$('#resource_add').removeAttr('disabled')
				},
			})
		})
	}

	reset() {
		this.$opened = $(this.$steps)[0]
		$(this.$opened).show()
		$(this.$rootNode).find('form').trigger('reset')
	}

	validate() {
		const fieldsForValidation = $(this.$opened).find('.modal-step-required')

		if (fieldsForValidation.length === 0) {
			$(this.$stepError).fadeOut()
			return true
		}

		let isValid = true

		$(fieldsForValidation).each(index => {
			const inputEl = fieldsForValidation[index]

			if (!$(fieldsForValidation[index]).val()) {
				$(fieldsForValidation[index]).addClass('modal-step-invalid')
				$(fieldsForValidation[index])
					.next('label')
					.addClass('modal-step-invalid')
				isValid = false
				return
			} else {
				$(fieldsForValidation[index]).removeClass('modal-step-invalid')
				$(fieldsForValidation[index])
					.next('label')
					.removeClass('modal-step-invalid')
			}
		})

		if (!isValid) {
			$(this.$stepError).fadeIn()
		} else {
			$(this.$stepError).fadeOut()
		}

		return isValid
	}
}

const resourcePopup = new ResourcePopup('#resource-popup')
const addressInput = $('#resource_address')

$(addressInput).on('input', event => {
	$(addressInput).removeClass('invalid-resource')
})

$('#resource_add').on('click', event => {
	event.preventDefault()
	if (!$(addressInput).val()) {
		$(addressInput).addClass('invalid-resource')
		return
	}

	resourcePopup.open()
})

getResourceList()

function setOnImportClick(node) {
	$(node).on('click', event => {
		event.preventDefault()
		const button = $(event.currentTarget)
		const title = $(button).attr('data-title')
		const address = $(button).attr('data-address')
		const type = $(button).attr('data-type')
		const lat = $(button).attr('data-lat')
		const lng = $(button).attr('data-lng')

		importResource({ address, title, lat, lng, type })
	})
}

function importResource({ address, title, lat, lng, type }) {
	let url = SITE + 'ajaxfiles/trip_resource.php'

	$('#resource_add, .btn-import-resource').css('cursor', 'not-allowed')
	$('#resource_add, .btn-import-resource').attr('disabled', true)

	const formData = new FormData()
	formData.append('resource_trip_id', TRIP_ID)
	formData.append('resource_address', address)
	formData.append('resource_title', title)
	formData.append('resource_lat', lat)
	formData.append('resource_lng', lng)
	formData.append('resource_type', type)
	formData.append('resource_custom', '0')

	$.ajax({
		url: url,
		method: 'post',
		processData: false,
		contentType: false,
		cache: false,
		data: formData,
		success: function (response) {
			toastr.success('Successfully added resource')
			let resourceId = response.data.resource_id

			$('#resource_add, .btn-import-resource').css('cursor', 'pointer')
			$('#resource_add, .btn-import-resource').removeAttr('disabled', true)
			infowindow.close()

			const icon = getResourceIcon(type)

			addMarkerProcess(resourceId, lat, lng, icon, title)

			getResourceList()
		},
		error: function (jqXHR, textStatus, errorThrown) {
			toastr.error('A system error has been encountered. Please try again')

			$('#resource_add, .btn-import-resource').css('cursor', 'pointer')
			$('#resource_add, .btn-import-resource').removeAttr('disabled', true)
		},
	})
}

function removeResource(res_id) {
	const matchValue = markers_list.findIndex(item => item.id == res_id)

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
				type: 'DELETE',
				url: SITE + 'ajaxfiles/trip_resource.php/' + res_id,
				dataType: 'json',
				success: function (response) {
					deleteMarker(res_id)
					markers_list.splice(matchValue, 1)
					toastr.success('Resource was removed.')
					$('#resource_' + res_id).remove()
				},
				error: function (jqXHR, textStatus, errorThrown) {
					toastr.error(jqXHR.responseJSON)
				},
			})
		}
	)
}

function editResource(id) {
	$('.resource-list__action-btn').css('cursor', 'not-allowed')
	$('.resource-list__action-btn').attr('disabled', true)

	$.ajax({
		url: SITE + 'ajaxfiles/trip_resource.php/' + id,
		type: 'GET',
		dataType: 'json',
		success: function (response) {
			response = response?.data?.resource

			if (response) {
				$('#resource_add').prop('disabled', false)
				var icon_image = getResourceIcon(response['type'])
				$('#resource_id').val(id)
				deleteMarker(id)

				const iconSourcePath = SITE + 'images/map-icons/'

				changeMarkerPosition(
					response['lat'],
					response['lng'],
					icon_image,
					'bounce',
					iconSourcePath
				)

				$('#resource_add').html('Update')
				$('#resource_address').val(response['address'])
				$('#resource_address').focus()
				$('body').scrollTop(0)

				$('#location_to_lat').val(response['lat'])
				$('#location_to_lng').val(response['lng'])

				let stepperPrefill = {
					'#resource_type': response['type'],
					'#resource_title': response['title'],
				}

				resourcePopup.prefill(stepperPrefill)
			}
		},
	})
}

function getResourceList() {
	var items = ''
	try {
		$.getJSON(
			SITE + 'ajaxfiles/trip_resource.php?tripId=' + TRIP_ID,
			function (response) {
				$.each(response.data, function (index, item) {
					let typeContent = ''

					if (resourceMap[item.type]) {
						typeContent = `<span>${resourceMap[item.type]}</span>`
					}

					items += `<li class="itinerary-field" id="resource_${item.id}">
					<div class="itinerary-field__content">
					<h4 id="resource_name">${item.title}</h4>
					<p> <i class="fa fa-map-marker" aria-hidden="true"></i>${item.address}</p>
					<div class="resource-list__type">
							<img class="resource-list__icon" src="${SITE}images/map-icons/${getResourceIcon(item.type)}" id="resource_type_icon" />
							${typeContent}
					</div>   
					</div>
					<div class="itinerary-field__actions">
							<button id="update" type="button" onclick="editResource(${item.id})" class="itinerary-field__button itinerary-field__button_edit edit_note" title="Edit Note" value="1">
									<i class="fa fa-pencil"></i>
							</button>
							<button id="delete" type="button" onclick="removeResource(${item.id})" class="itinerary-field__button itinerary-field__button_delete" title="Delete Note" value="1">
									<i class="fa fa-trash"></i>
							</button>
					</div>
			</li>`
				})

				$('#resource-list').html(items)
			}
		).fail(function (jqxhr, textStatus, error) {
			var err = textStatus + ', ' + error
			alert(err)
		})
	} catch (e) {
		console.log('Error ', e)
	}
}

const resourceMap = {
	lodging: 'Hotel/Motel',
	police: 'Police',
	hospital: 'Hospital',
	airport: 'Airport',
	parking: 'Parking',
	subway_station: 'Subway Station',
	gas_station: 'Gas Station',
	taxi_stand: 'Taxi Stand',
	university: 'University',
	atm: 'Atm',
	library: 'Library',
	museum: 'Museum',
	church: 'Church',
	train_station: 'Metro Station',
	park: 'Park',
	pharmacy: 'Pharmacy',
	covid_testing_center: 'Covid Testing Center',
	ev_charging_station: 'Electric Car Charging Station',
	shopping_mall: 'Shopping Mall',
	golf_course: 'Golf Course',
	restaurant: 'Restaurant',
	cafe: 'Cafe',
	'historical site': 'Historical Site',
}
