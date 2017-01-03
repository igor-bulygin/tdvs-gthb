### Update product to cart - Post

Example about how to call to Web Service to update a product to the cart

**URL**: `/api/pub/v1/cart/<:cartId>/product/<:priceStockId>`

**Method**: `POST`

**Response codes**:
* `200`: Updated
* `400`: Bad request
* `403`: Not allowed

**Request parameters**:
* `:cartId`: Id of the cart that want to update
* `:priceStockId`: Id of the price stock item that want to update


**Request body**:
* `product_id`: Product identifier (Required)
* `price_stock_id`: Price&stock identifier (Required)
* `quantity`: Quantity of items (Required)


**Response body**:
```
cart object (view getCart.md)
```


