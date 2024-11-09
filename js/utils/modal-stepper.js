class ModalStepper {
	$rootNode
	$steps
	$stepError

	$nextBtns
	$backBtn
	$dependBtns

	$opened

	onSubmit

	constructor(rootNode) {
		if (!rootNode) {
			throw new Error('Root node for stepper should be present.')
		}

		this.$rootNode = $(rootNode)
		this.$steps = $(this.$rootNode).find('[data-step]')

		this.$stepError = $(this.$rootNode).find('.modal-step-error')

		this.$nextBtns = $(this.$rootNode).find('.modal-btn-next')
		this.$backBtn = $(this.$rootNode).find('.modal-btn-back')
		this.$dependBtns = $(this.$rootNode).find('.modal-btn-sub')

		this.$opened = $(this.$steps)[0]
	}

	init() {
		$(this.$steps).each(function () {
			if ($(this).attr('data-step') !== '1') {
				$(this).hide()
			}
		})

		$(this.$nextBtns).on('click', event => {
			if (
				$(event.currentTarget).attr('type') === 'checkbox' &&
				$(event.currentTarget).is(':checked')
			) {
				$(this.$opened)
					.find('.option-checkbox')
					.not(event.currentTarget)
					.prop('checked', false)

				this.hideSubfield()

				this.moveNext()
			} else {
				event.preventDefault()

				if ($(event.currentTarget).attr('type') === 'checkbox') {
					this.moveNext()
					return
				}

				if (this.validate()) {
					this.moveNext()
				}
			}
		})

		$(this.$backBtn).on('click', event => {
			event.preventDefault()
			this.moveBack()
		})

		$(this.$dependBtns).on('click', event => {
			if ($(event.currentTarget).attr('type') !== 'checkbox') {
				return
			}
			$(this.$stepError).fadeOut()

			$(this.$opened)
				.find('.option-checkbox')
				.not(event.currentTarget)
				.prop('checked', false)

			if ($(event.currentTarget).is(':checked')) {
				this.showSubfield()
			} else {
				this.hideSubfield()
			}
		})

		$(this.$rootNode)
			.find('.modal-step-required')
			.on('input change', event => {
				$(event.currentTarget).removeClass('modal-step-invalid')
				$(event.currentTarget).next('label').removeClass('modal-step-invalid')
			})
	}

	setOnSubmit(callback) {
		this.onSubmit = callback
		return this
	}

	open() {
		$(this.$rootNode).modal('show')
	}

	close() {
		$(this.$rootNode).modal('hide')
	}

	reset() {
		this.onSubmit = null

		$(this.$stepError).hide()

		$(this.$steps).each(function () {
			if (parseInt($(this).attr('data-step')) !== 1) {
				$(this).hide()
			}
		})

		$(this.$backBtn).hide()

		this.$opened = $(this.$steps)[0]
		$(this.$opened).show()
		// $(this.$dependBtns).hide()

		this.hideSubfield(true)
		$(this.$rootNode).find('form').trigger('reset')
		$(this.$rootNode).find('input[type="file"]').trigger('change')
	}

	moveToStep(index) {
		$(this.$steps).each(function () {
			if (parseInt($(this).attr('data-step')) !== index) {
				$(this).hide()
			}
		})

		if (index === 1) {
			$(this.$backBtn).hide()
		}

		this.$opened = $(this.$steps)[index - 1]
		$(this.$opened).show()
	}

	moveNext() {
		$(this.$stepError).hide()
		$(this.$opened).hide()

		const index = +$(this.$opened).attr('data-step')
		const nextIndex = index + 1

		if (nextIndex > this.$steps.length) {
			if (this.onSubmit && typeof this.onSubmit === 'function') {
				this.onSubmit()
				this.close()
				return
			}

			$(this.$rootNode).find('form').trigger('submit')

			return
		}

		this.$opened = $(this.$rootNode).find(`[data-step='${nextIndex}']`)

		if ($(this.$backBtn).css('display') == 'none') {
			$(this.$backBtn).show()
		}

		$(this.$opened).fadeIn()
	}

	moveBack() {
		$(this.$stepError).hide()
		$(this.$opened).hide()

		const index = +$(this.$opened).attr('data-step')
		const prevIndex = index - 1

		this.$opened = $(this.$rootNode).find(`[data-step='${prevIndex}']`)

		if (prevIndex === 1) $(this.$backBtn).hide()

		$(this.$opened).fadeIn()
	}

	validate() {
		if (!$(this.$opened).find('.option-checkbox').is(':checked')) {
			$(this.$stepError).fadeIn()

			return false
		}

		if (!$(this.$opened).find('.modal-btn-sub').is(':checked')) {
			return true
		}

		const fieldsForValidation = $(this.$opened).find('.modal-step-required')

		if (fieldsForValidation.length === 0) {
			$(this.$stepError).fadeOut()
			return true
		}

		let isValid = true

		$(fieldsForValidation).each(index => {
			const inputEl = fieldsForValidation[index]

			if (parseInt($(inputEl).attr('data-no-validate')) === 1) {
				return
			}

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

	showSubfield() {
		const subfield = $(this.$opened).find('.modal-step-sub')

		if (!subfield) return

		$(subfield).fadeIn()
	}

	hideSubfield(all = false) {
		const base = all ? this.$rootNode : this.$opened

		const subfield = $(base).find('.modal-step-sub')

		if (!subfield) return

		$(subfield).fadeOut()
	}

	prefill(data) {
		this.reset()

		Object.entries(data).forEach(([selector, val]) => {
			const passInput = $(this.$rootNode).find(selector)

			if (!passInput) return

			if ($(passInput).attr('type') === 'file') {
				if (val) {
					$(passInput).next('label').find('span').text(val)
					$(passInput).attr('data-file', val)
				}
				$(passInput).attr('data-no-validate', 1)

				return
			}

			if ($(passInput).attr('type') === 'checkbox') {
				if (val) {
					$(passInput).prop('checked', val)
					if (!$(passInput).hasClass('modal-btn-sub')) {
						return
					}

					const sub = $(passInput)
						.closest('[data-step]')
						.find('.modal-step-sub')

					if (sub) $(sub).show()
				}
			}

			$(passInput).val(val)
		})
	}
}
