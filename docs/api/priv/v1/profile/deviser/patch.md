### Deviser - Patch

Example about how to call to Web Service to update Deviser profile

* URL: `/api/priv/v1/profile/deviser`
* Method: `PATCH`
* Response codes: 
 * `204`: Success, without body
 * `400`: Bad request
 * `403`: Not allowed
  
* Request body: 

```
{
    "scenario": "deviser-..." // available ["deviser-profile-update", "deviser-press-update", "deviser-videos-update", "deviser-faq-update"]
    "slug": "my-name"
    "categories": [
      "f0cco"
    ],
    "text_biography": {
      "en-US": "my biography"
    },
    "text_short_description": "my new short description",
    "personal_info": {
      "name": "My name",
      "surnames": [
        "surname 1"
      ],
      "brand_name": "my brand name",
      "city": "faketown",
      "country": "AN"
    },
    "preferences": {
      "lang": "en-US",
      "currency": "EUR"
    },
    "media": {
      "header": "filename.jpg",
      "profile": "filename.jpg"
    },
    "curriculum": "deviser.cv.123456.pdf",
    "videos": [{
      "url": "http://youtube.com/asdf",
      "products": ["id_1", "id_2"]          // products related
    }],
    "faq": [{
      "question": {"en-US" : "my question"},
      "answer": {"en-US" : "my answer"},
    }]
}
```