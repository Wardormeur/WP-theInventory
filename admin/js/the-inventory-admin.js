(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function($) {
		 $('.new-prop').on('click', function(){
			 jQuery.post(ajaxurl, { action: 'add_propertyInstance_template'}, function(response) {
				 $('.properties').append(response);
				 $('pods-form-ui-field-type-select2').select2();
			 });
		 });

		 $('.save-prop').on('click', function(event){
			 event.preventDefault();
			 var propertyInstance_id = $('.property-box input[name="edit_propertyInstance_id"]').val();
			 var value = $('.property-box .pods-form-ui-field-name-value').val();
			 jQuery.post(ajaxurl, { action: 'save_property',
				 property : $('.property-box input.pods-form-ui-field-name-property').val(),
				 propertyInstance_id: propertyInstance_id,
				 value: value,
				 product_id :$('input[name="_pods_id"]').val() },
				 function(response) {
					 if(propertyInstance_id){
						 $('input[name="propertyInstance_id"][value="' + propertyInstance_id + '"]')
						 .parent()
						 .find('input[name="value"]').val(value);
					 }else{
						 var fieldPath = '.properties>div.pods-form-fields:last';
						 $(fieldPath).after(response);
						 $(fieldPath).find('.edit-prop').on('click', function(event){
							 edit_prop_cb(event.target);
						 });
						 $(fieldPath).find('.delete-prop').on('click', function(event){
							 delete_prop_cb(event.target);
						 });
					 }
					 $('.property-box .pods-form-ui-field-name-value').val('');
	 				 $('.property-box input[name="edit_propertyInstance_id"]').val('');

		 });
	 });

	 $('.edit-prop').on('click', function(event){
		 edit_prop_cb(event.target);
	 });

	 $('.delete-prop').on('click', function(event){
		 delete_prop_cb(event.target);
 	 });

	 function edit_prop_cb(target) {
		 var src = $(target).parent();
		 $('.property-box input.pods-form-ui-field-name-property').val($(src).find('input[name="property_id"]').val()).trigger('change');
		 $('.property-box input.pods-form-ui-field-name-value').val($(src).find('input[name="value"]').val()).focus();
		 $('.property-box input[name="edit_propertyInstance_id"]').val($(src).find('input[name="propertyInstance_id"]').val());
	 }

	 function delete_prop_cb(target) {
		 var src = $(target).parent();
		 jQuery.post(ajaxurl, {
			 action: 'delete_property',
			 id : $(src).find('input[name="propertyInstance_id"]').val(),
			 product_id :$('input[name="_pods_id"]').val() },
			 function(response) {
				 src.remove();
		});
	 }

 });
})( jQuery );
