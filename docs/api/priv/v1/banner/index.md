### Banners - Index (GET list)

Example about how to call to Web Service to get a private lists of Banners

**URL**: `/api/priv/v1/banner`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific banner for id
* `q`: Search word/s in the title of the banner (LIKE)
* `type`: Banner type (carousel|home_banner)
* `category_id`: Category associated with the banner
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
    "items": [
        {
            "id": "c0204dae",
            "image": {
                "es-ES": "ola1234.jpg",
                "en-US": "ola1234.jpg"
            },
            "alt_text": {
                "es-ES": "Texto alternativo",
                "en-US": "Alternative text"
            },
            "link": {
                "es-ES": "http://www.google.com",
                "en-US": "http://www.google.com"
            },
            "type": "carousel",
            "position": 1,
            "category_id": null,
            "image_link": {
                "es-ES": "/uploads/banner/ola1234.jpg",
                "en-US": "/uploads/banner/ola1234.jpg"
            }
        }
    ],
    "meta": {
        "total_count": 1,
        "current_page": 1,
        "per_page": 99999
    }
}
```