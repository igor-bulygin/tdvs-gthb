### Chat send message - Post

Example about how to call to Web Service to send a message to a Person

**URL**: `/api/priv/v1/chat/send-message/<:personId>`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personId`: Id of the addressee of the message
  
**Request body**: 
* `text`: (string) text of the message

**Response body**:

*Note*: 
This method creates a new chat if there is no chat between the connected user and the addressee of the message

Returns the created or updated chat object. See the structure of a chat item in view.md