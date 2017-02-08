(function () {
	"use strict";

	function controller(UtilService, $locale, cartDataService, $window) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.editPersonalInfo = editPersonalInfo;
		vm.checkout = checkout;
		vm.stripejs = stripejs;
		vm.cvvPattern = new RegExp("[0-9]{3}", "g");
		var datetime = $locale.DATETIME_FORMATS;


		init();

		function init() {
			setMonths();
			setYears();

			configureStripe();
		}

		function configureStripe() {
			Stripe.setPublishableKey('pk_test_p1DPyiicE2IerEV676oj5t89');

			vm.handler = StripeCheckout.configure({
				key: 'pk_test_p1DPyiicE2IerEV676oj5t89',
				image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
				locale: 'auto',
				token: function(token) {
					var cartReceiveToken = new cartDataService.CartReceiveToken;
					cartReceiveToken.token = angular.copy(token);
					cartReceiveToken.$save({
						cartId: vm.cart.id
					}).then(function(dataSaved) {
						$window.location.href = currentHost()+'/order/success/'+vm.cart.id
					}, function (err) {
						//TODO manage errors
						vm.errors = true;
						console.log(err);
					});
				}
			});

		}

		function setMonths() {
			vm.months = [];
			var monthNumber = 1;
			datetime.MONTH.forEach(function(month) {
				var object = {
					month: monthNumber,
					name: month
				}
				vm.months.push(object);
				monthNumber++;
			})
		}

		function setYears() {
			var year = new Date().getFullYear();
			vm.years = [];
			for(var i = 0; i < 8; i++) {
				vm.years.push(year+i);
			}
		}

		function editPersonalInfo() {
			vm.state.state = 2;
		}

		function checkout() {
			console.log('checkout!');
			vm.handler.open({
				name: '',
				description: 'Order Nº '+vm.cart.id,
				zipCode: true,
				locale: 'auto',
				currency: 'eur',
				image: '/imgs/logo.png',
				amount: vm.cart.subtotal*100
			});
		}

		function stripejs() {
			Stripe.card.createToken({
				number: vm.payment.card_number,
				cvc: vm.payment.cvv,
				exp_month: vm.payment.month,
				exp_year: vm.payment.year,
				address_zip: '15177'
			}, stripeResponseHandler);
		}

		function stripeResponseHandler(status, response) {

			console.log(response);
			return;

			// Grab the form:
			var $form = $('#payment-form');

			if (response.error) { // Problem!

				// Show the errors on the form
				$form.find('.payment-errors').text(response.error.message);
				$form.find('button').prop('disabled', false); // Re-enable submission

			} else { // Token was created!

				// Get the token ID:
				var token = response.id;

				// Insert the token into the form so it gets submitted to the server:
				$form.append($('<input type="hidden" name="stripeToken" />').val(token));

				// Submit the form:
				$form.get(0).submit();

			}
		}

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/payment-methods/payment-methods.html',
		controller: controller,
		controllerAs: 'paymentMethodsCtrl',
		bindings: {
			state: '<',
			cart: '<'
		}
	}

	angular
		.module('todevise')
		.component('paymentMethods', component);

}());