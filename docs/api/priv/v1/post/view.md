### Posts - View (GET detail)

Example about how to call to Web Service to get specific post of the connected user

**URL**: `/api/priv/v1/post/<:id>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `404`: Not found
  
**Request parameters**:
* `:id`: Id of the post that want to get
  
**Response body**:

See the structure of a post in index.md