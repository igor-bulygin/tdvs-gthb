### Cart - View - GET

Example about how to call to Web Service to get tthe cart

**URL**: `/api/pub/v1/cart/<:cartId>`

**Method**: `GET`

**Response codes**:
* `200`: Success
* `400`: Bad request

**Request parameters**:
* `:id`: Id of the cart that want to get


**Response body**:

```
{
  "id": "4cf9b192",
  "order_state": "order_state_cart",
  "client_id": "c2a37cs",
  "client_info": [],
  "products": [],
  "subtotal": 0
}
```