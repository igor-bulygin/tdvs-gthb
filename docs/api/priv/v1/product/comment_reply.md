### Product Comment Reply - Post

Example about how to call to Web Service to create a new Reply on a product

**URL**: `/api/priv/v1/products/<:product_id>/comments/<:comment_id>`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `text`: Comment text (Required)

NOTE: The reply is automatically asigned to the connected user

