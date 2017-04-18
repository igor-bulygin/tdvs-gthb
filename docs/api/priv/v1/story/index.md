### Stories - Index (GET list)

Example about how to call to Web Service to get a private lists of Stories of the connected user

**URL**: `/api/priv/v1/story`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific product for id
* `q`: Search word/s in name and description (LIKE)
* `story_state`: Filter stories by state (values: story_state_draft, story_state_active)
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

TODO