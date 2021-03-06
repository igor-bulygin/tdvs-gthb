### Upload - Post

Example about how to call to Web Service to upload a media file

**URL**: `/api/priv/v1/uploads`

**Method**: `POST`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request body**: 
* `type`: one of available upload use:
** `deviser-media-header-original`
** `deviser-media-header-cropped`
** `deviser-media-profile-original`
** `deviser-media-profile-cropped`
** `deviser-media-photos`
** `deviser-press`
** `deviser-curriculum`
** `story-photos`
** `post-photos`
** `person-pack-invoice`
** `banner-image`
** `known-product-photo`
** `unknown-product-photo`
 
* `person_id`: id of the person related with the media file (only for use types related with persons) 
* `product_id`: id of the product related with the media file (only for use types related with products)
* `file`: media file to be uploaded
 
**Response body**:
```
{
  "filename": "deviser.press.57ce89417e569.pdf",
  "url": "/uploads/deviser/123456/deviser.press.57ce89417e569.pdf"
}
```    