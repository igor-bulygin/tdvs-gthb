(function () {
	"use strict";

	function controller(UtilService, orderDataService) {
		var vm = this;
		vm.changeOrderState=changeOrderState;
		vm.deviserId=person.id;
		vm.orders=[];
		init();

		function init() {
			getOrders();
			
		}

		function getOrders() {
			function onGetOrdersSuccess(data) {
				if(angular.isArray(data.items) && data.items.length > 0) {
					vm.orders = angular.copy(data.items); 
				}
				vm.orders= [
				{
					"id": "3b65b43c",
					"person_id": "1000000",
					"person_info": {
						"slug": "admin",
						"name": "Admin",
						"photo": "http://localhost.thumbor.todevise.com:8000/hwdnt1qH25UFH1_Vf4EWGvkaKEQ=/128x0//imgs/default-avatar.png",
						"url": "/"
					},
					"order_date": {
						"sec": 1500573262,
						"usec": 344000
					},
					"shipping_address": {
						"first_name": "Jose",
						"last_name": "Vázquez",
						"vat_id": "12345678Z",
						"email": "jose.vazquez@gmail.com",
						"phone": {
							"prefix": "34",
							"number": "657454038"
						},
						"country": "ES",
						"city": "Lorbé - Oleiros",
						"address": "Vila do Couto, 15",
						"zipcode": "15177"
					},
					"billing_address": {
						"first_name": "Jose",
						"last_name": "Vázquez",
						"vat_id": "12345678Z",
						"email": "jose.vazquez@gmail.com",
						"phone": {
							"prefix": "34",
							"number": "657454038"
						},
						"country": "ES",
						"city": "Lorbé - Oleiros",
						"address": "Vila do Couto, 15",
						"zipcode": "15177"
					},
					"packs": [
					{
						"short_id": "bfd65373",
						"deviser_id": "f351c59",
						"shipping_type": "standard",
						"shipping_price": null,
						"pack_weight": 0,
						"pack_price": 130,
						"pack_percentage_fee": null,
						"currency": null,
						"weight_measure": null,
						"products": [
						{
							"product_id": "ca60b295",
							"price_stock_id": "ca60b2951d8eff7",
							"quantity": 1,
							"price": 130,
							"weight": 0,
							"options": {
								"size": "38 (S)",
								"731ct": [
								"green",
								"blue",
								"pink"
								],
								"d0e2g": [
								"elastene",
								"polyester"
								]
							},
							"product_info": {
								"name": "Stark - Printed Velvet Dress",
								"photo": "http://localhost.thumbor.todevise.com:8000/t90c-IOS5LTcUn-tVJ5GORlhxOo=//uploads/product/ca60b295/2016-11-14-13-17-19-1fe9f.jpg",
								"slug": "stark-printed-velvet-dress",
								"url": "http://localhost:8080/work/stark-printed-velvet-dress/ca60b295"
							}
						}
						]
					}
					]
				},
				{
					"id": "e0ecbf7c",
					"person_id": "1000000",
					"person_info": {
						"slug": "admin",
						"name": "Admin",
						"photo": "http://localhost.thumbor.todevise.com:8000/hwdnt1qH25UFH1_Vf4EWGvkaKEQ=/128x0//imgs/default-avatar.png",
						"url": "/"
					},
					"order_date": {
						"sec": 1500630872,
						"usec": 505000
					},
					"shipping_address": {
						"first_name": "Jose",
						"last_name": "Vázquez",
						"vat_id": "12345678Z",
						"email": "jose.vazquez@gmail.com",
						"phone": {
							"prefix": "34",
							"number": "657454038"
						},
						"country": "ES",
						"city": "Lorbé - Oleiros",
						"address": "Vila do Couto, 15",
						"zipcode": "15177"
					},
					"billing_address": {
						"first_name": "Jose",
						"last_name": "Vázquez",
						"vat_id": "12345678Z",
						"email": "jose.vazquez@gmail.com",
						"phone": {
							"prefix": "34",
							"number": "657454038"
						},
						"country": "ES",
						"city": "Lorbé - Oleiros",
						"address": "Vila do Couto, 15",
						"zipcode": "15177"
					},
					"packs": [
					{
						"short_id": "2cc70d4c",
						"deviser_id": "f351c59",
						"shipping_type": "standard",
						"shipping_price": null,
						"pack_weight": 0,
						"pack_price": 505,
						"pack_percentage_fee": null,
						"currency": null,
						"weight_measure": null,
						"products": [
						{
							"product_id": "ca60b295",
							"price_stock_id": "ca60b2951d8eff7",
							"quantity": 1,
							"price": 130,
							"weight": 0,
							"options": {
								"size": "38 (S)",
								"731ct": [
								"green",
								"blue",
								"pink"
								],
								"d0e2g": [
								"elastene",
								"polyester"
								]
							},
							"product_info": {
								"name": "Stark - Printed Velvet Dress",
								"photo": "http://localhost.thumbor.todevise.com:8000/t90c-IOS5LTcUn-tVJ5GORlhxOo=//uploads/product/ca60b295/2016-11-14-13-17-19-1fe9f.jpg",
								"slug": "stark-printed-velvet-dress",
								"url": "http://localhost:8080/work/stark-printed-velvet-dress/ca60b295"
							}
						},
						{
							"product_id": "852eb305",
							"price_stock_id": "852eb3055b581fm",
							"quantity": 3,
							"price": 75,
							"weight": 0,
							"options": {
								"size": "42 (L)",
								"731ct": [
								"red",
								"yellow",
								"blue"
								],
								"d0e2g": [
								"elastene",
								"polyester"
								]
							},
							"product_info": {
								"name": "Acrylic - Printed Dress",
								"photo": "http://localhost.thumbor.todevise.com:8000/2mYu5RL1WeJPFuy-Z1D0ZmiwxpM=//uploads/product/852eb305/2016-11-15-11-04-17-acf14.jpg",
								"slug": "acrylic-printed-dress",
								"url": "http://localhost:8080/work/acrylic-printed-dress/852eb305"
							}
						},
						{
							"product_id": "0f55b159",
							"price_stock_id": "0f55b15913d324s",
							"quantity": 2,
							"price": 75,
							"weight": 0,
							"options": {
								"size": "42 (L)",
								"731ct": [
								"white",
								"orange",
								"brown"
								],
								"d0e2g": [
								"elastene",
								"polyester"
								]
							},
							"product_info": {
								"name": "Yellowstone - Printed Dress",
								"photo": "http://localhost.thumbor.todevise.com:8000/mZwxsziDuXy8Z4-74AK0jwQ-olY=//uploads/product/0f55b159/2016-11-15-11-07-28-2cd14.jpg",
								"slug": "yellowstone-printed-dress",
								"url": "http://localhost:8080/work/yellowstone-printed-dress/0f55b159"
							}
						}
						]
					}
					]
				}
				];
				angular.forEach(vm.orders, function(order, key) {
					order.state='aware';
					order.totalPrice = 0;
					order.totalShippingPrice=0;
					order.order_date= new Date(order.order_date.sec*1000)
					angular.forEach(order.packs, function(pack, keyPack) {
						order.totalPrice = order.totalPrice + pack.pack_price;
						order.totalShippingPrice = order.totalShippingPrice + pack.shipping_price;
					});
					order.total= order.totalPrice + order.totalShippingPrice ; //TODO + commission
				});
			}
			orderDataService.getDeviserOrders({pack_state:"open", personId:vm.deviserId}, onGetOrdersSuccess, UtilService.onError);
		}

		function changeOrderState(order) {
			if (order.state==='aware') {
				order.state='preparing'
			}
			else if (order.state==='preparing') {
				order.state='shipped';
				//TODO send changed state
				vm.orders.splice(vm.orders.indexOf(order),1);
			}
			
		}
	}

	angular
	.module('settings')
	.controller('openOrdersCtrl', controller);
}());