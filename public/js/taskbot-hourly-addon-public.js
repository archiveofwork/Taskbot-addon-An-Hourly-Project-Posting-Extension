
( function ( $ ) {
    'use strict';
	jQuery(document).ready(function($){
	 	// Project complete action
		jQuery('.tb_project_completed').on('click', function(){
			var _this       	= jQuery(this);
			var $trigger 		= jQuery(".tk-projectsstatus_option > a");
			if($trigger !== event.target && !$trigger.has(event.target).length){
				jQuery(".tk-contract-list").slideUp("fast");
			}
			var proposal_id    	= _this.data('proposal_id');
			var title     		= _this.data('title');
			var counter 		= Math.floor((Math.random() * 999999) + 999);
			var load_task 		= wp.template('load-completed_project_form');
			var data 			= {counter: counter, proposal_id: proposal_id};
			load_task 			= load_task(data);

			jQuery('#tb_projectcomplete_form').html(load_task);
			jQuery('#tb_project_ratingtitle').html(title);
			jQuery('#tb_project_completetask').modal('show');
			tb_rating_options();
		});


		//Submit time slots
		jQuery('.tb_send_timeslot').on('click',function () {
			let _this           	= jQuery(this);
			let id     				= _this.data('id');
			let transaction_id     	= _this.data('transaction_id');
			jQuery.confirm({
				icon: 'fas fa-file-invoice',
				title: hourly_scripts_vars.hourly_invoice_title,
				content: hourly_scripts_vars.hourly_invoice_detail,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.yes,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'			: 'taskbot_submite_hourly_activities',
									'security'			: scripts_vars.ajax_nonce,
									'id'				: id,
									'transaction_id'	: transaction_id
								},
								dataType: "json",
								success: function (response) {
									jQuery('.tb-preloader-section').remove();
									if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.reload();
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.no_btntext,
						btnClass: 'tb-btnvthree',
					}
				}
			});
		});

		// Time slot model
		jQuery('.tb_add_timeslot').on('click',function () {
			let _this           = jQuery(this);
			let time_id     	= _this.data('time');
			let formated_date   = _this.data('formated_date');
			let timeslot_date   = _this.data('timeslot_date');
			let time_string   	= _this.data('time_string');
			let details   		= jQuery('#tb_'+timeslot_date).text();
			jQuery('#tb_timeslot_date_format').html(formated_date);
			jQuery('#tb_form_time_id').val(time_id);
			if (typeof time_string !== 'undefined' && time_string !== '') {
				jQuery('#tb-working-time').val(time_string);
			}
			jQuery('#tb_timeslot_details').val(details);
			jQuery('#tb_form_date').val(timeslot_date);
			jQuery('#tb_workinghours').modal('show');
		});

		// Decline hourly timeslot
		jQuery('.tb_decline_hourly').on('click',function () {
			let _this           	= jQuery(this);
			let detail   			= jQuery('#tb_decline_detail').val();
			let transaction_id     	= _this.data('transaction_id');
			let proposal_id     	= _this.data('proposal_id');
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'				: 'taskbot_update_hourly_decline',
					'security'				: scripts_vars.ajax_nonce,
					'detail'				: detail,
					'transaction_id'		: transaction_id,
					'proposal_id'			: proposal_id,
				},
				dataType: "json",
				success: function (response) {
				jQuery('.tb-preloader-section').remove();

				if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});

		// Update timeslot
		jQuery('.tb_timetracking_btn').on('click',function () {
			let _serialized   	= jQuery('#tb_timetracking_form').serialize();
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'taskbot_update_hourly_timetracking',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: _serialized,
				},
				dataType: "json",
				success: function (response) {
				jQuery('.tb-preloader-section').remove();

				if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});
		
		// Submit proposal 
		jQuery('.tb_hire_job_proposal').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			jQuery.confirm({
				icon: 'fas fa-bullhorn',
				title: scripts_vars.hiring_title,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
								},
								dataType: "json",
								success: function (response) {
								jQuery('.tb-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'tb-btnvthree'
					}
				}
			});
			
		});

		// Hired hourly proposal 
		jQuery('.tb_hire_hourly_proposal').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			jQuery.confirm({
				icon: 'fas fa-bullhorn',
				title: scripts_vars.hiring_title,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
								},
								dataType: "json",
								success: function (response) {
								jQuery('.tb-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'tb-btnvthree'
					}
				}
			});
			
		});

		// Hired hourly proposal 
		jQuery('.tb_hourly_slot_payment').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');
			jQuery.confirm({
				icon: 'fas fa-bullhorn',
				title: scripts_vars.hiring_title,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
								jQuery('.tb-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'tb-btnvthree'
					}
				}
			});
			
		});

		// hired project with wallet
		$(document).on('click', '.tb_hourly_proposal_hiring', function (e) {
			let _this			= $(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');
			jQuery.confirm({
				icon: 'fas fa-bullhorn',
				title: scripts_vars.wallet_account,
				content: scripts_vars.wallet_account_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.btn_with_wallet,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id,
									'wallet'	: true
								},
								dataType: "json",
								success: function (response) {
									jQuery('.tb-preloader-section').remove();

									if (response.type === 'success') {
										window.location.replace(response.checkout_url);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btn_without_wallet,
						btnClass: 'tb-btnvthree',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
									jQuery('.tb-preloader-section').remove();

									if (response.type === 'success') {
										window.location.replace(response.checkout_url);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					}
				}
			});
		});

		// Approved hourly proposal 
		jQuery('.tb_approve_hours').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');
			jQuery.confirm({
				icon: 'fas fa-bullhorn',
				title: hourly_scripts_vars.approved_time_title,
				content: hourly_scripts_vars.approved_time_detail,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'tb-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'taskbot_approved_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
								jQuery('.tb-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.reload();
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'tb-btnvthree'
					}
				}
			});
			
		});
	});
} ( jQuery ) );