### Product - Index (GET list)

Example about how to call to Web Service to get a public list of 
Products

* URL: /api/pub/v1/products
* Method: GET
* Response codes: 
 * `200`: Success
 * `400`: Bad request
  
* Request parameters:
 * `name`: Search word/s in name attribute (LIKE)
 * `q`: Search word/s in name and description (LIKE)
 * `id`: Filter a specific product for id
 * `deviser`: Filter in products of a specific deviser only
 * `categories`: Filter products related with any category of the list
 * `page`: Set the result page that want to be retrieved (default: 1)
 * `limit`: Limit the results returned for page (default: 20)

* Response body:

```
{
  "items": [
    {
      "id": "a04c31dc",
      "name": "test product",
      "slug": "test-product",
      "description": "The piece",
      ...
    },
    ... 
  ],
  "meta": [
    "total_count": 123,    
    "current_page": 1,    
    "per_page": 20,    
  ]
}
```