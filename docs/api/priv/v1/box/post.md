### Boxes - Post

Example about how to call to Web Service to create a new box

**URL**: `/api/priv/v1/box`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `name`: Name of the box. (Required)
* `description`: Description of the box. (Required)

**Notes**
The box is automatically asigned to the connected user

