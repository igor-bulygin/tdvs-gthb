### Update person information of the cart - Post

Example about how to call to Web Service to update person information of the cart

**URL**: `/api/pub/v1/cart/<:cartId>/personInfo`

**Method**: `POST`

**Response codes**:
* `200`: Updated
* `400`: Bad request
* `403`: Not allowed

**Request parameters**:
* `:cartId`: Id of the cart that want to update


**Request body**:
* `first_name`:
* `last_name`:
* `email`:
* `phone1`:
* `phone2`:
* `country`:
* `city`:
* `address`:
* `zipcode`:

**Request body example**:
```
{
  "first_name": "Jose",
  "last_name" : "Vázquez",
  "email" : "jose.vazquez.viader@gmail.com",
  "phone1" : {
    "prefix" : "+34",
    "number": "981981981"
  },
  "phone2" : {
    "prefix" : "+34",
    "number": "900900900"
  },
  "country" : "ES",
  "city" : "A Coruña",
  "address" : "Real 1",
  "zipcode" : "15001"
}
```


**Response body**:
```
cart object (view getCart.md)
```



