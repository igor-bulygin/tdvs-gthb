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
* `person_id`: Person identifier of the story's owner. (Required)
* `story_state`: State of the story. Available values: (`story_state_draft` and `story_state_active`)
* `title`: Multilanguage field. Title of the story. (Required)
* `categories`: [] array with category ids (["f0cco", "1234"])
* `tags`: [] array with tags as multilanguage fields
* `components`: [] array with components (see above)
* `main_media.type`: Type of main media. Available values: (1: photo)
* `main_media.photo`: Photo marked as main media

**Components**:

Components has 4 types:
* `1`: Text
* `2`: Photos
* `3`: Works
* `4`: Videos

Each component has at least 3 properties:
* `type`: (integer), one of the types explained
* `position` : (integer) position of the component inside the story
* `items` : items inside the component, depending on the type. 

In the response body you can see an example of each case

**Response body**:

See the structure of a story in index.md
