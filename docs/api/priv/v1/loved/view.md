### Loved - View (GET detail)

Example about how to call to Web Service to get specific Loved of the connected user

**URL**: `/api/priv/v1/loved/<:id>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `:id`: Id of the loved that want to get
  
**Response body**:
See index.md