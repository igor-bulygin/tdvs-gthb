### Stories - Post

Example about how to call to Web Service to create a new story

**URL**: `/api/priv/v1/story`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `title`: Multilanguage field. Title of the story. (Required)

**Notes**
The story is automatically asigned to the connected user

