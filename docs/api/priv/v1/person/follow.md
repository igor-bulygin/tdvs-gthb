### Person - Follow (POST)

Example about how to call to Web Service to follow a person

**URL**: `/api/priv/v1/person/<:personId>/follow/<:personToFollowId>`

**Method**: `POST`

**Response codes**: 
* `200`: Ok
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personId`: Id of the person that want to upadte
* `:personToFollowId`: Id of the person that want to follow
  

**Response body**:

* See index.md