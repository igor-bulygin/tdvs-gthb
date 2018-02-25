### Mark chat as read - Patch

Example about how to call to Web Service to mark a chat as read by the connected user

**URL**: `/api/priv/v1/chat/mark-as-read/<:chatId>`

**Method**: `PATCH`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:chatId`: Id of the chat that want to mark as read
  
**Request body**: 
* No body

**Response body**:

Returns the updated object. See the structure of a chat item in view.md