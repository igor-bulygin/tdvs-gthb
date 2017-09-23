### Product - Index (GET list)

Example about how to call to Web Service to get a public list of 
Products

**URL**: `/api/priv/v1/products`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific product for id
* `name`: Search word/s in name attribute (LIKE)
* `q`: Search word/s in name and description (LIKE)
* `deviser`: Filter in products of a specific deviser only
* `categories`: Filter products related with any category of the list
* `product_state`: Filter products in a specific state (product_state_draft, product_state_active)
* `order_type`: String optional. Avaiable values: (new, old, cheapest, expensive)
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

See the structure of the complete item in view.md

```
{
  "items": [
    {
      "id": "a04c31dc",
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