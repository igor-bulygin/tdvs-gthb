### Size charts collection

This is how a single size chart looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c8', // 5 characters long custom ID (url-safe)

	enabled: true,

	type: 0, // Specifies the type of size chart. 0 means an internal Todevise size chart. 1 means a deviser modified size chart.

	deviser_id: 1000000, // If type != 0, this will hold the short_id of the deviser to whom this size charts belongs.

	name: {
		'en-US': 'Name of size chart',
		'es-ES': 'Nombre de size chart'
	},

	categories: [
		'413e5',
		'989io',
		'1vbh8'
	],

	metric_unit: "mm",

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

	// This holds the values that were assigned to the dynamically generated table by mixing the countries and the columns.
	// Note that there are as many values as the sum of all the assigned countries + all the columns.
	// This specific example's table would look like this:
	//
	// Spain    USA    Inside leg    Waist
	//
	// 2        S      1             2
	// 4        M      4             7
	//
	values: [
		['2', 'S', 1, 2],
		['4', 'M', 4, 7]
	]

]
~~~
