### Person - Follow (DELETE)

Example about how to call to Web Service to un-follow a person by the connected person

**URL**: `/api/priv/v1/person/follow/<:personToUnfollowId>`

**Method**: `DELETE`

**Response codes**: 
* `204`: Ok
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personToUnfollowId`: Id of the person that want to follow
  
**Response body**:

No content