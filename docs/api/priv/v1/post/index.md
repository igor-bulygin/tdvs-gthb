### Posts - Index (GET list)

Example about how to call to Web Service to get a private lists of Posts of the connected user

**URL**: `/api/priv/v1/post`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific post for id
* `q`: Search word/s in the title of the post (LIKE)
* `post_state`: Filter Posts by state (values: post_state_draft, post_state_active)
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
    "items": [
        {
            "id": "d88a4923",
            "post_state": "post_state_active",
            "person_id": "1000000",
            "text": "lorem ipsum dolor sit amet",
            "photo": "person.post.5b04a8fe7dd91.jpg",
            "photo_url": "/uploads/deviser/1000000/person.post.5b04a8fe7dd91.jpg",
            "published_at": {
                "sec": 1527032179,
                "usec": 226000
            }
        },
        {
            "id": "a86945e6",
            "post_state": "post_state_active",
            "person_id": "1000000",
            "text": "lorem ipsum dolor sit amet",
            "photo": "person.post.5b04a8fe7dd91.jpg",
            "photo_url": "/uploads/deviser/1000000/person.post.5b04a8fe7dd91.jpg",
            "published_at": {
                "sec": 1527032177,
                "usec": 828000
            }
        },
        {
            "id": "44b141d3",
            "post_state": "post_state_active",
            "person_id": "1000000",
            "text": "lorem ipsum dolor sit amet",
            "photo": "person.post.5b04a8fe7dd91.jpg",
            "photo_url": "/uploads/deviser/1000000/person.post.5b04a8fe7dd91.jpg",
            "published_at": {
                "sec": 1527032075,
                "usec": 239000
            }
        },
        {
            "id": "fa067266",
            "post_state": "post_state_active",
            "person_id": "1000000",
            "text": "lorem ipsum dolor sit amet",
            "photo": null,
            "photo_url": "/uploads/deviser/1000000/",
            "published_at": {
                "sec": 1527031836,
                "usec": 880000
            }
        }
    ],
    "meta": {
        "total_count": 4,
        "current_page": 1,
        "per_page": 99999
    }
}
```