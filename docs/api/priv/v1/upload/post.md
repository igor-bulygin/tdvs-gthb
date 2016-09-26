### Upload - Post

Example about how to call to Web Service to upload a media file

**URL**: `/api/priv/v1/uploads`

**Method**: `POST`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `403`: Not allowed
  
**Request body**: 
* `type`: one of available upload use (`deviser-media-header-original`, `deviser-media-header-cropped`, `deviser-media-profile-original`, `deviser-media-profile-cropped`, `deviser-media-photos`, `deviser-press`, `deviser-curriculum`)
* `deviser_id`: id of the deviser related with the media file (only for use types related with devisers) 
* `product_id`: id of the product related with the media file (only for use types related with products)
* `file`: media file to be uploaded
 
**Response body**:
```
{
  "filename": "deviser.press.57ce89417e569.pdf",
  "url": "/uploads/deviser/123456/deviser.press.57ce89417e569.pdf"
}
```    