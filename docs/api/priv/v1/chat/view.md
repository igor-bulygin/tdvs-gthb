### Chat - View (GET detail)

Example about how to call to Web Service to get specific chat (conversation)

**URL**: `/api/priv/v1/chat/<:id>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found
  
**Request parameters**:
* `:id`: Id of the chat (conversation) that want to get
  
**Response body**:

```
{
    "id": "78f2fa4e",
    "members": [
        {
            "person_id": "d4ac1a8",
            "person_info": {
                "slug": "hawkins-and-brimble",
                "name": "Hawkins and Brimble",
                "photo": "http://localhost.thumbor.todevise.com:8000/RmgE9t0SWCjD59pQb2DkPMoDZno=/155x155//uploads/deviser/d4ac1a8/person.profile.cropped.5a044d7950916.png",
                "url": "http://localhost:8080/deviser/hawkins-and-brimble/d4ac1a8/store"
            }
        },
        {
            "person_id": "1000000",
            "person_info": {
                "slug": "admin",
                "name": "Admin",
                "photo": "/imgs/default-avatar.png",
                "url": "/"
            }
        }
    ],
    "messages": [
        {
            "id": "17d757db",
            "person_id": "1000000",
            "person_info": {
                "slug": "admin",
                "name": "Admin",
                "photo": "/imgs/default-avatar.png",
                "url": "/"
            },
            "text": "This is my second message",
            "date": {
                "sec": 1518637948,
                "usec": 430000
            }
        },
        {
            "id": "592c95fd",
            "person_id": "1000000",
            "person_info": {
                "slug": "admin",
                "name": "Admin",
                "photo": "/imgs/default-avatar.png",
                "url": "/"
            },
            "text": "This is my first message",
            "date": {
                "sec": 1518637767,
                "usec": 366000
            }
        }
    ]
}```

