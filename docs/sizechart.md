### Size charts collection

This is how a single size chart looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c8', // 5 characters long custom ID (url-safe)

	enabled: true,

	name: {
		'en-US': 'Name of size chart',
		'es-ES': 'Nombre de size chart'
	},

	categories: [
		'413e5',
		'989io',
		'1vbh8'
	],

	metric_units: 1,

	countries: [
		"ES",
		"US"
	],

	columns: [
		{
			"en-US": "Inside leg",
			"es-ES": "Interior pierna"
		},
		{
			"en-US": "Waist",
			"es-ES":  "Cintura"
		}
	],

	// This holds the values that were assigned to the dynamically generated table
	// by mixing the countries and the columns.
	values: [
		[],
		[]
		...
	]

]
~~~