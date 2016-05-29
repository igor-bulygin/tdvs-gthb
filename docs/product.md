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
	], // An array of categories short_ids. Even if only 1 category is selected for the product, this still must be an array.

	collections: [

	], // An array of collections short_ids. Even if the product belongs to 1 collection, this still must be an array.

	name: {
		"en-US": "Name of product",
		"es-ES": "Nombre del producto"
	},

	slug: {
		"en-US": "slug-in-english",
		"es-ES": "slug-in-spanish"		
	}

	description: {
		"en-US": "Description",
		"es-ES": "Descripcion"
	},

	media: {		
		photos: [
			{
				name: 'name of the photo', // This is code-side generated
				tags: [] // Not implemented yet.
			}
		],

		videos_links: [
			"link",
			"link",
			"link"
		],
	}

	// This is an object containing all the selected tags for the product. Note that the STRUCTURE might change depending on the type of the tag, so make sure to check the TAG docs and understand them properly.
	options: [
		'12345': [STRUCTURE],
		'67890': [STRUCTURE]
	],

	sizechart: {
		'short_id': '12345',
		'country': 'US',
		'metric_unit': 'cm',
		'columns': [ // Tis contains the column names of the sizechart for the product
			{
				'en-US': 'Column 1',
				'es-ES': 'Columna 1'
			},
			{
				'en-US': 'Column 2',
				'es-ES': 'Columna 2'
			}
		],
		'values': [
			// A few things to keep in mind in here:
			// The first value in each array is the size label, while the remaining values represent the sizes.
			// There will always be one more value than the number of total columns, that's because of the first value.
			// This example's table would look like:
			//
			//    Column 1    Column 2
			// M  1           2
			// L  2           4
			['M', 1, 2],
			['L', 2, 4]
		]
	},

	bespoke: [], // Not implemented yet.

	preorder: {
		'type': 0 // Check available types in the Preorder model
	},

	returns: {
		'type': 1, // Check available types in the Returns model
		'value': 5
	},

	currency: 'USD', // Check available currency codes

	weight_unit: 'g', // Check available units

	price_stock: [
		// This is a very critical part of each product's data, so make sure to pay attention, read this a couple of times until you're sure what every line means and how things work.
		// The price_stock is a table that holds the price, the stock and the weight of a given combination of 'options' (tags).
		// For example, let's say the product is a T-shirt. One possible combination of options would be 'size: M', 'color: red', 'material: leather'. Note that each tag can have multiple values (color black & white, material: leather & cotton, etc...)
		{
			'options': {
				'size': 'M', // This is available only if the product has a sizechart (it won't exist otherwise)
				'12345': ['foo', 'bar'],
				'67890': ['abc', 'xyz']
			},
			'weight': 0,
			'stock': 1,
			'price': 123
		},
		{
			...
		},
		{
			...
		}
	],

	madetoorder: { // Does the product require some special preparation?

	},

	warranty: {
		'type': 1,
		'value': 365
	}

]
~~~
