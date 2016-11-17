### Metric - Index (GET list)

Example about how to call to Web Service to get a public list of 
Metric

**URL**: `/api/pub/v1/metric`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:

**Response body**:

```
{
  "size": [
    "mm",
    "cm",
    "m",
    "km",
    "in",
    "ft",
    "yd",
    "mi"
  ],
  "weight": [
    "mg",
    "g",
    "kg",
    "oz",
    "lb"
  ]
}
```