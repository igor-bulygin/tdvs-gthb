### Person - Patch

Example about how to call to Web Service to update a Person password

**URL**: `/api/priv/v1/person/<:personId>/update-password`

**Method**: `PUT`

**Response codes**: 
* `204`: No content
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personId`: Id of the person that want to upadte
  
**Request body**: 
* `oldpassword`: (string) current password of the user 
* `newpassword`: (string) new password of the user

**Response body**:

* No content