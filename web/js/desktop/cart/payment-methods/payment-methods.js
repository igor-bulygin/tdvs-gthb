(function () {
	"use strict";

	function controller(UtilService, $locale, cartDataService, $window, localStorageUtilService) {
		var vm = this;
		vm.has_error = UtilService.has_error;
		vm.editPersonalInfo = editPersonalInfo;
		vm.checkout = checkout;
		vm.cvvPattern = new RegExp("[0-9]{3}", "g");
		var datetime = $locale.DATETIME_FORMATS;
		vm.sameBilling = true;
    vm.person = person;
    vm.payWithCredit = payWithCredit;

		init();

		function init() {
			setMonths();
			setYears();

			configureStripe();
		}

		function configureStripe() {
			// Stripe.setPublishableKey(getStripeApiKey());

			function onReceiveTokenSuccess(data) {
				localStorageUtilService.removeLocalStorage('cart_id');
				$window.location.href = currentHost() + '/order/success/' + vm.cart.id;
			}

			function onReceiveTokenError(err) {
				//ToDo: Manage errors
				setSaving(false);
				vm.errors = true;
				console.log(err);
			}

			vm.handler = StripeCheckout.configure({
				key: getStripeApiKey(),
				image: '/imgs/logo_stripe.png',
				locale: _lang,
				zipCode: true,
				currency: 'eur',
				token: function(token) {
					setSaving(true);
					cartDataService.getCartToken(
						{
							token: angular.copy(token)
						},
						{
							cartId: vm.cart.id
						},
						onReceiveTokenSuccess, onReceiveTokenError);
				}
			});

		}

		function setSaving(value) {
			vm.saving = value;
		}

		function checkout(form) {
			function onSaveCartSuccess(data) {
				vm.handler.open({
					name: '',
					description: 'Order Nº ' + data.id,
					amount: data.total*100
				});
				// setSaving(true);
			}

			if(form)
				form.$submitted = true;
			if(vm.sameBilling)
				vm.cart.billing_address = Object.assign({}, vm.cart.shipping_address);
			if((form && form.$valid) || !form) {
				//POST TO API
				cartDataService.updateCart(vm.cart, {
					id: vm.cart.id
				}, onSaveCartSuccess, UtilService.onError);
				//
			}
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

    function payWithCredit(form) {

      function onReceiveTokenSuccessWithCredit(data) {
        // localStorageUtilService.removeLocalStorage('cart_id');
        // $window.location.href = currentHost() + '/order/success/' + vm.cart.id;
				// setSaving(true);
      }

      function onReceiveTokenErrorWithCredit(err) {
        // TOdo manage errors
        vm.errors = true;
        console.log(err);
      }

      function onSaveCartSuccessWithCredit(data) {

        cartDataService.getCartToken(
          {
            token: vm.cart.id + "?*s" // TODO: secure token
          },
          {
            cartId: vm.cart.id
          },
          onReceiveTokenSuccessWithCredit, onReceiveTokenErrorWithCredit
        );
			}
      

      if(vm.person.available_earnings >= vm.cart.total) {

        if(form)
  				form.$submitted = true;
  			if(vm.sameBilling)
  				vm.cart.billing_address = Object.assign({}, vm.cart.shipping_address);
  			if((form && form.$valid) || !form) {
          vm.cart.pay_with_credit = 1;
  				//POST TO API
  				cartDataService.updateCart(vm.cart, {
  					id: vm.cart.id
  				}, onSaveCartSuccessWithCredit, UtilService.onError);
  				//
  			}

      }

    }

	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/cart/payment-methods/payment-methods.html',
		controller: controller,
		controllerAs: 'paymentMethodsCtrl',
		bindings: {
			saving: '=',
			state: '=?',
			cart: '<',
			countries: '<'
		}
	}

	angular
		.module('cart')
		.component('paymentMethods', component);

}());
