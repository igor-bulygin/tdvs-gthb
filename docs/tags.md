### Tags collection

This is how a single tag looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c8', // 5 characters long custom ID (url-safe)

	enabled: true,
	required: true,
	stock_and_price: true, // This will add the tag to the stock and price chart
	type: 0, // Check the constants in Tag model for all the valid values
	         // for this
	n_options: 1 // Exists only when type is DROPDOWN
	             //The number of options that can be assigned to a product

	name: {
		'en-US': 'Name of tag',
		'es-ES': 'Nombre de tag'
	},

	description: {
		'en-US': 'Description of tag',
		'es-ES': 'Descripcion de tag'
	},

	categories: [
		'413e5',
		'989io',
		'1vbh8'
	],

	// This holds the options for each tag. Let me clarify what an "option" is. An
	// option is one particular value in a tag. Let's say we have a "Color" tag
	// (DROPDOWN). Each option in that tag would look like this:
	//
	// {
	//     text: {
	//         "en-US": "Green",
	//         "es-ES": "Verde",
	//     },
	//
	//     value: '79de2' // 5 alphanumeric chars long ID
	//     is_color: 0    // 0 == not a color, 1 == color
	// }
	//
	// Now, let's say we have a "Weight" tag (FREETEXT). Each option of that
	// tag would look the same way as the options from the "Color" (DROPDOWN)
	// example, with the following exceptions:
	//
	// * "value" won't exist.
	//
	// * A key called "metric_type" will be created. Check the constants
	// in MetricType model for all the valid values for this.
	//
	// * A key called "type" will be created. Check the constants in
	// TagOption model for all the valid values for this.
	//
	// Example:
	//
	// {
	//     text: {
	//         "en-US": "Wide",
	//         "es-ES": "Ancho",
	//     },
	//     type: 0, // Check the constants in TagOption model for all the
	//              // valid values for this.
	//
	//     metric_type: 1
	// }
	//
	// Keep in mind that free text values are NOT normalized, which means that
	// it's up to you, dear developer, to save them in the same metric units
	// and/or type of data you'll then be able to query. As right now I'm leading
	// this project, I'll use the following normalization rules:
	//
	// Weight: all values will be saved in milligrams
	// Size: all values will be saved in millimeters
	}
	options: [
		{},
		{},
		{}
	]

]
~~~
