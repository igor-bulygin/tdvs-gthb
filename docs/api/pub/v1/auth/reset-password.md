### Reset password - POST

Example about how to call to Web Service to reset a password (only working from a forgot password link)

**URL**: `/api3/pub/v1/auth/reset-password`

**Method**: `POST`

**Response codes**: 
* `204`: Password updated
* `400`: Bad request
  
**Request parameters**:
* `email`: email of the user who is changing the password
* `person_id`: unique identifier of the user who is changing the password
* `action_id`: unique identifier of the "forgot password" action
* `new_password`: new password
* `repeate_password`: new password (repeated)

**Response body**:
No content (code 204)
