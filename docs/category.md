### Categories collection

This is how a single category looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c8', // 5 characters long custom ID (url-safe)

	path: '/298fa/c3b1b/677d1/b310f/4q1c8/', // path of IDs of categories to this category or '/' if root category
	// This should always end with a '/'

	sizecharts: false, // should this category use size charts
	prints: false, // should this category use prints

	name: {
		'en-US': 'Name of category',
		'es-ES': 'Nombre de categoria'
	},

	slug: {
		'en-US': 'url-of-category',
		'es-ES': 'url-de-categoria'
	}
]
~~~
