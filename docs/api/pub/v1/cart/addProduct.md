### Add product to cart - Post

Example about how to call to Web Service to add a product to the cart

**URL**: `/api3/pub/v1/cart/<:cartId>/product`

**Method**: `POST`

**Response codes**:
* `201`: Created
* `400`: Bad request: syntax or validation error
* `401`: Unauthorized: trying to access to a cart of other user
* `404`: Not found
* `409`: Conflict: order is in an invalid state

**Request parameters**:
* `:cartId`: Id of the cart that want to update with the new product

**Request body**:
* `product_id`: Product identifier (Required)
* `price_stock_id`: Price&stock identifier (Required)
* `quantity`: Quantity of items (Required)

**Response body**:

Returns the cart modified. See the structure of the complete item in view.md