### Countries - Worldwide (GET list)

Example about how to call to Web Service to get a public list of all Continents and Countries

**URL**: `/api3/pub/v1/countries/worldwide`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
  
**Request parameters**:
* No params
    
**Response body**:

```
{
  "code": "WW",
  "name": "World Wide",
  "path": "WW",
  "items": [
	{
	  "code": "AF",
	  "name": "Africa",
	  "path": "WW/AF",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000045"
		  },
		  "country_code": "AO",
		  "country_name": "Angola",
		  "currency_code": "AOA",
		  "continent": "AF",
		  "path": "WW/AF/AO"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000052"
		  },
		  "country_code": "BF",
		  "country_name": "Burkina Faso",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/BF"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000055"
		  },
		  "country_code": "BI",
		  "country_name": "Burundi",
		  "currency_code": "BIF",
		  "continent": "AF",
		  "path": "WW/AF/BI"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000056"
		  },
		  "country_code": "BJ",
		  "country_name": "Benin",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/BJ"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e01000067"
		  },
		  "country_code": "CG",
		  "country_name": "Republic of the Congo",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/CG"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e01000069"
		  },
		  "country_code": "CI",
		  "country_name": "Ivory Coast",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/CI"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e0100006c"
		  },
		  "country_code": "CM",
		  "country_name": "Cameroon",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/CM"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000071"
		  },
		  "country_code": "CV",
		  "country_name": "Cape Verde",
		  "currency_code": "CVE",
		  "continent": "AF",
		  "path": "WW/AF/CV"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000077"
		  },
		  "country_code": "DJ",
		  "country_name": "Djibouti",
		  "currency_code": "DJF",
		  "continent": "AF",
		  "path": "WW/AF/DJ"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000060"
		  },
		  "country_code": "BW",
		  "country_name": "Botswana",
		  "currency_code": "BWP",
		  "continent": "AF",
		  "path": "WW/AF/BW"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000065"
		  },
		  "country_code": "CD",
		  "country_name": "Democratic Republic of the Congo",
		  "currency_code": "CDF",
		  "continent": "AF",
		  "path": "WW/AF/CD"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000066"
		  },
		  "country_code": "CF",
		  "country_name": "Central African Republic",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/CF"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007b"
		  },
		  "country_code": "DZ",
		  "country_name": "Algeria",
		  "currency_code": "DZD",
		  "continent": "AF",
		  "path": "WW/AF/DZ"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007e"
		  },
		  "country_code": "EG",
		  "country_name": "Egypt",
		  "currency_code": "EGP",
		  "continent": "AF",
		  "path": "WW/AF/EG"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007f"
		  },
		  "country_code": "EH",
		  "country_name": "Western Sahara",
		  "currency_code": "MAD",
		  "continent": "AF",
		  "path": "WW/AF/EH"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e01000080"
		  },
		  "country_code": "ER",
		  "country_name": "Eritrea",
		  "currency_code": "ERN",
		  "continent": "AF",
		  "path": "WW/AF/ER"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000089"
		  },
		  "country_code": "GA",
		  "country_name": "Gabon",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/GA"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e0100008f"
		  },
		  "country_code": "GH",
		  "country_name": "Ghana",
		  "currency_code": "GHS",
		  "continent": "AF",
		  "path": "WW/AF/GH"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000092"
		  },
		  "country_code": "GM",
		  "country_name": "Gambia",
		  "currency_code": "GMD",
		  "continent": "AF",
		  "path": "WW/AF/GM"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000093"
		  },
		  "country_code": "GN",
		  "country_name": "Guinea",
		  "currency_code": "GNF",
		  "continent": "AF",
		  "path": "WW/AF/GN"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000095"
		  },
		  "country_code": "GQ",
		  "country_name": "Equatorial Guinea",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/GQ"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e0100009a"
		  },
		  "country_code": "GW",
		  "country_name": "Guinea-Bissau",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/GW"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e01000082"
		  },
		  "country_code": "ET",
		  "country_name": "Ethiopia",
		  "currency_code": "ETB",
		  "continent": "AF",
		  "path": "WW/AF/ET"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000b0"
		  },
		  "country_code": "KE",
		  "country_name": "Kenya",
		  "currency_code": "KES",
		  "continent": "AF",
		  "path": "WW/AF/KE"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b4"
		  },
		  "country_code": "KM",
		  "country_name": "Comoros",
		  "currency_code": "KMF",
		  "continent": "AF",
		  "path": "WW/AF/KM"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c0"
		  },
		  "country_code": "LR",
		  "country_name": "Liberia",
		  "currency_code": "LRD",
		  "continent": "AF",
		  "path": "WW/AF/LR"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c1"
		  },
		  "country_code": "LS",
		  "country_name": "Lesotho",
		  "currency_code": "LSL",
		  "continent": "AF",
		  "path": "WW/AF/LS"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c5"
		  },
		  "country_code": "LY",
		  "country_name": "Libya",
		  "currency_code": "LYD",
		  "continent": "AF",
		  "path": "WW/AF/LY"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000ce"
		  },
		  "country_code": "ML",
		  "country_name": "Mali",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/ML"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d4"
		  },
		  "country_code": "MR",
		  "country_name": "Mauritania",
		  "currency_code": "MRO",
		  "continent": "AF",
		  "path": "WW/AF/MR"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d7"
		  },
		  "country_code": "MU",
		  "country_name": "Mauritius",
		  "currency_code": "MUR",
		  "continent": "AF",
		  "path": "WW/AF/MU"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d9"
		  },
		  "country_code": "MW",
		  "country_name": "Malawi",
		  "currency_code": "MWK",
		  "continent": "AF",
		  "path": "WW/AF/MW"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000dc"
		  },
		  "country_code": "MZ",
		  "country_name": "Mozambique",
		  "currency_code": "MZN",
		  "continent": "AF",
		  "path": "WW/AF/MZ"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000dd"
		  },
		  "country_code": "NA",
		  "country_name": "Namibia",
		  "currency_code": "NAD",
		  "continent": "AF",
		  "path": "WW/AF/NA"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c6"
		  },
		  "country_code": "MA",
		  "country_name": "Morocco",
		  "currency_code": "MAD",
		  "continent": "AF",
		  "path": "WW/AF/MA"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000cb"
		  },
		  "country_code": "MG",
		  "country_name": "Madagascar",
		  "currency_code": "MGA",
		  "continent": "AF",
		  "path": "WW/AF/MG"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000df"
		  },
		  "country_code": "NE",
		  "country_name": "Niger",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/NE"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e1"
		  },
		  "country_code": "NG",
		  "country_name": "Nigeria",
		  "currency_code": "NGN",
		  "continent": "AF",
		  "path": "WW/AF/NG"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000f9"
		  },
		  "country_code": "RE",
		  "country_name": "Réunion",
		  "currency_code": "EUR",
		  "continent": "AF",
		  "path": "WW/AF/RE"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000fd"
		  },
		  "country_code": "RW",
		  "country_name": "Rwanda",
		  "currency_code": "RWF",
		  "continent": "AF",
		  "path": "WW/AF/RW"
		},
		{
		  "_id": {
			"$id": "57a8ae8b55c6212e01000100"
		  },
		  "country_code": "SC",
		  "country_name": "Seychelles",
		  "currency_code": "SCR",
		  "continent": "AF",
		  "path": "WW/AF/SC"
		},
		{
		  "_id": {
			"$id": "57a8ae8b55c6212e01000101"
		  },
		  "country_code": "SD",
		  "country_name": "Sudan",
		  "currency_code": "SDG",
		  "continent": "AF",
		  "path": "WW/AF/SD"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000104"
		  },
		  "country_code": "SH",
		  "country_name": "Saint Helena",
		  "currency_code": "SHP",
		  "continent": "AF",
		  "path": "WW/AF/SH"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000108"
		  },
		  "country_code": "SL",
		  "country_name": "Sierra Leone",
		  "currency_code": "SLL",
		  "continent": "AF",
		  "path": "WW/AF/SL"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e01000112"
		  },
		  "country_code": "SZ",
		  "country_name": "Swaziland",
		  "currency_code": "SZL",
		  "continent": "AF",
		  "path": "WW/AF/SZ"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000114"
		  },
		  "country_code": "TD",
		  "country_name": "Chad",
		  "currency_code": "XAF",
		  "continent": "AF",
		  "path": "WW/AF/TD"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000116"
		  },
		  "country_code": "TG",
		  "country_name": "Togo",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/TG"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e0100011c"
		  },
		  "country_code": "TN",
		  "country_name": "Tunisia",
		  "currency_code": "TND",
		  "continent": "AF",
		  "path": "WW/AF/TN"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e01000122"
		  },
		  "country_code": "TZ",
		  "country_name": "Tanzania",
		  "currency_code": "TZS",
		  "continent": "AF",
		  "path": "WW/AF/TZ"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e0100010a"
		  },
		  "country_code": "SN",
		  "country_name": "Senegal",
		  "currency_code": "XOF",
		  "continent": "AF",
		  "path": "WW/AF/SN"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e0100010b"
		  },
		  "country_code": "SO",
		  "country_name": "Somalia",
		  "currency_code": "SOS",
		  "continent": "AF",
		  "path": "WW/AF/SO"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e0100010d"
		  },
		  "country_code": "SS",
		  "country_name": "South Sudan",
		  "currency_code": "SSP",
		  "continent": "AF",
		  "path": "WW/AF/SS"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e0100010e"
		  },
		  "country_code": "ST",
		  "country_name": "São Tomé and Príncipe",
		  "currency_code": "STD",
		  "continent": "AF",
		  "path": "WW/AF/ST"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000124"
		  },
		  "country_code": "UG",
		  "country_name": "Uganda",
		  "currency_code": "UGX",
		  "continent": "AF",
		  "path": "WW/AF/UG"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000134"
		  },
		  "country_code": "YT",
		  "country_name": "Mayotte",
		  "currency_code": "EUR",
		  "continent": "AF",
		  "path": "WW/AF/YT"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000135"
		  },
		  "country_code": "ZA",
		  "country_name": "South Africa",
		  "currency_code": "ZAR",
		  "continent": "AF",
		  "path": "WW/AF/ZA"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000136"
		  },
		  "country_code": "ZM",
		  "country_name": "Zambia",
		  "currency_code": "ZMW",
		  "continent": "AF",
		  "path": "WW/AF/ZM"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000137"
		  },
		  "country_code": "ZW",
		  "country_name": "Zimbabwe",
		  "currency_code": "ZWL",
		  "continent": "AF",
		  "path": "WW/AF/ZW"
		}
	  ]
	},
	{
	  "code": "AN",
	  "name": "Antarctica",
	  "path": "WW/AN",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000046"
		  },
		  "country_code": "AQ",
		  "country_name": "Antarctica",
		  "currency_code": "",
		  "continent": "AN",
		  "path": "WW/AN/AQ"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e0100005f"
		  },
		  "country_code": "BV",
		  "country_name": "Bouvet Island",
		  "currency_code": "NOK",
		  "continent": "AN",
		  "path": "WW/AN/BV"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e01000097"
		  },
		  "country_code": "GS",
		  "country_name": "South Georgia and the South Sandwich Islands",
		  "currency_code": "GBP",
		  "continent": "AN",
		  "path": "WW/AN/GS"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e0100009d"
		  },
		  "country_code": "HM",
		  "country_name": "Heard Island and McDonald Islands",
		  "currency_code": "AUD",
		  "continent": "AN",
		  "path": "WW/AN/HM"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000115"
		  },
		  "country_code": "TF",
		  "country_name": "French Southern Territories",
		  "currency_code": "EUR",
		  "continent": "AN",
		  "path": "WW/AN/TF"
		}
	  ]
	},
	{
	  "code": "AS",
	  "name": "Asia",
	  "path": "WW/AS",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004d"
		  },
		  "country_code": "AZ",
		  "country_name": "Azerbaijan",
		  "currency_code": "AZN",
		  "continent": "AS",
		  "path": "WW/AS/AZ"
		},
		{
		  "_id": {
			"$id": "57a8ae7955c6212e01000050"
		  },
		  "country_code": "BD",
		  "country_name": "Bangladesh",
		  "currency_code": "BDT",
		  "continent": "AS",
		  "path": "WW/AS/BD"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000054"
		  },
		  "country_code": "BH",
		  "country_name": "Bahrain",
		  "currency_code": "BHD",
		  "continent": "AS",
		  "path": "WW/AS/BH"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e0100003f"
		  },
		  "country_code": "AE",
		  "country_name": "United Arab Emirates",
		  "currency_code": "AED",
		  "continent": "AS",
		  "path": "WW/AS/AE"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000040"
		  },
		  "country_code": "AF",
		  "country_name": "Afghanistan",
		  "currency_code": "AFN",
		  "continent": "AS",
		  "path": "WW/AS/AF"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000044"
		  },
		  "country_code": "AM",
		  "country_name": "Armenia",
		  "currency_code": "AMD",
		  "continent": "AS",
		  "path": "WW/AS/AM"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000059"
		  },
		  "country_code": "BN",
		  "country_name": "Brunei",
		  "currency_code": "BND",
		  "continent": "AS",
		  "path": "WW/AS/BN"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e0100005e"
		  },
		  "country_code": "BT",
		  "country_name": "Bhutan",
		  "currency_code": "BTN",
		  "continent": "AS",
		  "path": "WW/AS/BT"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e0100006d"
		  },
		  "country_code": "CN",
		  "country_name": "China",
		  "currency_code": "CNY",
		  "continent": "AS",
		  "path": "WW/AS/CN"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000073"
		  },
		  "country_code": "CX",
		  "country_name": "Christmas Island",
		  "currency_code": "AUD",
		  "continent": "AS",
		  "path": "WW/AS/CX"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000064"
		  },
		  "country_code": "CC",
		  "country_name": "Cocos [Keeling] Islands",
		  "currency_code": "AUD",
		  "continent": "AS",
		  "path": "WW/AS/CC"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e0100008c"
		  },
		  "country_code": "GE",
		  "country_name": "Georgia",
		  "currency_code": "GEL",
		  "continent": "AS",
		  "path": "WW/AS/GE"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e0100009c"
		  },
		  "country_code": "HK",
		  "country_name": "Hong Kong",
		  "currency_code": "HKD",
		  "continent": "AS",
		  "path": "WW/AS/HK"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a2"
		  },
		  "country_code": "ID",
		  "country_name": "Indonesia",
		  "currency_code": "IDR",
		  "continent": "AS",
		  "path": "WW/AS/ID"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000ae"
		  },
		  "country_code": "JO",
		  "country_name": "Jordan",
		  "currency_code": "JOD",
		  "continent": "AS",
		  "path": "WW/AS/JO"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000af"
		  },
		  "country_code": "JP",
		  "country_name": "Japan",
		  "currency_code": "JPY",
		  "continent": "AS",
		  "path": "WW/AS/JP"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000b1"
		  },
		  "country_code": "KG",
		  "country_name": "Kyrgyzstan",
		  "currency_code": "KGS",
		  "continent": "AS",
		  "path": "WW/AS/KG"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000b2"
		  },
		  "country_code": "KH",
		  "country_name": "Cambodia",
		  "currency_code": "KHR",
		  "continent": "AS",
		  "path": "WW/AS/KH"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b6"
		  },
		  "country_code": "KP",
		  "country_name": "North Korea",
		  "currency_code": "KPW",
		  "continent": "AS",
		  "path": "WW/AS/KP"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b7"
		  },
		  "country_code": "KR",
		  "country_name": "South Korea",
		  "currency_code": "KRW",
		  "continent": "AS",
		  "path": "WW/AS/KR"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b8"
		  },
		  "country_code": "KW",
		  "country_name": "Kuwait",
		  "currency_code": "KWD",
		  "continent": "AS",
		  "path": "WW/AS/KW"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000ba"
		  },
		  "country_code": "KZ",
		  "country_name": "Kazakhstan",
		  "currency_code": "KZT",
		  "continent": "AS",
		  "path": "WW/AS/KZ"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000bb"
		  },
		  "country_code": "LA",
		  "country_name": "Laos",
		  "currency_code": "LAK",
		  "continent": "AS",
		  "path": "WW/AS/LA"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000bc"
		  },
		  "country_code": "LB",
		  "country_name": "Lebanon",
		  "currency_code": "LBP",
		  "continent": "AS",
		  "path": "WW/AS/LB"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a4"
		  },
		  "country_code": "IL",
		  "country_name": "Israel",
		  "currency_code": "ILS",
		  "continent": "AS",
		  "path": "WW/AS/IL"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a6"
		  },
		  "country_code": "IN",
		  "country_name": "India",
		  "currency_code": "INR",
		  "continent": "AS",
		  "path": "WW/AS/IN"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a7"
		  },
		  "country_code": "IO",
		  "country_name": "British Indian Ocean Territory",
		  "currency_code": "USD",
		  "continent": "AS",
		  "path": "WW/AS/IO"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a8"
		  },
		  "country_code": "IQ",
		  "country_name": "Iraq",
		  "currency_code": "IQD",
		  "continent": "AS",
		  "path": "WW/AS/IQ"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000a9"
		  },
		  "country_code": "IR",
		  "country_name": "Iran",
		  "currency_code": "IRR",
		  "continent": "AS",
		  "path": "WW/AS/IR"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000bf"
		  },
		  "country_code": "LK",
		  "country_name": "Sri Lanka",
		  "currency_code": "LKR",
		  "continent": "AS",
		  "path": "WW/AS/LK"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000cf"
		  },
		  "country_code": "MM",
		  "country_name": "Myanmar [Burma]",
		  "currency_code": "MMK",
		  "continent": "AS",
		  "path": "WW/AS/MM"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000d0"
		  },
		  "country_code": "MN",
		  "country_name": "Mongolia",
		  "currency_code": "MNT",
		  "continent": "AS",
		  "path": "WW/AS/MN"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000d1"
		  },
		  "country_code": "MO",
		  "country_name": "Macao",
		  "currency_code": "MOP",
		  "continent": "AS",
		  "path": "WW/AS/MO"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d8"
		  },
		  "country_code": "MV",
		  "country_name": "Maldives",
		  "currency_code": "MVR",
		  "continent": "AS",
		  "path": "WW/AS/MV"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000db"
		  },
		  "country_code": "MY",
		  "country_name": "Malaysia",
		  "currency_code": "MYR",
		  "continent": "AS",
		  "path": "WW/AS/MY"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e5"
		  },
		  "country_code": "NP",
		  "country_name": "Nepal",
		  "currency_code": "NPR",
		  "continent": "AS",
		  "path": "WW/AS/NP"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000ef"
		  },
		  "country_code": "PK",
		  "country_name": "Pakistan",
		  "currency_code": "PKR",
		  "continent": "AS",
		  "path": "WW/AS/PK"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000f4"
		  },
		  "country_code": "PS",
		  "country_name": "Palestine",
		  "currency_code": "ILS",
		  "continent": "AS",
		  "path": "WW/AS/PS"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000f8"
		  },
		  "country_code": "QA",
		  "country_name": "Qatar",
		  "currency_code": "QAR",
		  "continent": "AS",
		  "path": "WW/AS/QA"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000fe"
		  },
		  "country_code": "SA",
		  "country_name": "Saudi Arabia",
		  "currency_code": "SAR",
		  "continent": "AS",
		  "path": "WW/AS/SA"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000e9"
		  },
		  "country_code": "OM",
		  "country_name": "Oman",
		  "currency_code": "OMR",
		  "continent": "AS",
		  "path": "WW/AS/OM"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000ee"
		  },
		  "country_code": "PH",
		  "country_name": "Philippines",
		  "currency_code": "PHP",
		  "continent": "AS",
		  "path": "WW/AS/PH"
		},
		{
		  "_id": {
			"$id": "57a8ae8b55c6212e01000103"
		  },
		  "country_code": "SG",
		  "country_name": "Singapore",
		  "currency_code": "SGD",
		  "continent": "AS",
		  "path": "WW/AS/SG"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e01000111"
		  },
		  "country_code": "SY",
		  "country_name": "Syria",
		  "currency_code": "SYP",
		  "continent": "AS",
		  "path": "WW/AS/SY"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000117"
		  },
		  "country_code": "TH",
		  "country_name": "Thailand",
		  "currency_code": "THB",
		  "continent": "AS",
		  "path": "WW/AS/TH"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000118"
		  },
		  "country_code": "TJ",
		  "country_name": "Tajikistan",
		  "currency_code": "TJS",
		  "continent": "AS",
		  "path": "WW/AS/TJ"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e0100011b"
		  },
		  "country_code": "TM",
		  "country_name": "Turkmenistan",
		  "currency_code": "TMT",
		  "continent": "AS",
		  "path": "WW/AS/TM"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e0100011e"
		  },
		  "country_code": "TR",
		  "country_name": "Turkey",
		  "currency_code": "TRY",
		  "continent": "AS",
		  "path": "WW/AS/TR"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e01000121"
		  },
		  "country_code": "TW",
		  "country_name": "Taiwan",
		  "currency_code": "TWD",
		  "continent": "AS",
		  "path": "WW/AS/TW"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000128"
		  },
		  "country_code": "UZ",
		  "country_name": "Uzbekistan",
		  "currency_code": "UZS",
		  "continent": "AS",
		  "path": "WW/AS/UZ"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e0100012e"
		  },
		  "country_code": "VN",
		  "country_name": "Vietnam",
		  "currency_code": "VND",
		  "continent": "AS",
		  "path": "WW/AS/VN"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000133"
		  },
		  "country_code": "YE",
		  "country_name": "Yemen",
		  "currency_code": "YER",
		  "continent": "AS",
		  "path": "WW/AS/YE"
		}
	  ]
	},
	{
	  "code": "EU",
	  "name": "Europe",
	  "path": "WW/EU",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7955c6212e01000049"
		  },
		  "country_code": "AT",
		  "country_name": "Austria",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/AT"
		},
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004c"
		  },
		  "country_code": "AX",
		  "country_name": "Åland",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/AX"
		},
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004e"
		  },
		  "country_code": "BA",
		  "country_name": "Bosnia and Herzegovina",
		  "currency_code": "BAM",
		  "continent": "EU",
		  "path": "WW/EU/BA"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000051"
		  },
		  "country_code": "BE",
		  "country_name": "Belgium",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/BE"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000053"
		  },
		  "country_code": "BG",
		  "country_name": "Bulgaria",
		  "currency_code": "BGN",
		  "continent": "EU",
		  "path": "WW/EU/BG"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e0100003e"
		  },
		  "country_code": "AD",
		  "country_name": "Andorra",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/AD"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000043"
		  },
		  "country_code": "AL",
		  "country_name": "Albania",
		  "currency_code": "ALL",
		  "continent": "EU",
		  "path": "WW/EU/AL"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e01000068"
		  },
		  "country_code": "CH",
		  "country_name": "Switzerland",
		  "currency_code": "CHF",
		  "continent": "EU",
		  "path": "WW/EU/CH"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000074"
		  },
		  "country_code": "CY",
		  "country_name": "Cyprus",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/CY"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000075"
		  },
		  "country_code": "CZ",
		  "country_name": "Czech Republic",
		  "currency_code": "CZK",
		  "continent": "EU",
		  "path": "WW/EU/CZ"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000076"
		  },
		  "country_code": "DE",
		  "country_name": "Germany",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/DE"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000078"
		  },
		  "country_code": "DK",
		  "country_name": "Denmark",
		  "currency_code": "DKK",
		  "continent": "EU",
		  "path": "WW/EU/DK"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000061"
		  },
		  "country_code": "BY",
		  "country_name": "Belarus",
		  "currency_code": "BYR",
		  "continent": "EU",
		  "path": "WW/EU/BY"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007d"
		  },
		  "country_code": "EE",
		  "country_name": "Estonia",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/EE"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e01000081"
		  },
		  "country_code": "ES",
		  "country_name": "Spain",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/ES"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e0100008a"
		  },
		  "country_code": "GB",
		  "country_name": "United Kingdom",
		  "currency_code": "GBP",
		  "continent": "EU",
		  "path": "WW/EU/GB"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e0100008e"
		  },
		  "country_code": "GG",
		  "country_name": "Guernsey",
		  "currency_code": "GBP",
		  "continent": "EU",
		  "path": "WW/EU/GG"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000090"
		  },
		  "country_code": "GI",
		  "country_name": "Gibraltar",
		  "currency_code": "GIP",
		  "continent": "EU",
		  "path": "WW/EU/GI"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e01000096"
		  },
		  "country_code": "GR",
		  "country_name": "Greece",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/GR"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000083"
		  },
		  "country_code": "FI",
		  "country_name": "Finland",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/FI"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000087"
		  },
		  "country_code": "FO",
		  "country_name": "Faroe Islands",
		  "currency_code": "DKK",
		  "continent": "EU",
		  "path": "WW/EU/FO"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000088"
		  },
		  "country_code": "FR",
		  "country_name": "France",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/FR"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e0100009f"
		  },
		  "country_code": "HR",
		  "country_name": "Croatia",
		  "currency_code": "HRK",
		  "continent": "EU",
		  "path": "WW/EU/HR"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a1"
		  },
		  "country_code": "HU",
		  "country_name": "Hungary",
		  "currency_code": "HUF",
		  "continent": "EU",
		  "path": "WW/EU/HU"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a3"
		  },
		  "country_code": "IE",
		  "country_name": "Ireland",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/IE"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000ab"
		  },
		  "country_code": "IT",
		  "country_name": "Italy",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/IT"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000ac"
		  },
		  "country_code": "JE",
		  "country_name": "Jersey",
		  "currency_code": "GBP",
		  "continent": "EU",
		  "path": "WW/EU/JE"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a5"
		  },
		  "country_code": "IM",
		  "country_name": "Isle of Man",
		  "currency_code": "GBP",
		  "continent": "EU",
		  "path": "WW/EU/IM"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000aa"
		  },
		  "country_code": "IS",
		  "country_name": "Iceland",
		  "currency_code": "ISK",
		  "continent": "EU",
		  "path": "WW/EU/IS"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000be"
		  },
		  "country_code": "LI",
		  "country_name": "Liechtenstein",
		  "currency_code": "CHF",
		  "continent": "EU",
		  "path": "WW/EU/LI"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c2"
		  },
		  "country_code": "LT",
		  "country_name": "Lithuania",
		  "currency_code": "LTL",
		  "continent": "EU",
		  "path": "WW/EU/LT"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c3"
		  },
		  "country_code": "LU",
		  "country_name": "Luxembourg",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/LU"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c4"
		  },
		  "country_code": "LV",
		  "country_name": "Latvia",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/LV"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000cd"
		  },
		  "country_code": "MK",
		  "country_name": "Macedonia",
		  "currency_code": "MKD",
		  "continent": "EU",
		  "path": "WW/EU/MK"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d6"
		  },
		  "country_code": "MT",
		  "country_name": "Malta",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/MT"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c7"
		  },
		  "country_code": "MC",
		  "country_name": "Monaco",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/MC"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c8"
		  },
		  "country_code": "MD",
		  "country_name": "Moldova",
		  "currency_code": "MDL",
		  "continent": "EU",
		  "path": "WW/EU/MD"
		},
		{
		  "_id": {
			"$id": "57a8ae8555c6212e010000c9"
		  },
		  "country_code": "ME",
		  "country_name": "Montenegro",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/ME"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e3"
		  },
		  "country_code": "NL",
		  "country_name": "Netherlands",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/NL"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e4"
		  },
		  "country_code": "NO",
		  "country_name": "Norway",
		  "currency_code": "NOK",
		  "continent": "EU",
		  "path": "WW/EU/NO"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000f0"
		  },
		  "country_code": "PL",
		  "country_name": "Poland",
		  "currency_code": "PLN",
		  "continent": "EU",
		  "path": "WW/EU/PL"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000f5"
		  },
		  "country_code": "PT",
		  "country_name": "Portugal",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/PT"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000fa"
		  },
		  "country_code": "RO",
		  "country_name": "Romania",
		  "currency_code": "RON",
		  "continent": "EU",
		  "path": "WW/EU/RO"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000fb"
		  },
		  "country_code": "RS",
		  "country_name": "Serbia",
		  "currency_code": "RSD",
		  "continent": "EU",
		  "path": "WW/EU/RS"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000fc"
		  },
		  "country_code": "RU",
		  "country_name": "Russia",
		  "currency_code": "RUB",
		  "continent": "EU",
		  "path": "WW/EU/RU"
		},
		{
		  "_id": {
			"$id": "57a8ae8b55c6212e01000102"
		  },
		  "country_code": "SE",
		  "country_name": "Sweden",
		  "currency_code": "SEK",
		  "continent": "EU",
		  "path": "WW/EU/SE"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000105"
		  },
		  "country_code": "SI",
		  "country_name": "Slovenia",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/SI"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000106"
		  },
		  "country_code": "SJ",
		  "country_name": "Svalbard and Jan Mayen",
		  "currency_code": "NOK",
		  "continent": "EU",
		  "path": "WW/EU/SJ"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000107"
		  },
		  "country_code": "SK",
		  "country_name": "Slovakia",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/SK"
		},
		{
		  "_id": {
			"$id": "57a8ae8c55c6212e01000109"
		  },
		  "country_code": "SM",
		  "country_name": "San Marino",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/SM"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e01000123"
		  },
		  "country_code": "UA",
		  "country_name": "Ukraine",
		  "currency_code": "UAH",
		  "continent": "EU",
		  "path": "WW/EU/UA"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000129"
		  },
		  "country_code": "VA",
		  "country_name": "Vatican City",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/VA"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000132"
		  },
		  "country_code": "XK",
		  "country_name": "Kosovo",
		  "currency_code": "EUR",
		  "continent": "EU",
		  "path": "WW/EU/XK"
		}
	  ]
	},
	{
	  "code": "NA",
	  "name": "North America",
	  "path": "WW/NA",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004b"
		  },
		  "country_code": "AW",
		  "country_name": "Aruba",
		  "currency_code": "AWG",
		  "continent": "NA",
		  "path": "WW/NA/AW"
		},
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004f"
		  },
		  "country_code": "BB",
		  "country_name": "Barbados",
		  "currency_code": "BBD",
		  "continent": "NA",
		  "path": "WW/NA/BB"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000041"
		  },
		  "country_code": "AG",
		  "country_name": "Antigua and Barbuda",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/AG"
		},
		{
		  "_id": {
			"$id": "57a8ae7855c6212e01000042"
		  },
		  "country_code": "AI",
		  "country_name": "Anguilla",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/AI"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000057"
		  },
		  "country_code": "BL",
		  "country_name": "Saint Barthélemy",
		  "currency_code": "EUR",
		  "continent": "NA",
		  "path": "WW/NA/BL"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e01000058"
		  },
		  "country_code": "BM",
		  "country_name": "Bermuda",
		  "currency_code": "BMD",
		  "continent": "NA",
		  "path": "WW/NA/BM"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e0100005b"
		  },
		  "country_code": "BQ",
		  "country_name": "Bonaire",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/BQ"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e0100005d"
		  },
		  "country_code": "BS",
		  "country_name": "Bahamas",
		  "currency_code": "BSD",
		  "continent": "NA",
		  "path": "WW/NA/BS"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e0100006f"
		  },
		  "country_code": "CR",
		  "country_name": "Costa Rica",
		  "currency_code": "CRC",
		  "continent": "NA",
		  "path": "WW/NA/CR"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000070"
		  },
		  "country_code": "CU",
		  "country_name": "Cuba",
		  "currency_code": "CUP",
		  "continent": "NA",
		  "path": "WW/NA/CU"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e01000072"
		  },
		  "country_code": "CW",
		  "country_name": "Curacao",
		  "currency_code": "ANG",
		  "continent": "NA",
		  "path": "WW/NA/CW"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000062"
		  },
		  "country_code": "BZ",
		  "country_name": "Belize",
		  "currency_code": "BZD",
		  "continent": "NA",
		  "path": "WW/NA/BZ"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e01000063"
		  },
		  "country_code": "CA",
		  "country_name": "Canada",
		  "currency_code": "CAD",
		  "continent": "NA",
		  "path": "WW/NA/CA"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e01000079"
		  },
		  "country_code": "DM",
		  "country_name": "Dominica",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/DM"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007a"
		  },
		  "country_code": "DO",
		  "country_name": "Dominican Republic",
		  "currency_code": "DOP",
		  "continent": "NA",
		  "path": "WW/NA/DO"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e0100008b"
		  },
		  "country_code": "GD",
		  "country_name": "Grenada",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/GD"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000091"
		  },
		  "country_code": "GL",
		  "country_name": "Greenland",
		  "currency_code": "DKK",
		  "continent": "NA",
		  "path": "WW/NA/GL"
		},
		{
		  "_id": {
			"$id": "57a8ae8055c6212e01000094"
		  },
		  "country_code": "GP",
		  "country_name": "Guadeloupe",
		  "currency_code": "EUR",
		  "continent": "NA",
		  "path": "WW/NA/GP"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e01000098"
		  },
		  "country_code": "GT",
		  "country_name": "Guatemala",
		  "currency_code": "GTQ",
		  "continent": "NA",
		  "path": "WW/NA/GT"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e0100009e"
		  },
		  "country_code": "HN",
		  "country_name": "Honduras",
		  "currency_code": "HNL",
		  "continent": "NA",
		  "path": "WW/NA/HN"
		},
		{
		  "_id": {
			"$id": "57a8ae8255c6212e010000a0"
		  },
		  "country_code": "HT",
		  "country_name": "Haiti",
		  "currency_code": "HTG",
		  "continent": "NA",
		  "path": "WW/NA/HT"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000ad"
		  },
		  "country_code": "JM",
		  "country_name": "Jamaica",
		  "currency_code": "JMD",
		  "continent": "NA",
		  "path": "WW/NA/JM"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b5"
		  },
		  "country_code": "KN",
		  "country_name": "Saint Kitts and Nevis",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/KN"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000b9"
		  },
		  "country_code": "KY",
		  "country_name": "Cayman Islands",
		  "currency_code": "KYD",
		  "continent": "NA",
		  "path": "WW/NA/KY"
		},
		{
		  "_id": {
			"$id": "57a8ae8455c6212e010000bd"
		  },
		  "country_code": "LC",
		  "country_name": "Saint Lucia",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/LC"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000d3"
		  },
		  "country_code": "MQ",
		  "country_name": "Martinique",
		  "currency_code": "EUR",
		  "continent": "NA",
		  "path": "WW/NA/MQ"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000d5"
		  },
		  "country_code": "MS",
		  "country_name": "Montserrat",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/MS"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000da"
		  },
		  "country_code": "MX",
		  "country_name": "Mexico",
		  "currency_code": "MXN",
		  "continent": "NA",
		  "path": "WW/NA/MX"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000ca"
		  },
		  "country_code": "MF",
		  "country_name": "Saint Martin",
		  "currency_code": "EUR",
		  "continent": "NA",
		  "path": "WW/NA/MF"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e2"
		  },
		  "country_code": "NI",
		  "country_name": "Nicaragua",
		  "currency_code": "NIO",
		  "continent": "NA",
		  "path": "WW/NA/NI"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000f1"
		  },
		  "country_code": "PM",
		  "country_name": "Saint Pierre and Miquelon",
		  "currency_code": "EUR",
		  "continent": "NA",
		  "path": "WW/NA/PM"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000f3"
		  },
		  "country_code": "PR",
		  "country_name": "Puerto Rico",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/PR"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000ea"
		  },
		  "country_code": "PA",
		  "country_name": "Panama",
		  "currency_code": "PAB",
		  "continent": "NA",
		  "path": "WW/NA/PA"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000113"
		  },
		  "country_code": "TC",
		  "country_name": "Turks and Caicos Islands",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/TC"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e0100011f"
		  },
		  "country_code": "TT",
		  "country_name": "Trinidad and Tobago",
		  "currency_code": "TTD",
		  "continent": "NA",
		  "path": "WW/NA/TT"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e0100010f"
		  },
		  "country_code": "SV",
		  "country_name": "El Salvador",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/SV"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e01000110"
		  },
		  "country_code": "SX",
		  "country_name": "Sint Maarten",
		  "currency_code": "ANG",
		  "continent": "NA",
		  "path": "WW/NA/SX"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000126"
		  },
		  "country_code": "US",
		  "country_name": "United States",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/US"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e0100012a"
		  },
		  "country_code": "VC",
		  "country_name": "Saint Vincent and the Grenadines",
		  "currency_code": "XCD",
		  "continent": "NA",
		  "path": "WW/NA/VC"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e0100012c"
		  },
		  "country_code": "VG",
		  "country_name": "British Virgin Islands",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/VG"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e0100012d"
		  },
		  "country_code": "VI",
		  "country_name": "U.S. Virgin Islands",
		  "currency_code": "USD",
		  "continent": "NA",
		  "path": "WW/NA/VI"
		}
	  ]
	},
	{
	  "code": "SA",
	  "name": "South America",
	  "path": "WW/SA",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7955c6212e01000047"
		  },
		  "country_code": "AR",
		  "country_name": "Argentina",
		  "currency_code": "ARS",
		  "continent": "SA",
		  "path": "WW/SA/AR"
		},
		{
		  "_id": {
			"$id": "57a8ae7a55c6212e0100005a"
		  },
		  "country_code": "BO",
		  "country_name": "Bolivia",
		  "currency_code": "BOB",
		  "continent": "SA",
		  "path": "WW/SA/BO"
		},
		{
		  "_id": {
			"$id": "57a8ae7b55c6212e0100005c"
		  },
		  "country_code": "BR",
		  "country_name": "Brazil",
		  "currency_code": "BRL",
		  "continent": "SA",
		  "path": "WW/SA/BR"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e0100006b"
		  },
		  "country_code": "CL",
		  "country_name": "Chile",
		  "currency_code": "CLP",
		  "continent": "SA",
		  "path": "WW/SA/CL"
		},
		{
		  "_id": {
			"$id": "57a8ae7d55c6212e0100006e"
		  },
		  "country_code": "CO",
		  "country_name": "Colombia",
		  "currency_code": "COP",
		  "continent": "SA",
		  "path": "WW/SA/CO"
		},
		{
		  "_id": {
			"$id": "57a8ae7e55c6212e0100007c"
		  },
		  "country_code": "EC",
		  "country_name": "Ecuador",
		  "currency_code": "USD",
		  "continent": "SA",
		  "path": "WW/SA/EC"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e0100008d"
		  },
		  "country_code": "GF",
		  "country_name": "French Guiana",
		  "currency_code": "EUR",
		  "continent": "SA",
		  "path": "WW/SA/GF"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000085"
		  },
		  "country_code": "FK",
		  "country_name": "Falkland Islands",
		  "currency_code": "FKP",
		  "continent": "SA",
		  "path": "WW/SA/FK"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e0100009b"
		  },
		  "country_code": "GY",
		  "country_name": "Guyana",
		  "currency_code": "GYD",
		  "continent": "SA",
		  "path": "WW/SA/GY"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000f7"
		  },
		  "country_code": "PY",
		  "country_name": "Paraguay",
		  "currency_code": "PYG",
		  "continent": "SA",
		  "path": "WW/SA/PY"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000eb"
		  },
		  "country_code": "PE",
		  "country_name": "Peru",
		  "currency_code": "PEN",
		  "continent": "SA",
		  "path": "WW/SA/PE"
		},
		{
		  "_id": {
			"$id": "57a8ae8d55c6212e0100010c"
		  },
		  "country_code": "SR",
		  "country_name": "Suriname",
		  "currency_code": "SRD",
		  "continent": "SA",
		  "path": "WW/SA/SR"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000127"
		  },
		  "country_code": "UY",
		  "country_name": "Uruguay",
		  "currency_code": "UYU",
		  "continent": "SA",
		  "path": "WW/SA/UY"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e0100012b"
		  },
		  "country_code": "VE",
		  "country_name": "Venezuela",
		  "currency_code": "VEF",
		  "continent": "SA",
		  "path": "WW/SA/VE"
		}
	  ]
	},
	{
	  "code": "OC",
	  "name": "Australia",
	  "path": "WW/OC",
	  "items": [
		{
		  "_id": {
			"$id": "57a8ae7955c6212e01000048"
		  },
		  "country_code": "AS",
		  "country_name": "American Samoa",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/AS"
		},
		{
		  "_id": {
			"$id": "57a8ae7955c6212e0100004a"
		  },
		  "country_code": "AU",
		  "country_name": "Australia",
		  "currency_code": "AUD",
		  "continent": "OC",
		  "path": "WW/OC/AU"
		},
		{
		  "_id": {
			"$id": "57a8ae7c55c6212e0100006a"
		  },
		  "country_code": "CK",
		  "country_name": "Cook Islands",
		  "currency_code": "NZD",
		  "continent": "OC",
		  "path": "WW/OC/CK"
		},
		{
		  "_id": {
			"$id": "57a8ae8155c6212e01000099"
		  },
		  "country_code": "GU",
		  "country_name": "Guam",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/GU"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000084"
		  },
		  "country_code": "FJ",
		  "country_name": "Fiji",
		  "currency_code": "FJD",
		  "continent": "OC",
		  "path": "WW/OC/FJ"
		},
		{
		  "_id": {
			"$id": "57a8ae7f55c6212e01000086"
		  },
		  "country_code": "FM",
		  "country_name": "Micronesia",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/FM"
		},
		{
		  "_id": {
			"$id": "57a8ae8355c6212e010000b3"
		  },
		  "country_code": "KI",
		  "country_name": "Kiribati",
		  "currency_code": "AUD",
		  "continent": "OC",
		  "path": "WW/OC/KI"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000d2"
		  },
		  "country_code": "MP",
		  "country_name": "Northern Mariana Islands",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/MP"
		},
		{
		  "_id": {
			"$id": "57a8ae8755c6212e010000de"
		  },
		  "country_code": "NC",
		  "country_name": "New Caledonia",
		  "currency_code": "XPF",
		  "continent": "OC",
		  "path": "WW/OC/NC"
		},
		{
		  "_id": {
			"$id": "57a8ae8655c6212e010000cc"
		  },
		  "country_code": "MH",
		  "country_name": "Marshall Islands",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/MH"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e0"
		  },
		  "country_code": "NF",
		  "country_name": "Norfolk Island",
		  "currency_code": "AUD",
		  "continent": "OC",
		  "path": "WW/OC/NF"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e6"
		  },
		  "country_code": "NR",
		  "country_name": "Nauru",
		  "currency_code": "AUD",
		  "continent": "OC",
		  "path": "WW/OC/NR"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e7"
		  },
		  "country_code": "NU",
		  "country_name": "Niue",
		  "currency_code": "NZD",
		  "continent": "OC",
		  "path": "WW/OC/NU"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000f2"
		  },
		  "country_code": "PN",
		  "country_name": "Pitcairn Islands",
		  "currency_code": "NZD",
		  "continent": "OC",
		  "path": "WW/OC/PN"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000f6"
		  },
		  "country_code": "PW",
		  "country_name": "Palau",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/PW"
		},
		{
		  "_id": {
			"$id": "57a8ae8a55c6212e010000ff"
		  },
		  "country_code": "SB",
		  "country_name": "Solomon Islands",
		  "currency_code": "SBD",
		  "continent": "OC",
		  "path": "WW/OC/SB"
		},
		{
		  "_id": {
			"$id": "57a8ae8855c6212e010000e8"
		  },
		  "country_code": "NZ",
		  "country_name": "New Zealand",
		  "currency_code": "NZD",
		  "continent": "OC",
		  "path": "WW/OC/NZ"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000ec"
		  },
		  "country_code": "PF",
		  "country_name": "French Polynesia",
		  "currency_code": "XPF",
		  "continent": "OC",
		  "path": "WW/OC/PF"
		},
		{
		  "_id": {
			"$id": "57a8ae8955c6212e010000ed"
		  },
		  "country_code": "PG",
		  "country_name": "Papua New Guinea",
		  "currency_code": "PGK",
		  "continent": "OC",
		  "path": "WW/OC/PG"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e01000119"
		  },
		  "country_code": "TK",
		  "country_name": "Tokelau",
		  "currency_code": "NZD",
		  "continent": "OC",
		  "path": "WW/OC/TK"
		},
		{
		  "_id": {
			"$id": "57a8ae8e55c6212e0100011a"
		  },
		  "country_code": "TL",
		  "country_name": "East Timor",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/TL"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e0100011d"
		  },
		  "country_code": "TO",
		  "country_name": "Tonga",
		  "currency_code": "TOP",
		  "continent": "OC",
		  "path": "WW/OC/TO"
		},
		{
		  "_id": {
			"$id": "57a8ae8f55c6212e01000120"
		  },
		  "country_code": "TV",
		  "country_name": "Tuvalu",
		  "currency_code": "AUD",
		  "continent": "OC",
		  "path": "WW/OC/TV"
		},
		{
		  "_id": {
			"$id": "57a8ae9055c6212e01000125"
		  },
		  "country_code": "UM",
		  "country_name": "U.S. Minor Outlying Islands",
		  "currency_code": "USD",
		  "continent": "OC",
		  "path": "WW/OC/UM"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e0100012f"
		  },
		  "country_code": "VU",
		  "country_name": "Vanuatu",
		  "currency_code": "VUV",
		  "continent": "OC",
		  "path": "WW/OC/VU"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000130"
		  },
		  "country_code": "WF",
		  "country_name": "Wallis and Futuna",
		  "currency_code": "XPF",
		  "continent": "OC",
		  "path": "WW/OC/WF"
		},
		{
		  "_id": {
			"$id": "57a8ae9155c6212e01000131"
		  },
		  "country_code": "WS",
		  "country_name": "Samoa",
		  "currency_code": "WST",
		  "continent": "OC",
		  "path": "WW/OC/WS"
		}
	  ]
	}
  ]
}
```