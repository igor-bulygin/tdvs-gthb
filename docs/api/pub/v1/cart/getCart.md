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
      "product_id": "b8066597",
      "price_stock_id": "b806659772d3e06",
      "quantity": 35,
      "deviser_id": "18d910a",
      "price": 75,
      "weight": 0,
      "options": {
        "20000": [
          {
            "metric_unit": "mm",
            "value": 0
          },
          {
            "metric_unit": "cm",
            "value": "30"
          },
          {
            "metric_unit": "mm",
            "value": 0
          }
        ],
        "731ct": [
          "white",
          "blue",
          "pink"
        ]
      }
    },
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
      }
    }
  ],
  "subtotal": 4175
}
```