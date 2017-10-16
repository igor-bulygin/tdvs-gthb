### Delete product from the cart - Post

Example about how to call to Web Service to delete a product from the cart

**URL**: `/api3/pub/v1/cart/<:cartId>/product/<:priceStockId>`

**Method**: `DELETE`

**Response codes**:
* `200`: Updated
* `400`: Bad request: syntax or validation error
* `401`: Unauthorized: trying to access to a cart of other user
* `404`: Not found
* `409`: Conflict: order is in an invalid state

**Request parameters**:
* `:cartId`: Id of the cart that want to update
* `:priceStockId`: Id of the price stock item that want to update

**Response body**:

Returns the cart modified. See the structure of the complete item in view.md 


