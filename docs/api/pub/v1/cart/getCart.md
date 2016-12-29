### Cart - View - GET

Example about how to call to Web Service to get tthe cart

**URL**: `/api/pub/v1/cart/<:cartId>`

**Method**: `GET`

**Response codes**:
* `200`: Success
* `400`: Bad request

**Request parameters**:
* `:cartId`: Id of the cart that want to get


**Response body**:

```
{
  "id": "4cf9b192",
  "order_state": "order_state_cart",
  "client_id": "c2a37cs",
  "client_info": [],
  "products": [
    {
      "product_id": "d8823222",
      "price_stock_id": "d8823222ffb2f8b",
      "quantity": 5,
      "deviser_id": "f055694",
      "price": 310,
      "weight": 0,
      "options": {
        "size": "41",
        "731ct": [
          "black"
        ]
      },
      "product_name": "DOUBLE MONK STAP SHOES",
      "product_photo": "/uploads/product/d8823222/2016-05-17-05-55-35-2caf7.jpg",
      "deviser_name": "Inch2",
      "deviser_photo": "/imgs/default-avatar.jpg"
    }
  ],
  "subtotal": 1550
}
```