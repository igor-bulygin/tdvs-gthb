### Stories - Index (GET list)

Example about how to call to Web Service to get a public list of 
Story

**URL**: `/api/pub/v1/story`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `403`: Not allowed
  
**Request parameters**:
* `id`: Filter a specific story for id
* `q`: Search word/s in the title of the story (LIKE)
* `person_id`: Filter stories of a specific person only
* `categories`: Array of ids, to filter stories related with any category of the list
* `countries`: Array of country codes, to filter stories of persons related with any country of the list
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**NOTES**
Title and slug fields (multilanguage fields) are automatically translated in the response body

**Response body**:

```
{
  "items": [
    {
      "id": "b09e4cdb",
      "story_state": "story_state_active",
      "person_id": "485902r",
      "title": "first story",
      "slug": "first-story",
      "categories": [
    	"1b34c",
    	"1h10i"
      ],
	  "tags": {
		"en-US": ["super", "story"],
		"es-ES": ["super", "historia"]
	  },
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
    		{
    		  "position": 1,
    		  "work": "1c76e811"
    		},
    		{
    		  "position": 2,
    		  "work": "8e568599"
    		},
    		{
    		  "position": 3,
    		  "work": "839d54ba"
    		}
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
      },
      "first_text" : "lorem ipsum english", 										// get the text of the first text type component (translated)
      "main_photo_url" : "http://www.todevise.com/.../profile.57d69c5e69900.png", 	// gets the absolute url to the main photo (if main media is photo)
      "published_at" : ISODate("2017-05-02T10:59:07.032Z")
    }
  ],
  "meta": {
    "total_count": 1,
    "current_page": 1,
    "per_page": 20
  }
}
```