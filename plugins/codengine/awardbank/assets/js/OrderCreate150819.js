$("#orderform").submit(function (e) {
	e.preventDefault();
	if (validateForm('#orderform', '.business_name,.attn_name,.unit_number,.floor,.not-required,:hidden,:checkbox') === true) {
		var inputs = $('#orderform :input');
		var data = {};
		inputs.each(function () {
			data[this.name] = $(this).val();
		});
		requestFactory('OrderCreate::onCreateOrder', data, ['#orderplace-htmltarget', '#cartnavhtmltarget', '#cart-dropdown']);
	}
});