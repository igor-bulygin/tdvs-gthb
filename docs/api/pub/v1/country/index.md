### Countries - Index (GET list)

Example about how to call to Web Service to get a public list of 
Countries

**URL**: `/api3/pub/v1/countries`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `name`: Search word/s in name attribute (LIKE)
* `person_type`: Only get countries of persons of specified type
* `only_with_boxes`: Only get countries of persons with boxes
* `only_with_stories`: Only get countries of persons with stories
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 9999)
    
**Response body**:

```
{
  "items": [
    {
      "id": "AD",
      "country_name": "Andorra",
      "currency_code": "EUR",
      "continent": "EU"
    },
    {
      "id": "AI",
      "country_name": "Anguilla",
      "currency_code": "XCD",
      "continent": "NA"
    },
    {
      "id": "AE",
      "country_name": "United Arab Emirates",
      "currency_code": "AED",
      "continent": "AS"
    },
    ...
  ],
  "meta": {
    "total_count": 3,
    "current_page": 1,
    "per_page": 9999
  }
}
```