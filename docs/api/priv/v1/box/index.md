### Boxes - Index (GET list)

Example about how to call to Web Service to get a private lists of Boxes of the connected user

**URL**: `/api/priv/v1/box`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `id`: Filter a specific product for id
* `product_id`: Filter boxes of a specific product only
* `ignore_empty_boxes`: Boolean, to get only boxes with at least one product
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
  "items": [
    {
      "id": "b5f168p",
      "person_id": "3bc8104",
      "person": {
        "id": "3bc8104",
        "slug": "sur",
        "name": "Sur studio",
        "url_avatar": "http://thumbor.todevise.com:8000/e2A5JEEDPyNibi6uNh9O-3VvA9Y=/128x0//uploads/deviser/3bc8104/profile.57d17b47612f0.png"
      },
      "name": "Box 3",
      "description": "Description",
      "products": [
        {
          "id": "9a1a42cd",
          "slug": "business-blue",
          "name": "BUSINESS BLUE",
          "media": {
            "videos_links": [],
            "photos": [
              {
                "name": "2016-06-23-15-15-28-fb5e7.jpg",
                "tags": []
              },
              {
                "name": "2016-06-23-15-15-27-39459.jpg",
                "tags": []
              },
              {
                "name": "2016-06-23-15-15-28-4922z.jpg",
                "tags": [],
                "main_product_photo": true
              }
            ]
          },
          "deviser": {
            "id": "5572aal",
            "slug": "carat23-carat23",
            "name": "Carat23",
            "url_avatar": "http://thumbor.todevise.com:8000/mKK3sADnrYXxAZtd6CE2mi0QUQM=/128x0//uploads/deviser/5572aal/profile.57d7f2f6bcc9e.png"
          },
          "main_photo_128": "http://thumbor.todevise.com:8000/gAjLs54Xw2Qe5-lPAQ4HYiTEePA=/128x0//uploads/product/9a1a42cd/2016-06-23-15-15-28-4922z.jpg",
          "url_images": "/uploads/product/9a1a42cd/"
        },
        {
          "id": "d4d6a1de",
          "slug": "city-bag-off-white-camel",
          "name": "CITY BAG OFF WHITE & CAMEL",
          "media": {
            "videos_links": [],
            "photos": [
              {
                "name": "2016-08-24-10-10-41-adb41.jpg",
                "tags": [],
                "main_product_photo": true
              },
              {
                "name": "2016-08-24-10-10-37-c06ft.jpg",
                "tags": []
              },
              {
                "name": "2016-08-24-10-10-41-51e04.jpg",
                "tags": []
              }
            ]
          },
          "deviser": {
            "id": "5572aal",
            "slug": "carat23-carat23",
            "name": "Carat23",
            "url_avatar": "http://thumbor.todevise.com:8000/mKK3sADnrYXxAZtd6CE2mi0QUQM=/128x0//uploads/deviser/5572aal/profile.57d7f2f6bcc9e.png"
          },
          "main_photo_128": "http://thumbor.todevise.com:8000/-5eVaqRnIlfKtOaLFoSBNYRSghQ=/128x0//uploads/product/d4d6a1de/2016-08-24-10-10-41-adb41.jpg",
          "url_images": "/uploads/product/d4d6a1de/"
        }
      ]
    },
    {
      "id": "5fbbf92",
      "person_id": "3bc8104",
      "person": {
        "id": "3bc8104",
        "slug": "sur",
        "name": "Sur studio",
        "url_avatar": "http://thumbor.todevise.com:8000/e2A5JEEDPyNibi6uNh9O-3VvA9Y=/128x0//uploads/deviser/3bc8104/profile.57d17b47612f0.png"
      },
      "name": "Box 2",
      "description": "Description",
      "products": []
    },
    {
      "id": "87ec23l",
      "person_id": "3bc8104",
      "person": {
        "id": "3bc8104",
        "slug": "sur",
        "name": "Sur studio",
        "url_avatar": "http://thumbor.todevise.com:8000/e2A5JEEDPyNibi6uNh9O-3VvA9Y=/128x0//uploads/deviser/3bc8104/profile.57d17b47612f0.png"
      },
      "name": "One box",
      "description": "Description",
      "products": []
    }
  ],
  "meta": {
    "total_count": 3,
    "current_page": 1,
    "per_page": 20
  }
}
```