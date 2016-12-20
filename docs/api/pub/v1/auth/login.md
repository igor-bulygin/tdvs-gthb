### Login - POST

Example about how to call to Web Service to login with todevise account

**URL**: `/api/pub/v1/auth/login`

**Method**: `POST`

**Response codes**: 
* `200`: Ok
* `400`: Bad request
  
**Request parameters**:
* `email`: email of the user to be logged in
* `password`: password of the user

**Response body**:
{"access_token":"0yWT6m....."}
