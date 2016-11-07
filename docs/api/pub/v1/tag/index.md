### Tags - Index (GET list)

Example about how to call to Web Service to get a public list of 
Tags

**URL**: `/api/pub/v1/tags`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 100)
    
**Response body**:

```
{
  "items": [
    {      
      'id' => '91b27',
      'enabled' => true,
      'required' => true,
      'type' => 0,
      'n_options' => 1,
      'name' => "Style",
      'description' => "JEWERLY - Style",
      'categories' => ['3n05x','3abc9',]
      },
    },
    {
      'id' => 'e6a1q',
      'enabled' => true,
      'required' => true,
      'type' => 0,
      'n_options' => 1,
      'name' => "Water Resistance",
      'description' => "WATCHES - Water Resistance",
      'categories' => ['31316','bd70t',]
      },
    },
}
```