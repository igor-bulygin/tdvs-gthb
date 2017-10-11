### Countries - Index (GET list)

Example about how to call to Web Service to get a public list of all available shipping Countries

**URL**: `/api/pub/v1/countries/shipping`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* No params
    
**Response body**:

```
[
    {
        "id": "ES",
        "country_name": "Spain",
        "currency_code": "EUR",
        "continent": "EU"
    },
    {
        "id": "US",
        "country_name": "United States",
        "currency_code": "USD",
        "continent": "NA"
    }
]
```