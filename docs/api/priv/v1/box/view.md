### Boxes - View (GET detail)

Example about how to call to Web Service to get specific box of the connected user

**URL**: `/api/priv/v1/box/<:id>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `:id`: Id of the box that want to get
  
**Response body**:
See index.md