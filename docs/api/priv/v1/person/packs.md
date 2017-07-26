### Person - GET packs

Example about how to call to Web Service to get a list of packs sold by a person

**URL**: `/api/priv/v1/person/<:person_id>/packs`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized
* `403`: Forbidden
* `404`: Not found
  
**Request parameters**:
* `:person_id`: Id of the person (deviser) you want to get the packs
* `pack_state`: Filter packs only in a specific state. 
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

```
Available *pack_state* values:
	- paid
	- aware
	- shipped
	
Also ther is there is two special available values:
	- open: includes paid and aware
	- past: includes shipped
```

**Response body**:

*Important note*: 
This response is a list of order objects **modified** to remove any reference to the packs present 
in the order but that do not belong to that person. Also, there are properties of the order that are no available in this API method to preserve information of the customer

So, keep in mind that this objects **are not a copy of the data in database**, and can not be used to modifiy info in database


```
{
    "items": [
        {
            "id": "3b65b43c",
            "person_id": "1000000",
            "person_info": {
                "slug": "admin",
                "name": "Admin",
                "photo": "http://localhost.thumbor.todevise.com:8000/hwdnt1qH25UFH1_Vf4EWGvkaKEQ=/128x0//imgs/default-avatar.png",
                "url": "/"
            },
            "order_date": {
                "sec": 1500573262,
                "usec": 344000
            },
            "shipping_address": {
                "first_name": "Michael",
                "last_name": "Jackson",
                "vat_id": "12345678Z",
                "email": "jacko@king.of.pop.com",
                "phone": {
                    "prefix": "34",
                    "number": "981981981"
                },
                "country": "ES",
                "city": "Best City",
                "address": "Best street, 15",
                "zipcode": "15177"
            },
            "billing_address": {
                "first_name": "Michael",
                "last_name": "Jackson",
                "vat_id": "12345678Z",
                "email": "jacko@king.of.pop.com",
                "phone": {
                    "prefix": "34",
                    "number": "981981981"
                },
                "country": "ES",
                "city": "Best City",
                "address": "Best street, 15",
                "zipcode": "15177"
            },
            "packs": [
                {
                    "short_id": "bfd65373",
                    "deviser_id": "f351c59",
                    "shipping_type": "standard",
                    "shipping_price": null,
                    "pack_weight": 0,
                    "pack_price": 130,
                    "pack_percentage_fee": null,
                    "currency": null,
                    "weight_measure": null,
                    "products": [
                        {
                            "product_id": "ca60b295",
                            "price_stock_id": "ca60b2951d8eff7",
                            "quantity": 1,
                            "price": 130,
                            "weight": 0,
                            "options": {
                                "size": "38 (S)",
                                "731ct": [
                                    "green",
                                    "blue",
                                    "pink"
                                ],
                                "d0e2g": [
                                    "elastene",
                                    "polyester"
                                ]
                            },
                            "product_info": {
                                "name": "Stark - Printed Velvet Dress",
                                "photo": "http://localhost.thumbor.todevise.com:8000/t90c-IOS5LTcUn-tVJ5GORlhxOo=//uploads/product/ca60b295/2016-11-14-13-17-19-1fe9f.jpg",
                                "slug": "stark-printed-velvet-dress",
                                "url": "http://localhost:8080/work/stark-printed-velvet-dress/ca60b295",
                                "stock": 3
                            }
                        }
                    ]
                }
            ]
        },
        {
            "id": "e0ecbf7c",
            "person_id": "1000000",
            "person_info": {
                "slug": "admin",
                "name": "Admin",
                "photo": "http://localhost.thumbor.todevise.com:8000/hwdnt1qH25UFH1_Vf4EWGvkaKEQ=/128x0//imgs/default-avatar.png",
                "url": "/"
            },
            "order_date": {
                "sec": 1500630872,
                "usec": 505000
            },
            "shipping_address": {
                "first_name": "Michael",
                "last_name": "Jackson",
                "vat_id": "12345678Z",
                "email": "jacko@king.of.pop.com",
                "phone": {
                    "prefix": "34",
                    "number": "981981981"
                },
                "country": "ES",
                "city": "Best City",
                "address": "Best street, 15",
                "zipcode": "15177"
            },
            "billing_address": {
                "first_name": "Michael",
                "last_name": "Jackson",
                "vat_id": "12345678Z",
                "email": "jacko@king.of.pop.com",
                "phone": {
                    "prefix": "34",
                    "number": "981981981"
                },
                "country": "ES",
                "city": "Best City",
                "address": "Best street, 15",
                "zipcode": "15177"
            },
            "packs": [
                {
                    "short_id": "2cc70d4c",
                    "deviser_id": "f351c59",
                    "shipping_type": "standard",
                    "shipping_price": null,
                    "pack_weight": 0,
                    "pack_price": 505,
                    "pack_percentage_fee": null,
                    "currency": null,
                    "weight_measure": null,
                    "products": [
                        {
                            "product_id": "ca60b295",
                            "price_stock_id": "ca60b2951d8eff7",
                            "quantity": 1,
                            "price": 130,
                            "weight": 0,
                            "options": {
                                "size": "38 (S)",
                                "731ct": [
                                    "green",
                                    "blue",
                                    "pink"
                                ],
                                "d0e2g": [
                                    "elastene",
                                    "polyester"
                                ]
                            },
                            "product_info": {
                                "name": "Stark - Printed Velvet Dress",
                                "photo": "http://localhost.thumbor.todevise.com:8000/t90c-IOS5LTcUn-tVJ5GORlhxOo=//uploads/product/ca60b295/2016-11-14-13-17-19-1fe9f.jpg",
                                "slug": "stark-printed-velvet-dress",
                                "url": "http://localhost:8080/work/stark-printed-velvet-dress/ca60b295",
                                "stock": 3
                            }
                        },
                        {
                            "product_id": "852eb305",
                            "price_stock_id": "852eb3055b581fm",
                            "quantity": 3,
                            "price": 75,
                            "weight": 0,
                            "options": {
                                "size": "42 (L)",
                                "731ct": [
                                    "red",
                                    "yellow",
                                    "blue"
                                ],
                                "d0e2g": [
                                    "elastene",
                                    "polyester"
                                ]
                            },
                            "product_info": {
                                "name": "Acrylic - Printed Dress",
                                "photo": "http://localhost.thumbor.todevise.com:8000/2mYu5RL1WeJPFuy-Z1D0ZmiwxpM=//uploads/product/852eb305/2016-11-15-11-04-17-acf14.jpg",
                                "slug": "acrylic-printed-dress",
                                "url": "http://localhost:8080/work/acrylic-printed-dress/852eb305",
                                "stock": 3
                            }
                        },
                        {
                            "product_id": "0f55b159",
                            "price_stock_id": "0f55b15913d324s",
                            "quantity": 2,
                            "price": 75,
                            "weight": 0,
                            "options": {
                                "size": "42 (L)",
                                "731ct": [
                                    "white",
                                    "orange",
                                    "brown"
                                ],
                                "d0e2g": [
                                    "elastene",
                                    "polyester"
                                ]
                            },
                            "product_info": {
                                "name": "Yellowstone - Printed Dress",
                                "photo": "http://localhost.thumbor.todevise.com:8000/mZwxsziDuXy8Z4-74AK0jwQ-olY=//uploads/product/0f55b159/2016-11-15-11-07-28-2cd14.jpg",
                                "slug": "yellowstone-printed-dress",
                                "url": "http://localhost:8080/work/yellowstone-printed-dress/0f55b159",
                                "stock": 3
                            }
                        }
                    ]
                }
            ]
        }
    ],
    "meta": {
        "total_count": 2,
        "current_page": 1,
        "per_page": 99999
    }
}
```

