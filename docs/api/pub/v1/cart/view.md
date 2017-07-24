### Cart - View - GET

Example about how to call to Web Service to get the cart

**URL**: `/api/pub/v1/cart/<:cartId>`

**Method**: `GET`

**Response codes**:
* `200`: Success
* `400`: Bad request
* `404`: Not found

**Request parameters**:
* `:cartId`: Id of the cart that want to get


**Response body**:

```
{
   "id": "3b65b43c",
   "person_id": "1000000",
   "subtotal": 130,
   "order_state": "order_state_cart",
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
		   "short_id": "2cc70d4c",
		   "deviser_id": "f351c59",
		   "deviser_info": {
			   "slug": "due",
			   "name": "Due.",
			   "photo": "http://localhost.thumbor.todevise.com:8000/Jap7E4DbCMXSqClr_BzS7ATpUeY=/128x0//uploads/deviser/f351c59/profile.57d290158ea58.png",
			   "url": "http://localhost:8080/deviser/due/f351c59/store"
		   },
		   "shipping_type": "standard",
		   "shipping_price": 10,
		   "pack_weight": 5,
		   "pack_price": 130,
		   "pack_percentage_fee": 2.25,
		   "currency": "EUR",
		   "weight_measure": "g",
		   "products": [
			   {
				   "product_id": "ca60b295",
				   "price_stock_id": "ca60b2951d8eff7",
				   "quantity": 1,
				   "price": 130,
				   "weight": 5,
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
}
```