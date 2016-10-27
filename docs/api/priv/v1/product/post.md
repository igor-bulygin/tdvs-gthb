### Product - Post

Example about how to call to Web Service to create a new Product

**URL**: `/api/priv/v1/products`

**Method**: `POST`

**Response codes**: 
* `201`: Created
* `400`: Bad request
* `403`: Not allowed
  
**Request body**: 
* `name`: Name or title of the product (Multilanguage field)
* `description`: Detailed descripton of the product (Multilanguage field)
* `categories`: [] array with category ids (["f0cco", "1234"]) 
* `photos`: [] array with custom data
 * `name`: filename
 * `main_product_photo`: (Boolean)
 * `title`: title for the image (Multilanguage field)
 * `description`: description of the image (Multilanguage field)

