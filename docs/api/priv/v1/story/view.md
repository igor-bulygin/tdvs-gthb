### Stories - View (GET detail)

Example about how to call to Web Service to get specific story of the connected user

**URL**: `/api/priv/v1/story/<:id>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `:id`: Id of the story that want to get
  
**Response body**:

See the structure of a story in index.md