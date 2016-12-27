### Update product to cart - Post

Example about how to call to Web Service to update a product to the cart

**URL**: `/api/pub/v1/cart/<:cartId>/product/<:priceStockId>`

**Method**: `POST`

**Response codes**:
* `200`: Updated
* `400`: Bad request
* `403`: Not allowed

**Request body**:
* `product_id`: Product identifier (Required)
* `price_stock_id`: Price&stock identifier (Required)
* `quantity`: Quantity of items (Required)


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
    }
  ],
  "subtotal": 2625
}
```


