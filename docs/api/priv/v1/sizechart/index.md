### Sizechart - Index (GET list)

Example about how to call to Web Service to get a private list of
SizeChart

**URL**: `/api/priv/v1/sizechart`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* `deviser_id`: Also include sizecharts of this deviser
* `scope`: Customize filters. Available values: "all" (default: "all). Note that "all" scope means that no limit will be applied
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 100)

NOTE: This method always return, at least, all the "todevise" sizecharts.
    
**Response body**:

```
{
  "items": [
    {
        "id": "19221",
        "name": {
            "en-US": "T-shirts for Men"
        },
        "enabled": true,
        "type": 0,
        "deviser_id": null,
        "categories": [
            "4x9a5",
            "4x8b4"
        ],
        "metric_unit": "mm",
        "countries": [
            "US",
            "EU",
            "GB",
            "IT",
            "JP",
            "AU",
            "DE",
            "DK"
        ],
        "columns": [
            {
                "en-US": "Length"
            },
            {
                "en-US": "Waist"
            },
            {
                "en-US": "Bust"
            },
            {
                "en-US": "Shoulder Width"
            },
            {
                "en-US": "Sleeve Length"
            },
            {
                "en-US": "Sleeve Opening"
            },
            {
                "en-US": "Collar"
            }
        ],
        "values": [
            [
                "2",
                "34",
                "6",
                "38",
                "5",
                "6",
                "32",
                "32",
                "600",
                "450",
                "500",
                "400",
                "200",
                "50",
                "75"
            ],
            [
                "4",
                "36",
                "8",
                "40",
                "7",
                "8",
                "34",
                "34",
                "625",
                "475",
                "525",
                "425",
                "225",
                "75",
                "100"
            ],
            [
                "6",
                "38",
                "10",
                "42",
                "9",
                "10",
                "36",
                "36",
                "650",
                "500",
                "550",
                "450",
                "250",
                "100",
                "125"
            ],
            [
                "8",
                "40",
                "12",
                "44",
                "11",
                "12",
                "38",
                "38",
                "675",
                "525",
                "575",
                "475",
                "275",
                "125",
                "150"
            ],
            [
                "10",
                "42",
                "14",
                "46",
                "13",
                "14",
                "40",
                "40",
                "700",
                "550",
                "600",
                "500",
                "300",
                "125",
                "175"
            ],
            [
                "12",
                "44",
                "16",
                "48",
                "15",
                "16",
                "42",
                "42",
                "725",
                "575",
                "625",
                "525",
                "325",
                "125",
                "175"
            ],
            [
                "14",
                "46",
                "18",
                "50",
                "17",
                "18",
                "44",
                "44",
                "750",
                "600",
                "650",
                "550",
                "350",
                "125",
                "175"
            ],
            [
                "16",
                "48",
                "20",
                "52",
                "19",
                "20",
                "46",
                "46",
                "775",
                "625",
                "675",
                "550",
                "350",
                "150",
                "175"
            ]
        ]
    },
    {
       ...
    }
    ...
   ],
  "meta": {
    "total_returned": 6,
    "total_count": 100,
    "current_page": 1,
    "per_page": 6
  }
}
```