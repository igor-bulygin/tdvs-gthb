### Create a new Deviser account - POST 

Example about how to call to Web Service to create a new Deviser account

**URL**: `/api/pub/v1/devisers`

**Method**: `POST`

**Response codes**: 
* `201`: Success (without body)
* `400`: Bad request
  
**Request parameters**:
* `invitation_id`: uuid of invitation (required)
* `email`: email of deviser (required)
* `first_name`: representative first name (required)
* `last_name`: representative last name (required)
* `brand_name`: brand name (required)
* `password`: password (required)
