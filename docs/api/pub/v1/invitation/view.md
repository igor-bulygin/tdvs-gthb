### Invitation - View (GET detail)

Example about how to call to Web Service to get specific Invitation

**URL**: `/api/pub/v1/invitations/<:uuid>`
**Method**: `GET`
**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `:uuid`: Id of the invitation that want to get
  
**Response body**:

```
{
  "uuid": "6dca0323-dbf4-484b-a5fd-440f20d75e75",
  "email": "test@email.local"
}
```