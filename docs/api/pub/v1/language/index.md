### Languages - Index (GET list)

Example about how to call to Web Service to get a public list of 
Languages

**URL**: `/api/pub/v1/languages`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Response body**:

```
{
  "items": [
    {
      "code": "en-US",
      "name": "English"
    },
    {
      "code": "es-ES",
      "name": "Spanish"
    },
    {
      "code": "ca-ES",
      "name": "Catalan"
    }
  ],
  "meta": {
    "total_count": 3,
    "current_page": 1,
    "per_page": 3
  }
}
```