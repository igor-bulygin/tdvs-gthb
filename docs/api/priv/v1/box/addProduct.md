### Boxes - Post

Example about how to call to Web Service to add a product to a box

**URL**: `/api/priv/v1/box/<:box_id>/product`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `product_id`: Product identifier. (Required)


