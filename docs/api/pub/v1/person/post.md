### Create a new Person account - POST 

Example about how to call to Web Service to create a new Person account

**URL**: `/api3/pub/v1/person`

**Method**: `POST`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `404`: Not found (invitation not found)
* `409`: Conflict (email already in use)
  
**Request parameters for create a Deviser**:
* `type` : array with only one element, with value 2 (required)
* `uuid`: uuid of invitation (required)
* `email`: email of deviser (required)
* `name`: representative first name (required)
* `last_name`: representative last name (required)
* `brand_name`: brand name (required)
* `password`: password (required)
  
**Request parameters for create a Influencer**:
* `type` : array with only one element, with value 3 (required)
* `invitation_id`: uuid of invitation (required)
* `email`: email of deviser (required)
* `name`: representative name (required)
* `password`: password (required)


**Request parameters for create a Client**:
* `type` : array with only one element, with value 1 (required)
* `email`: email of deviser (required)
* `name`: representative first name (required)
* `password`: password (required)

**Response body**:

See the structure of the complete item in view.md  