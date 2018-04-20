### Person - GET orders

Example about how to call to Web Service to get a list of orders bought by person

**URL**: `/api/priv/v1/person/<:person_id>/orders`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found
  
**Request parameters**:
* `:person_id`: Id of the person (deviser) you want to get the packs
* `pack_state`: Filter orders only with packs in a specific state. 
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)
* `order_col`: Optional. Name of the column to order by (default: created_at)
* `order_dir`: Optional. Direcction of the order. Available values: asc / desc (default: asc);

```
Available *pack_state* values:
	- paid
	- aware
	- shipped
	
Also there is two special available values:
	- open: includes paid and aware
	- past: includes shipped
```

**Response body**:
```
{
  "items": [
	{
	  "id": "97aff9c9",
	  "person_id": "53e81ce",
	  "person_info": {
		"slug": "jose-vazquez",
		"name": "Jose Vázquez",
		"photo": "http://localhost.thumbor.todevise.com:8000/dBZIIZSezTziAGo6HFtsHimLg1M=/128x0//uploads/deviser/53e81ce/person.profile.cropped.59788309ac68f.jpg",
		"url": "http://localhost:8080/client/jose-vazquez/53e81ce/loved"
	  },
	  "subtotal": 565,
	  "order_state": "order_state_paid",
	  "order_date": {
		"sec": 1501060836,
		"usec": 286000
	  },
	  "shipping_address": {
		"name": "Michael",
		"last_name": "Jackson",
		"vat_id": "12345678Z",
		"email": "jacko@king.of.pop.com",
		"phone_number_prefix": "34",
		"phone_number": "981981981"
		"country": "ES",
		"city": "Best City",
		"address": "Best street, 15",
		"zip": "15177"
	  },
	  "billing_address": {
		"name": "Michael",
		"last_name": "Jackson",
		"vat_id": "12345678Z",
		"email": "jacko@king.of.pop.com",
		"phone_number_prefix": "34",
		"phone_number": "981981981"
		"country": "ES",
		"city": "Best City",
		"address": "Best street, 15",
		"zip": "15177"
	  },
	  "packs": [
		{
		  "short_id": "a22fd4c3",
		  "deviser_id": "6e13130",
		  "deviser_info": {
			"slug": "rosa-bisbe",
			"name": "Rosa Bisbe",
			"photo": "http://localhost.thumbor.todevise.com:8000/l7PE_BbN4yxMh07hRE32ZSBOCr0=/128x0//uploads/deviser/6e13130/profile.57d9197a27a8e.png",
			"url": "http://localhost:8080/deviser/rosa-bisbe/6e13130/store"
		  },
		  "shipping_type": "standard",
		  "shipping_price": null,
		  "shipping_info": {
			"company": "Seur",
			"tracking_number": "abc1234",
			"tracking_link": "http://www.seur.com"
		  },
		  "pack_weight": 0,
		  "pack_price": 565,
		  "pack_percentage_fee": 0.04,
		  "currency": null,
		  "weight_measure": null,
		  "pack_state": "shipped",
		  "pack_state_name": "Shipped",
		  "shipping_date": {
			"sec": 1501493161,
			"usec": 352000
		  },
		  "products": [
			{
			  "product_id": "b8c4f6df",
			  "price_stock_id": "b8c4f6dfeba2e12",
			  "quantity": 1,
			  "price": 565,
			  "weight": 0,
			  "options": {
				"731ct": [
				  "gold"
				],
				"2500m": [
				  "diamond"
				],
				"f6b97": [
				  "silver"
				]
			  },
			  "product_info": {
				"name": "SILVER WITH GOLDEN PLAQUE AND BROWN DIAMONDS",
				"photo": "http://localhost.thumbor.todevise.com:8000/-9bWA9lZT4R28CEfbT5kn48MDVM=//uploads/product/b8c4f6df/2016-07-21-16-34-13-cd85g.jpg",
				"slug": "silver-with-golden-plaque-and-brown-diamonds",
				"url": "http://localhost:8080/work/silver-with-golden-plaque-and-brown-diamonds/b8c4f6df",
				"stock": 1
			  }
			}
		  ]
		}
	  ],
	  "payment_info": {
		"id": "tok_1AkWnFJt4mveficF8Szt2UKl",
		"object": "token",
		"card": {
		  "id": "card_1AkWnFJt4mveficFnkokiMiC",
		  "object": "card",
		  "address_city": null,
		  "address_country": null,
		  "address_line1": null,
		  "address_line1_check": null,
		  "address_line2": null,
		  "address_state": null,
		  "address_zip": "15177",
		  "address_zip_check": "pass",
		  "brand": "Visa",
		  "country": "US",
		  "cvc_check": "pass",
		  "dynamic_last4": null,
		  "exp_month": NumberLong(5),
		  "exp_year": NumberLong(2020),
		  "funding": "credit",
		  "last4": "4242",
		  "metadata": [],
		  "name": "jacko@king.of.pop.com",
		  "tokenization_method": null
		},
		"client_ip": "195.76.104.164",
		"created": NumberLong(1501241889),
		"email": "jacko@king.of.pop.com",
		"livemode": false,
		"type": "card",
		"used": false
	  }
	  "state_history": null
	}
  ],
  "meta": {
	"total_count": 1,
	"current_page": 1,
	"per_page": 99999
  }
}
```


