### Products collection

This is how a single product looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c86t3', // 8 characters long custom ID (url-safe)

	deviser_id: '', // 7 characters long custom ID (url-safe)

	enabled: true,

	categories: [
		'413e5',
		'989io',
		'1vbh8'
	],

	collections: [

	],

	name: {
		"en-US": "Name of product",
		"es-ES": "Nombre del producto"
	},

	description: {
		"en-US": "Description",
		"es-ES": "Descripcion"
	},

	photos: [

	],

	videos: [
		"link",
		"link",
		"link"
	],

	options: [

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

	returns: {
		type: 1
		value: 14
	},

	warranty: {
		type: 1:
		value: 365
	}

]
~~~
