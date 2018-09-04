### Product Comment - Post

Example about how to call to Web Service to create a new Comment on a product

**URL**: `/api/priv/v1/products/<:id>/comments`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `text`: Comment text (Required)
* `stars`: Integer. Number of stars (0-5) (Required)

NOTE: The comment is automatically asigned to the connected user