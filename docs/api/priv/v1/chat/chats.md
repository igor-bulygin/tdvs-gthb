### Person - GET packs

Example about how to call to Web Service to get a list of the chats of the connected user

**URL**: `/api/priv/v1/chats`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found
  
**Request parameters**:
* `person_type`: Filter chats only with a specific user type (1: customers, 2: devisers, 3: influencers) 
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

*Note*: 
This method returns a preview version of each conversation. It does not include all the messages and information of each conversation

In the `preview` property, we have:
* `title`: Represented by the names of the members in the chat. Currently only one member by chat
* `image`: Main image of the chat. Currently is the profile image of the first member different of the connected user
* `text`: Last message in the chat
* `unread`: If the chat is marked as unread by the current user
* `messages`: Total number of messages in the chat

```
{
   "items": [
	   {
		   "id": "78f2fa4e",
		   "preview": {
			   "title": "Hawkins and Brimble",
			   "image" : "http://localhost.thumbor.todevise.com:8000/RmgE9t0SWCjD59pQb2DkPMoDZno=/155x155//uploads/deviser/d4ac1a8/person.profile.cropped.5a044d7950916.png",
			   "text": "Last message",
			   "unread": false,
			   "messages": 2,
			   "url": "http://localhost:8080/messages/hawkins-and-brimble/d4ac1a8"
		   }
	   },
	   {
		   "id": "6e14b115",
		   "preview": {
			   "title": "Sweet Matitos",
               "image": "http://localhost.thumbor.todevise.com:8000/Mme2jM2BzQC3C98Ip9edxX0-3A4=/155x155//uploads/deviser/9d5b9a9/person.profile.cropped.59a8668b95237.jpg",
			   "text": "Last message",
			   "unread": true,
			   "messages": 11,
			   "url": "http://localhost:8080/messages/sweet-matitos/9d5b9a9"
		   }
	   }
   ],
   "meta": {
	   "total_count": 2,
	   "current_page": 1,
	   "per_page": 99999
   }
}
```

