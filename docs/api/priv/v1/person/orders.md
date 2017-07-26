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
	  "id": "e0ecbf7c",
	  "person_id": "1000000",
	  "subtotal": 564,
	  "order_state": "order_state_paid",
	  "order_date": {
		"sec": 1500630872,
		"usec": 505000
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
		  "deviser_id": "f351c59",
		  "short_id": "2cc70d4c",
		  "shipping_type": "standard",
		  "shipping_price": null,
		  "pack_weight": 0,
		  "pack_price": 505,
		  "pack_percentage_fee": null,
		  "currency": null,
		  "weight_measure": null,
		  "pack_state": "open",
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
			  }
			}
		  ]
		},
		{
		  "deviser_id": "51c0f3s",
		  "short_id": "3abeef8c",
		  "shipping_type": "standard",
		  "shipping_price": null,
		  "pack_weight": 0,
		  "pack_price": 59,
		  "pack_percentage_fee": null,
		  "currency": null,
		  "weight_measure": null,
		  "pack_state": "open",
		  "products": [
			{
			  "product_id": "eb1c85ef",
			  "price_stock_id": "eb1c85ef5be034w",
			  "quantity": 1,
			  "price": 59,
			  "weight": 0,
			  "options": {
				"size": "M",
				"731ct": [
				  "black",
				  "red"
				],
				"d0e2g": [
				  "cotton",
				  "other"
				]
			  }
			}
		  ]
		}
	  ]
	}
  ],
  "meta": {
	"total_count": 1,
	"current_page": 1,
	"per_page": 99999
  }
}
```

