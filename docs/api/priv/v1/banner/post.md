### Banners - Post

Example about how to call to Web Service to create a new banner

**URL**: `/api/priv/v1/banner`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `image`: Multilanguage field. Name of the image (phisical name of the file)
* `alt_text`: Multilanguage field. Alternative text of the image
* `link`: Optional. Multilanguage field. Link of the banner
* `type`: carousel|home_banner
* `position`: integer. Position of the banner in the group
* `category_id`: Optional. Id of the category to associate

**Response body**:

See the structure of a banner in index.md
