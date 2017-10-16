### Currency - Index (GET list)

Example about how to call to Web Service to get a public list of 
Currency

**URL**: `/api3/pub/v1/currency`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `403`: Not allowed
  
**Request parameters**:
* No params

**Response body**:

```
[
  {
	"symbol": "€",
	"text": "EUR",
	"value": "EUR"
  },
  {
	"symbol": "$",
	"text": "USD",
	"value": "USD"
  },
  {
	"symbol": "¥",
	"text": "JPY",
	"value": "JPY"
  },
  {
	"symbol": "лв",
	"text": "BGN",
	"value": "BGN"
  },
  {
	"symbol": "Kč",
	"text": "CZK",
	"value": "CZK"
  },
  {
	"symbol": "kr",
	"text": "DKK",
	"value": "DKK"
  },
  {
	"symbol": "£",
	"text": "GBP",
	"value": "GBP"
  },
  {
	"symbol": "Ft",
	"text": "HUF",
	"value": "HUF"
  },
  {
	"symbol": "zł",
	"text": "PLN",
	"value": "PLN"
  },
  {
	"symbol": "lei",
	"text": "RON",
	"value": "RON"
  },
  {
	"symbol": "kr",
	"text": "SEK",
	"value": "SEK"
  },
  {
	"symbol": "CHF",
	"text": "CHF",
	"value": "CHF"
  },
  {
	"symbol": "kr",
	"text": "NOK",
	"value": "NOK"
  },
  {
	"symbol": "kn",
	"text": "HRK",
	"value": "HRK"
  },
  {
	"symbol": "руб",
	"text": "RUB",
	"value": "RUB"
  },
  {
	"symbol": "YTL",
	"text": "TRY",
	"value": "TRY"
  },
  {
	"symbol": "$",
	"text": "AUD",
	"value": "AUD"
  },
  {
	"symbol": "R$",
	"text": "BRL",
	"value": "BRL"
  },
  {
	"symbol": "$",
	"text": "CAD",
	"value": "CAD"
  },
  {
	"symbol": "元",
	"text": "CNY",
	"value": "CNY"
  },
  {
	"symbol": "元",
	"text": "HKD",
	"value": "HKD"
  },
  {
	"symbol": "Rp",
	"text": "IDR",
	"value": "IDR"
  },
  {
	"symbol": "₪",
	"text": "ILS",
	"value": "ILS"
  },
  {
	"symbol": "₹",
	"text": "INR",
	"value": "INR"
  },
  {
	"symbol": "₩",
	"text": "KRW",
	"value": "KRW"
  },
  {
	"symbol": "$",
	"text": "MXN",
	"value": "MXN"
  },
  {
	"symbol": "RM",
	"text": "MYR",
	"value": "MYR"
  },
  {
	"symbol": "$",
	"text": "NZD",
	"value": "NZD"
  },
  {
	"symbol": "Ph",
	"text": "PHP",
	"value": "PHP"
  },
  {
	"symbol": "$",
	"text": "SGD",
	"value": "SGD"
  },
  {
	"symbol": "฿",
	"text": "THB",
	"value": "THB"
  },
  {
	"symbol": "R",
	"text": "ZAR",
	"value": "ZAR"
  }
]
```