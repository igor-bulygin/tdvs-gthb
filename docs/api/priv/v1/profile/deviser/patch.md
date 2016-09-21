### Deviser - Patch

Example about how to call to Web Service to update Deviser profile

**URL**: `/api/priv/v1/profile/deviser`

**Method**: `PATCH`

**Response codes**: 
* `204`: Success, without body
* `400`: Bad request
* `403`: Not allowed
  
  
**Request body**: 
* `scenario`: available values ["deviser-update-profile"]
* `categories`: [] array with category ids (["f0cco", "1234"]) 
* `text_biography`: multi-language field with biographies in different languages ({"en-US": "my biography", "es-ES": "mi biografía"}) 
* `text_short_description`: multi-language field with a short description in different languages ({"en-US": "my description", "es-ES": "mi descripcion"}) 
* `personal_info`: 
 * `name`: representative first name 
 * `last_name`: representative last name 
 * `brand_name`: brand name 
 * `city`: city name
 * `country`: code of country ("AN")
* `preferences`: 
 * `lang`: code of default language ("en-US")
 * `currency`: code of default currency ("EUR")
* `media`: 
 * `header`: filename of image to use in header cover ("filename1.jpg")
 * `profile`: filename of image to use in profile avatar ("filename2.jpg")
 * `photos`: [] array of file names to use in "about" gallery (["filename3.jpg", "filename4.jpg"])
* `curriculum`: filename of a document to download with the CV ("deviser.cv.123456.pdf")
* `videos`: [] array of documents with info about videos related with the Deviser. Each element has:
 * `url`: URL of video streaming ("http://youtube.com/asdf")
 * `products`: [] array with products ids that appears in video (["product_id_1", "product_id_2"])
* `faq`: [] array of documents with info about frequently asked questions. Each element has:
 * `question`: multi-language field with the question ({"en-US": "my quesiton", "es-ES": "mi pregunta"})
 * `answer`: multi-language field with the answer ({"en-US": "my answer", "es-ES": "mi respuesta"})
* `account_state`: available values ["active"]

All attributes are required or optional, based on account_state of the Deviser.
 
* You can change any value when the account is in "draft" mode
* You must have some of them filled when you want to change the state to "active", or you want to update an "active" profile.


