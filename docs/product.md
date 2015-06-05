### Products collection

This is how a single product looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c86t', // 7 characters long custom ID (url-safe)

	categories: [
		'413e5',
		'989io',
		'1vbh8'
	],

	// This is an object containing one or multiple prices per country.
	// Each key of the object is a country code, while each key inside that
	// country object is a code for the currency, and the value is the price
	// of the product.
	price: {
		"USA": {
			"USD": 60
		},
		"ESP": {
			"EUR": 54.78
		}
	},


	options : {

	}
]
~~~
