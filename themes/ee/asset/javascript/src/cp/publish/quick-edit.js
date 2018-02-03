/**
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license
 */

(function($) {

EE.cp.QuickEdit = {

	modal: $('div[rel="modal-quick-edit"]'),
	formContainer: $('.app-modal__content .col.w-12.remove-pad--right', this.modal),
	ajaxRequest: null,
	intent: null,

	intentFormUrls: {
		'quick-edit': EE.publishEdit.quickEditFormUrl,
		'add-categories': EE.publishEdit.addCategoriesFormUrl,
		'remove-categories': EE.publishEdit.removeCategoriesFormUrl
	},

	/**
	 * Opens a modal form
	 *
	 * @param {string} options Intent of modal
	 * @param {array} checked Array of jQuery objects of checked checkboxes for entries
	 * @return {void}
	 */
	openForm: function(intent, checked) {
		this.intent = intent

		var items = this._formatItems(checked)
		this._renderEntryList(items)

		this.formContainer.html('<span class="btn work">Loading</span>')
		this._loadForm(items)

		this.modal.trigger('modal:open')
	},

	/**
	 * Renders the filterable entry list React component
	 *
	 * @param {array} items Array of item objects formatted for QuickEditEntries component
	 * @return {void}
	 */
	_renderEntryList: function(items) {
		var that = this
		QuickEditEntries.render(this.modal, {
			items: items,
			entriesChanged: function(items) {
				that._loadForm(items)
			}
		})
	},

	/**
	 * Given an array of jQuery objects of checked checkboxes, returns an array
	 * of items formatter for the QuickEditEntries component
	 *
	 * @param {array} checked Array of jQuery objects of checked checkboxes for entries
	 * @return {array}
	 */
	_formatItems: function(checked) {
		return $.map(checked, function(el) {
			return {
				label: $(el).data('title'),
				value: $(el).val()
			}
		})
	},

	/**
	 * Given an array of QuickEditEntries component-formatted items, returns an
	 * array of entry IDs for the items
	 *
	 * @param {array} items Array of QuickEditEntries component-formatted items
	 * @return {array}
	 */
	_getEntryIdsFromItems: function(items) {
		return $.map(items, function(item) {
			return item.value
		})
	},

	/**
	 * Loads the modal form with the specified contents
	 *
	 * @param {array} items Array of QuickEditEntries component-formatted items
	 * @return {array}
	 */
	_loadForm: function(items) {
		if (this.ajaxRequest) {
			this.ajaxRequest.abort()
		}

		if (items.length == 0) {
			return this.modal.trigger('modal:close')
		}

		var form = $('form', this.modal)
		var params = Object.assign(
			this._getFormData(form),
			{ entryIds: this._getEntryIdsFromItems(items) }
		)

		var that = this
		this.ajaxRequest = $.ajax({
			url: this.intentFormUrls[this.intent],
			data: $.param(params),
			dataType: 'html',
			success: function(data) {
				that._bindForm(data)
			},
			error: console.error
		})
	},

	/**
	 * Given a form element, returns a hash of form input names to their values
	 *
	 * @param {jQuery object} form Form element
	 * @return {object} Hash of form input names to their values
	 */
	_getFormData: function(form) {
		var formData = {}

		$.each(form.serializeArray(), function(i, input){
			formData[input.name] = input.value
		})

		return formData
	},

	/**
	 * Binds all necessary callbacks and events when the form markup loads
	 *
	 * @param {string} data HTML of form
	 * @return {void}
	 */
	_bindForm: function(data) {
		this.formContainer.html(data)
		this._bindAddField()
		this._bindRemoveField()
		this._enableOrDisableButtons()
		SelectField.renderFields(this.formContainer)

		this.formContainer.find('.fluid-field-templates :input')
			.attr('disabled', 'disabled')

		$.fuzzyFilter()
		var that = this
		this.formContainer.find('.fluid-wrap')
			.find('.js-sorting-container .fluid-item')
			.each(function(i, item) {
				that._toggleMenuItem($(item).data('fieldName'))
			})

		$('form', this.modal).on('submit', function() {
			$.post(this.action, $(this).serialize(), function(result) {
				// Probably a validation error
				if ($.type(result) === 'string') {
					that._bindForm(result)
					return
				}

				location.reload()
			})
			return false
		})
	},

	/**
	 * Binds Fluid UI Add button
	 *
	 * @return {void}
	 */
	_bindAddField: function() {
		var that = this
		this.modal.find('.fluid-actions a[data-field-name]').click(function(e) {
			e.preventDefault()

			var wrapper = $(this).closest('.fluid-wrap'),
				fieldName = $(this).data('fieldName'),
				template = wrapper.find('.fluid-field-templates [data-field-name="'+fieldName+'"]'),
				fieldContainer = wrapper.find('.js-sorting-container')

			// Add the field
			template.appendTo(fieldContainer)
			fieldContainer.find(':input').removeAttr('disabled')
			that._toggleMenuItem(fieldName, true)

			that._enableOrDisableButtons()

			// Close Add menu
			$(this).closest('.filters')
				.find('.open')
				.removeClass('open')
				.siblings('.sub-menu')
				.hide();

			SelectField.renderFields(fieldContainer)
		})
	},

	/**
	 * Binds Fluid UI Remove button
	 *
	 * @return {void}
	 */
	_bindRemoveField: function() {
		var that = this
		this.modal.on('click', '.fluid-ctrls a.fluid-remove', function(e) {
			e.preventDefault()

			var item = $(this).closest('.fluid-item')

			that._toggleMenuItem(item.data('fieldName'), false)
			item.appendTo(that.formContainer.find('.fluid-field-templates'))

			that.formContainer.find('.fluid-field-templates :input')
				.attr('disabled', 'disabled')

			that._enableOrDisableButtons()
		})
	},

	/**
	 * Toggle visibility of field name in Fluid UI Add menu
	 *
	 * @param {string} fieldName Short name of field
	 * @param {boolean} toggle Whether or not to hide or show the item
	 * @return {void}
	 */
	_toggleMenuItem: function(fieldName, toggle) {
		this.formContainer.find('.fluid-actions a[data-field-name="'+fieldName+'"]')
			.closest('li')
			.toggleClass('hidden', toggle)
	},

	/**
	 * Enables/disables submission buttons based on the presence of fields to submit
	 *
	 * @return {void}
	 */
	_enableOrDisableButtons: function() {
		var itemCount = this.formContainer.find('.js-sorting-container .fluid-item').size(),
			buttons = this.formContainer.find('input.btn')

		if (itemCount == 0) {
			buttons.attr('disabled', 'disabled')
		} else {
			buttons.removeAttr('disabled')
		}
	}
}

})(jQuery);
