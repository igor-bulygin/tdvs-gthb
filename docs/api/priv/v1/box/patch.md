### Boxes - Patch

Example about how to call to Web Service to update a box

**URL**: `/api/priv/v1/box/<:box_id>`

**Method**: `PATCH`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `name`: Name of the box. (Required)
* `description`: Description of the box. (Required)


