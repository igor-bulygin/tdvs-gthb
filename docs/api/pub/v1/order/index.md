### Orders - Index (GET list)

Example about how to call to Web Service to get a public list of 
Order

**URL**: `/api/pub/v1/order`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `403`: Not allowed
  
**Request parameters**:
* `id`: Filter a specific product for id
* `person_id`: Filter orders of a specific person only
* `deviser_id`: Filter orders with products of a specific deviser only
* `product_id`: Filter orders with a specific product only
* `order_state`: Filter orders with a specific state
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
    "items": [
        {
            "id": "4161565e",
            "order_state": "order_state_paid",
            "person_id": null,
            "person_info": {
                "first_name": "Michael Joseph",
                "last_name": "Jackson",
                "email": "king@pop.com",
                "phone1": {
                    "prefix": "+34",
                    "number": "19580829"
                },
                "phone2": {
                    "prefix": "+34",
                    "number": "20090625"
                },
                "country": "US",
                "city": "Los Olivos, CA",
                "address": "Neverland",
                "zipcode": "934411"
            },
            "payment_info": {
                "id": "tok_19l2OUJt4mveficFyyeW1Egh",
                "object": "token",
                "card": {
                    "id": "card_19l2OUJt4mveficFjvmFweGK",
                    "object": "card",
                    "address_city": null,
                    "address_country": null,
                    "address_line1": null,
                    "address_line1_check": null,
                    "address_line2": null,
                    "address_state": null,
                    "address_zip": "934411",
                    "address_zip_check": "pass",
                    "brand": "Visa",
                    "country": "US",
                    "cvc_check": "pass",
                    "dynamic_last4": null,
                    "exp_month": 5,
                    "exp_year": 2020,
                    "funding": "credit",
                    "last4": "4242",
                    "metadata": [],
                    "name": "king@pop.com",
                    "tokenization_method": null
                },
                "client_ip": "88.21.115.109",
                "created": 1486587026,
                "email": "king@pop.com",
                "livemode": false,
                "type": "card",
                "used": false
            },
            "products": [
                {
                    "product_id": "656deef5",
                    "price_stock_id": "656deef5b909083",
                    "quantity": 1,
                    "deviser_id": "30a176b",
                    "price": 230,
                    "weight": 0,
                    "options": {
                        "731ct": [
                            "black",
                            "brown",
                            "silver"
                        ],
                        "2500m": [
                            "other"
                        ],
                        "f6b97": [
                            "gold",
                            "silver"
                        ]
                    },
                    "product_name": "Colgante - Broche Altozano",
                    "product_photo": "/uploads/product/656deef5/2016-07-06-14-01-01-a0da7.jpg",
                    "product_slug": "colgante-broche-altozano",
                    "product_url": "http://localhost:8080/work/colgante-broche-altozano/656deef5",
                    "deviser_name": "Jesús Martínez",
                    "deviser_photo": "/uploads/deviser/30a176b/profile.57d81103096e0.png",
                    "deviser_slug": "jesus-martinez",
                    "deviser_url": "http://localhost:8080/deviser/jesus-martinez/30a176b/store"
                }
            ],
            "subtotal": 230,
            "created_at": {
                "sec": 1486586933,
                "usec": 654000
            }
        }
    ],
    "meta": {
        "total_count": 1,
        "current_page": 1,
        "per_page": 99999
    }
}
```