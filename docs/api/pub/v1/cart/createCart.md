### Create a new cart - Post

Example about how to call to Web Service to create a new cart

**URL**: `/api3/pub/v1/cart`

**Method**: `POST`

**Response codes**:
* `201`: Created

**Request body**:

```
{
  "id": "4cf9b192",
  "order_state": "order_state_cart",
  "person_id": "c2a37cs",
  "person_info": [],
  "products": [],
  "subtotal": 0
}
```

**Response body**:

Returns the cart modified. See the structure of the complete item in view.md 

