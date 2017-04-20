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
* `story_state`: State of the story. Available values: (`story_state_draft` and `story_state_active`)
* `title`: Multilanguage field. Title of the story. (Required)
* `categories`: [] array with category ids (["f0cco", "1234"])
* `tags`: [] array with tags as multilanguage fields
* `components`: [] array with components (see above)
* `main_media.type`: Type of main media. Available values: (1: photo)
* `main_media.photo`: Photo marked as main media

**Notes**
The story is automatically asigned to the connected user

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
 
```
{
  "id": "0f466769",
  "story_state": "story_state_draft",
  "person_id": "485902r",
  "title": {
	"en-US": "first story"
  },
  "categories": [
	"1b34c",
	"1h10i"
  ],
  "tags": [
	{
	  "en-US": "super",
	  "es-ES": "super"
	},
	{
	  "en-US": "story",
	  "es-ES": "historia"
	}
  ],
  "components": [
	{
	  "type": 1,
	  "position": 0,
	  "items": {
		"en-US": "lorem ipsum english",
		"es-ES": "lorem ipsum castellano"
	  }
	},
	{
	  "type": 2,
	  "position": 0,
	  "items": [
		{
		  "position": 1,
		  "photo": "deviser.press.57e394fc5a83a.jpg"
		},
		{
		  "position": 2,
		  "photo": "deviser.press.57e394e36d81b.jpg"
		}
	  ]
	},
	{
	  "type": 3,
	  "position": 0,
	  "items": [
		"1c76e811",
		"8e568599",
		"839d54ba"
	  ]
	},
	{
	  "type": 4,
	  "position": 0,
	  "items": [
		"https://www.youtube.com/watch?v=keVKinMKTzE",
		"https://www.youtube.com/watch?v=FnGExIw6hTk"
	  ]
	}
  ],
  "main_media": {
	"type": 1,
	"photo": "profile.57d69c5e69900.png"
  }
}

```
