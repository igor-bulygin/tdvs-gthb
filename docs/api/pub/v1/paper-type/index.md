### PaperType - Index (GET list)

Example about how to call to Web Service to get a public list of 
PaperType

**URL**: `/api/pub/v1/paper-type`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:

**Response body**:

```
[
  {
    "type": 1,
    "name": "Fine art paper"
  },
  {
    "type": 2,
    "name": "Canvas"
  }
]
```