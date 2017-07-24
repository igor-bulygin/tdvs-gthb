### Delete product from the cart - Post

Example about how to call to Web Service to delete a product from the cart

**URL**: `/api/pub/v1/cart/<:cartId>/product/<:priceStockId>`

**Method**: `DELETE`

**Response codes**:
* `200`: Updated
* `400`: Bad request
* `403`: Not allowed

**Request parameters**:
* `:cartId`: Id of the cart that want to update
* `:priceStockId`: Id of the price stock item that want to update

**Response body**:

See the structure of the complete item in view.md 


