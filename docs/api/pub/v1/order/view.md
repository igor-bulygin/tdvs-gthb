### Order - View (GET detail)

Example about how to call to Web Service to get specific order

**URL**: `/api/pub/v1/order/<:orderId>`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `403`: Forbidden
  
**Request parameters**:
* `:id`: Id of the order that want to get
  
**Response body**:
See index.md