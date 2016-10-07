### Locations - Index (GET list)

Example about how to call to Web Service to get a public list of 
Locations

**URL**: `/api/pub/v1/locations`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request

**Request parameters**:
* `q`: Search word/s in city name
* `view`: (Optional). One value of the list [`parsed`, `debug`] (Default : `parsed`).
  
**Response body**:

```
{
{
  "items": [
    {
      "description": "Barcelona, Spain",
      "city": "Barcelona",
      "country_code": "ES",
      "country_name": "Spain",
      "terms": [
        {
          "offset": 0,
          "value": "Barcelona"
        },
        {
          "offset": 11,
          "value": "Spain"
        }
      ]
    },
    {
      "description": "Barcelona, Anzoategui, Venezuela",
      "city": "Barcelona",
      "country_code": "VE",
      "country_name": "Venezuela",
      "terms": [
        {
          "offset": 0,
          "value": "Barcelona"
        },
        {
          "offset": 11,
          "value": "Anzoategui"
        },
        {
          "offset": 23,
          "value": "Venezuela"
        }
      ]
    },
    {
      "description": "Barcelona, São Caetano do Sul - State of São Paulo, Brazil",
      "city": "Barcelona",
      "country_code": "BR",
      "country_name": "Brazil",
      "terms": [
        {
          "offset": 0,
          "value": "Barcelona"
        },
        {
          "offset": 11,
          "value": "São Caetano do Sul"
        },
        {
          "offset": 32,
          "value": "State of São Paulo"
        },
        {
          "offset": 52,
          "value": "Brazil"
        }
      ]
    },
    {
      "description": "Barcelona - State of Rio Grande do Norte, Brazil",
      "city": "Barcelona",
      "country_code": "BR",
      "country_name": "Brazil",
      "terms": [
        {
          "offset": 0,
          "value": "Barcelona"
        },
        {
          "offset": 12,
          "value": "State of Rio Grande do Norte"
        },
        {
          "offset": 42,
          "value": "Brazil"
        }
      ]
    },
    {
      "description": "Barcelona, Bicol, Philippines",
      "city": "Barcelona",
      "country_code": "PH",
      "country_name": "Philippines",
      "terms": [
        {
          "offset": 0,
          "value": "Barcelona"
        },
        {
          "offset": 11,
          "value": "Bicol"
        },
        {
          "offset": 18,
          "value": "Philippines"
        }
      ]
    }
  ],
  "meta": {
    "total_count": 5,
    "current_page": 1,
    "per_page": 5
  }
}
```