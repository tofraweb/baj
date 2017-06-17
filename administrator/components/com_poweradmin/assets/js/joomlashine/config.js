(function ($) {
	$(function () {
		if ($('#params_admin_session_timer_infinite:checked').size() > 0)
			$('#params_admin_session_timer').attr('readonly', 'readonly').addClass('disabled');
		
		$('#params_admin_session_timer_infinite').change(function () {
			if (this.checked)
				$('#params_admin_session_timer').attr('readonly', 'readonly').addClass('disabled');
			else
				$('#params_admin_session_timer').removeAttr('readonly').removeClass('disabled');
		});
		
		if ($('#params_admin_session_timeout_warning_disabled:checked').size() > 0)
			$('#params_admin_session_timeout_warning').attr('readonly', 'readonly').addClass('disabled');
		
		$('#params_admin_session_timeout_warning_disabled').change(function () {
			if (this.checked)
				$('#params_admin_session_timeout_warning').attr('readonly', 'readonly').addClass('disabled');
			else
				$('#params_admin_session_timeout_warning').removeAttr('readonly').removeClass('disabled');
		});

		$('#params_search_coverage .sortable').sortable({
			axis: 'y',
			items: '.item',
			placeholder	: "ui-state-highlight",
			helper: function (event, ui){
				ui.hide();				
				return ui;
			},			
			stop: function (event, ui) {
				ui.item.show();
				var values = [];
				$('input[type="checkbox"][name*=search_coverage]').each(function () {
					values.push($(this).val());
				});

				$('#params_search_coverage_order').val(values.join(','));
			}
		});
	});
})(JoomlaShine.jQuery);