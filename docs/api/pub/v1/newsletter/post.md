### Create a new Newsletter subscription - POST 

Example about how to call to Web Service to create a new Newsletter subscription

**URL**: `/api/pub/v1/newsletter`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `409`: Conflict (email already in use)
  
**Request parameters**:
* `email`: email of the user (required)
  
**Response body**:

```
{
    "id": "ff0eed9",
    "email": "michael@jackson.com",
    "enabled": true
}
```