### Create a new Person account - POST 

Example about how to call to Web Service to create a new Person account

**URL**: `/api/pub/v1/person`

**Method**: `POST`

**Response codes**: 
* `201`: Success (without body)
* `400`: Bad request
  
**Request parameters for create a Deviser**:
* `type` : array with only one element, with value 2 (required)
* `invitation_id`: uuid of invitation (required)
* `email`: email of deviser (required)
* `first_name`: representative first name (required)
* `last_name`: representative last name (required)
* `brand_name`: brand name (required)
* `password`: password (required)
  
**Request parameters for create a Influencer**:
* `type` : array with only one element, with value 3 (required)
* `invitation_id`: uuid of invitation (required)
* `email`: email of deviser (required)
* `name`: representative name (required)
* `password`: password (required)
