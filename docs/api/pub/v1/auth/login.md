### Login - POST

Example about how to call to Web Service to login with todevise account

**URL**: `/api3/pub/v1/auth/login`

**Method**: `POST`

**Response codes**: 
* `200`: Ok
* `400`: Bad request
  
**Request parameters**:
* `email`: email of the user to be logged in
* `password`: password of the user
* `rememberMe`: (boolean) if the user stay connected

**Response body**:

```
    {
        "access_token" : "0yWT6.....",
        "return_url" : "/deviser/phuong-my/dffbd75/works/create",
    }
```
