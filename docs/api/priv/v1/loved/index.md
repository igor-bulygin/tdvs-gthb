### Loved - Index (GET list)

Example about how to call to Web Service to get a public list of Loveds of the connected user

**URL**: `/api/priv/v1/loved`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific product for id
* `product_id`: Filter loveds of a specific product only
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
  "items": [
        "id": "81abd8d",
        "person_id": "3bc8104",
        "product_id": "62b95e5",
        "person": {
            "id": "3bc8104",
            "slug": "sur",
            "name": "Sur studio",
            "url_avatar": "http://thumbor.todevise.com:8000/e2A5JEEDPyNibi6uNh9O-3VvA9Y=/128x0//uploads/deviser/3bc8104/profile.57d17b47612f0.png"
        },
        "product": {
            "id": "62b95e5",
            "slug": "dechoker-for-adults",
            "name": "Dechoker for Adults",
            "media": {
            "photos": [
                {
                "name": "product.photo.5891e4794580d.png",
                "tags": null,
                "not_uploaded": null,
                "main_product_photo": true
                }
            ],
            "description_photos": [],
            "videos_links": null
            },
            "deviser": {
                "id": "bf2583c",
                "slug": "dechoker-dechoker",
                "name": "Dechoker",
                "url_avatar": "http://thumbor.todevise.com:8000/wJMhiMnMQFGMKk4CCw4IfdGKsRE=/128x0//uploads/deviser/bf2583c/profile.5880afabc0b15.png"
            },
            "url_image_preview": "http://thumbor.todevise.com:8000/N8H7t1etecbDDVmcVrl4ZIUMpuw=/128x0//uploads/product/62b95e5/product.photo.5891e4794580d.png",
            "url_images": "/uploads/product/62b95e5/"
            }
        },
        {
        "id": "dfaa0a9",
        "person_id": "3bc8104",
        "product_id": "2df4379",
        "person": {
            "id": "3bc8104",
            "slug": "sur",
            "name": "Sur studio",
            "url_avatar": "http://thumbor.todevise.com:8000/e2A5JEEDPyNibi6uNh9O-3VvA9Y=/128x0//uploads/deviser/3bc8104/profile.57d17b47612f0.png"
        },
        "product": {
            "id": "2df4379",
            "slug": "dechoker-for-toddlers",
            "name": "Dechoker for Toddlers",
            "media": {
            "photos": [
                {
                "name": "product.photo.5891e58c64b32.png",
                "tags": null,
                "not_uploaded": null,
                "main_product_photo": true
                }
            ],
            "description_photos": [],
            "videos_links": null
            },
            "deviser": {
                "id": "bf2583c",
                "slug": "dechoker-dechoker",
                "name": "Dechoker",
                "url_avatar": "http://thumbor.todevise.com:8000/wJMhiMnMQFGMKk4CCw4IfdGKsRE=/128x0//uploads/deviser/bf2583c/profile.5880afabc0b15.png"
            },
            "url_image_preview": "http://thumbor.todevise.com:8000/oRgCokROrcVuQTlc64UOmVJXoSg=/128x0//uploads/product/2df4379/product.photo.5891e58c64b32.png",
            "url_images": "/uploads/product/2df4379/"
        }
    ... 
  ],
  "meta": [
    "total_count": 123,    
    "current_page": 1,    
    "per_page": 20,    
  ]
}
```