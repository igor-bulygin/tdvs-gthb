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
  "person_id": "c2a37cs",
  "person_info": {
    "first_name": "Jose",
    "last_name": "Vázquez",
    "email": "jose.vazquez.viader@gmail.com",
    "phone1": {
      "prefix": "+34",
      "number": "981981981"
    },
    "phone2": {
      "prefix": "+34",
      "number": "900900900"
    },
    "country": "ES",
    "city": "A Coruña",
    "address": "Real 1",
    "zipcode": "15001"
  },
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
      "deviser_photo": "/imgs/default-avatar.png"
    }
  ],
  "subtotal": 1550
}
```