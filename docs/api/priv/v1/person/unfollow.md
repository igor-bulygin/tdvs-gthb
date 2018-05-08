### Person - Follow (DELETE)

Example about how to call to Web Service to un-follow a person

**URL**: `/api/priv/v1/person/<:personId>/follow/<:personToUnfollowId>`

**Method**: `POST`

**Response codes**: 
* `200`: Ok
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personId`: Id of the person that want to upadte
* `:personToUnfollowId`: Id of the person that want to follow
  

**Response body**:

* See index.md