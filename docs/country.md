### Countries collection

This is how a single country looks like:

~~~javascript
[
	_id: '507f1f77bcf86cd799439011', // MongoDB _id
	country_code: 'ES'
	country_name: {
		'es-ES': 'España',
		'en-US': 'Spain'
	},
	currency_code: 'EUR', // Note that currency-related code is defined in the Currency model.
	continent: 'EU' // Note that continent names are defined as constants in the Country model.
]
~~~
