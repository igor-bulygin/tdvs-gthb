### Person - Index (GET list)

Example about how to call to Web Service to get a public list of 
Persons

**Note**: This method only returns active accounts

**URL**: `/api/pub/v1/person`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `id`: Filter a specific product for id
* `name`: Search word/s in name attribute (LIKE)
* `q`: Search word/s in name and description (LIKE)
* `categories`: Array of ids, to filter persons related with any category of the list
* `countries`: Array of country codes, to filter persons related with any country of the list
* `type`: Type of account (1: client, 2: deviser, 3: influencer)
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)
* `order_col`: Optional. Name of the column to order by
* `order_direction`: Optional. Direcction of the order. Available values: asc / desc
* `rand`: Optional. If it is present, results are randomized

**Response body**:
//TODO