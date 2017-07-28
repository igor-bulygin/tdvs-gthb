### Person - PUT pack state as shipped

Example about how to call to Web Service to set the state of a pack as *shipped*

**URL**: `/api/priv/v1/person/<:person_id>/packs/<:pack_id>/shipped`

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

**Request body**:
* `company`: Name of company that ships the pack
* `tracking_number`: Tracking number to follow the package
* `tracking_link`: Link to company website to get more info about package

**Response body**:

Returns the order modified. See the structure of the complete item in packs.md