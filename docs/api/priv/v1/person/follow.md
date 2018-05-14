### Person - Follow (POST)

Example about how to call to Web Service to follow a person by the connected user

**URL**: `/api/priv/v1/follow/<:personToFollowId>`

**Method**: `POST`

**Response codes**: 
* `204`: Ok
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personToFollowId`: Id of the person that want to follow
  
**Response body**:

No content