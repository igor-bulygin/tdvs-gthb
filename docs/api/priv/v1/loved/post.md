### Loved - Post

Example about how to call to Web Service to create a new loved

**URL**: `/api/priv/v1/loved`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `403`: Not allowed
  
**Request body**: 
* `product_id`: Product identifier. (Required)

**Notes**
The loved is automatically asigned to the connected user

