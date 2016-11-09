### Product - Patch

Example about how to call to Web Service to update a Product

**URL**: `/api/priv/v1/products/<:id>`

**Method**: `PATCH`

**Response codes**:
* `200`: Success
* `400`: Bad request
* `403`: Not allowed

**Request body**:
* `deviser_id`: Deviser (product's owner) identifier. (Required)
* `name`: Name or title of the product (Multilanguage field)
* `description`: Detailed descripton of the product (Multilanguage field)
* `categories`: [] array with category ids (["f0cco", "1234"])
* `faq`: [] array of documents with info about frequently asked questions. Each element has:
 * `question`: multi-language field with the question ({"en-US": "my quesiton", "es-ES": "mi pregunta"})
 * `answer`: multi-language field with the answer ({"en-US": "my answer", "es-ES": "mi respuesta"})
* `product_state`: available values ["product_state_draft", "product_state_active"]


