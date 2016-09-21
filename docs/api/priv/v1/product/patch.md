### Product - Patch

Example about how to call to Web Service to update a Product

**URL**: `/api/priv/v1/products/<:id>`

**Method**: `PATCH`

**Response codes**: 
* `204`: Success, without body
* `400`: Bad request
* `403`: Not allowed
  
**Request body**: 
* `position`: new position to show the product in store
