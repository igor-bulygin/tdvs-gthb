### Deviser - Patch

Example about how to call to Web Service to update Deviser profile

* URL: /api/priv/v1/profile/deviser
* Method: PATCH
* Response codes: 
  204: Success, without body
  400: Bad request
  403: Not allowed
  
* Request body: 

```
{
  "Person": {
    "categories": [
      "f0cco"
    ],
    "text_biography": {
      "en-US": "my biography"
    },
    "text_short_description": "my new short description",
    "personal_info": {
      "name": "test deviser",
      "brand_name": "my brand name",
      "country": "AN",
      "city": "faketown",
      "surnames": [
        "surname 1",
        "surname 2"
      ]
    }
  },
  "preferences": {
    "lang": "en-US",
    "currency": "EUR"
  },
  "slug": "slug-example"
}
```