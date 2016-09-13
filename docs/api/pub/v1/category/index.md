### Categories - Index (GET list)

Example about how to call to Web Service to get a public list of 
Categories

* URL: `/api/pub/v1/categories`
* Method: `GET`
* Response codes: 
 * `200`: Success
 * `400`: Bad request
  
* Request parameters:
 * `scope`: filter root categories, or all. Available values: "roots", "all" (default: "roots")
 * `page`: Set the result page that want to be retrieved (default: 1)
 * `limit`: Limit the results returned for page (default: 100)
    
* Response body:

```
{
  "items": [
    {
      "id": "1a23b",
      "path": "/",
      "sizecharts": false,
      "prints": false,
      "name": "Art",
      "slug": "art"
    },
    {
      "id": "2r67s",
      "path": "/",
      "sizecharts": false,
      "prints": false,
      "name": "Decoration",
      "slug": "decoration"
    },
    {
      "id": "3f78g",
      "path": "/",
      "sizecharts": true,
      "prints": false,
      "name": "Jewelry",
      "slug": "Jewelry"
    },
    {
      "id": "4a2b4",
      "path": "/",
      "sizecharts": true,
      "prints": false,
      "name": "Fashion",
      "slug": "fashion"
    },
    {
      "id": "f0cco",
      "path": "/",
      "sizecharts": false,
      "prints": false,
      "name": "Gadgets",
      "slug": "gadgets"
    },
    {
      "id": "ffeec",
      "path": "/",
      "sizecharts": false,
      "prints": false,
      "name": "More",
      "slug": "more"
    }
  ],
  "meta": {
    "total_count": 6,
    "current_page": 1,
    "per_page": 6
  }
}
```