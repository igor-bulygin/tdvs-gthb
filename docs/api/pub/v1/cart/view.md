### Cart - View - GET

Example about how to call to Web Service to get tthe cart

**URL**: `/api/pub/v1/cart/<:id>`

**Method**: `GET`

**Response codes**:
* `200`: Success
* `400`: Bad request

**Request parameters**:
* `:id`: Id of the product that want to get


**Response body**:

```
{
  "id": "73d26178",
  "order_state": "order_state_cart",
  "client_id": null,
  "client_info": [],
  "products": [
    {
      "product_id": "b8066597",
      "price_stock_id": "b806659772d3e06",
      "quantity": 5,
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
    }
  ],
  "subtotal": 375
}
```