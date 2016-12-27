### Create a new cart - Post

Example about how to call to Web Service to create a new cart

**URL**: `/api/pub/v1/cart`

**Method**: `POST`

**Response codes**:
* `201`: Created
* `400`: Bad request
* `403`: Not allowed

**Request body**:

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


