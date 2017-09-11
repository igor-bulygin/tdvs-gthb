### Sizechart - Index (GET list)

Example about how to call to Web Service to get a public list of
SizeChart

**URL**: `/api/pub/v1/sizechart`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `scope`: Customize filters. Available values: "all" (default: "all). Note that "all" scope means that no limit will be applied
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 100)
    
**Response body**:

```
{
  "items": [
    {
       ...
    },
    {
       ...
    }
    ...
   ],
  "meta": {
    "total_returned": 6,
    "total_count": 100,
    "current_page": 1,
    "per_page": 6
  }
}
```