### Person - PUT pack state as aware

Example about how to call to Web Service to set the state of a pack as *aware*

**URL**: `/api/priv/v1/person/<:person_id>/packs/<:pack_id>/aware`

**Method**: `PUT`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found
  
**Request parameters**:
* `:person_id`: Id of the person (deviser) owner of the pack
* `:pack_id`: Id of the pack (inside a order) you want to change state

**Response body**:

Returns the order modified. See the structure of the complete item in packs.md