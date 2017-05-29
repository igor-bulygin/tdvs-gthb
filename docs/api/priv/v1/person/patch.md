### Person - Patch

Example about how to call to Web Service to update Person profile

**URL**: `/api/priv/v1/person/<:personId>`

**Method**: `PATCH`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found

**Request parameters**:
* `:personId`: Id of the person that want to upadte
  
**Request body**: 
* `text_short_description`: multi-language field with a short description in different languages ({"en-US": "my description", "es-ES": "mi descripcion"}) 
* `text_biography`: multi-language field with biographies in different languages ({"en-US": "my biography", "es-ES": "mi biografía"})
* `account_state`: available values ["draft", "active"]
* `categories`: [] array with category ids (["f0cco", "1234"]) 
* `personal_info`: 
  * `name`: representative first name 
  * `last_name`: representative last name 
  * `brand_name`: brand name 
  * `country`: code of country ("AN")
  * `city`: city name
  * `street`: street name
  * `number`: street number
  * `phone_number_prefix`: phone number prefix
  * `phone_number`: phone number
  * `zip`: zip postal code
  * `bday`: deprecated
  * `surnames`: deprecated
* `curriculum`: filename of a document to download with the CV ("deviser.cv.123456.pdf")
* `media`: 
  * `header`: filename of image to use in header cover, original version ("filename1.jpg")
  * `header_cropped`: filename of image to use in header cover, cropped version ("filename1.jpg")
  * `profile`: filename of image to use in profile avatar, original version ("filename2.jpg")
  * `profile_cropped`: filename of image to use in profile avatar, cropped version ("filename2.jpg")
  * `photos`: [] array of file names to use in "about" gallery (["filename3.jpg", "filename4.jpg"])
* `settings`:
  * `lang`: code of default language ("en-US")
  * `weight_measure`: mg, g, kg, oz, or lb
  * `bank_info`:
    * `location`: code of country ("US")
    * `bank_name`: name of the bank",
    * `institution_number`
    * `transit_number`
    * `account_number`
    * `swift_bic`
    * `account_type`: posible values: `savings` and `checking`
    * `routing_number`
* `press`: [] array with images names about press related with the Person (["deviser.press.1.jpg", "deviser.press.2.jpg"])
* `videos`: [] array of documents with info about videos related with the Person. Each element has:
  * `url`: URL of video streaming ("http://youtube.com/?v=asdf")
  * `products`: [] array with products ids that appears in video (["product_id_1", "product_id_2"])
* `faq`: [] array of documents with info about frequently asked questions. Each element has:
  * `question`: multi-language field with the question ({"en-US": "my quesiton", "es-ES": "mi pregunta"})
  * `answer`: multi-language field with the answer ({"en-US": "my answer", "es-ES": "mi respuesta"})

All attributes are required or optional, based on account_state and type of person
 
* You can change any value when the account is in "draft" mode
* You must have some of them filled when you want to change the state to "active", or you want to update an "active" profile.


**Response body**:

See the structure of a story in view.md