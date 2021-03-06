### Cart - View - PUT

Example about how to call to Web Service to update the cart

**URL**: `/api3/pub/v1/cart/<:cartId>`

**Method**: `PUT`

**Response codes**:
* `200`: Success
* `400`: Bad request: syntax or validation error
* `401`: Unauthorized: trying to access to a cart of other user
* `404`: Not found
* `409`: Conflict: order is in an invalid state

**Request parameters**:
* `:cartId`: Id of the cart that want to get
  
**Request body**: 
* `shipping_address`: Address configured by the customer as shipping_address (see structure above)
* `billing_address`: Address configured by the customer as billing (see structure above)
* `packs.shipping_type`: Type of the shipping configured by the customer (standard, express)

**Address object structure**:
Next example shows the structure of an object that represents an address
```
{
	"name": null,
	"last_name": null,
	"vat_id": null,
	"email": null,
	"phone_number_prefix": null,
	"phone_number":null,		
	"country": null,
	"city": null,
	"address": null,
	"zip": null
}
```

**Response body**:

Returns the cart modified. See the structure of the complete item in view.md 
