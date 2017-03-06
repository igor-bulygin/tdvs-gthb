### Create a new Client account - POST 

Example about how to call to Web Service to create a new Client ccount

**URL**: `/api/pub/v1/person/signup`

**Method**: `POST`

**Response codes**: 
* `201`: Success (without body)
* `400`: Bad request
  
**Request parameters:
* `type` : array with only one element, with value 2 (required)
* `email`: email of deviser (required)
* `first_name`: representative first name (required)
* `password`: password (required)
  