### Posts - Post

Example about how to call to Web Service to create a new post

**URL**: `/api/priv/v1/post`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `person_id`: Person identifier of the post's owner. (Required)
* `post_state`: State of the post. Available values: (`post_state_draft` and `post_state_active`)
* `text`: Multilanguage field. Title of the post. (Required)
* `photo`: Photo associated with the post (it has to be previously uploaded using upload webservice)

**Response body**:

See the structure of a post in index.md
