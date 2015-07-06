### People collection

This is how a single person looks like:

~~~javascript
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c838', // 7 characters long custom ID (url-safe)

	"slug": "foo-bar", // This will exist if the person is a deviser

	"categories": ["30000", "50000"], //Informational only.

	type: [0, 1], // Each person can have a single or multiple roles, for example, Client and Deviser.
	// Check the constants in Person model for all the valid values for this.

	personal_info: {
		name: '', // String
		surnames: ['', '', ''...], // Array of strings
		bday: , ISODate('1969-12-31'), // MongoDB Date
		country: 'EN', // Country code
		biography: '' // This exists if the person is a deviser
	},

	media: {
		profile: "profile.png",
		header: "header.jpg"
	},

	credentials: {
		email: 'foo@bar.com', // User email
		password: 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', // Scrypt encrypted password, 64 characters,
		salt: '8f5b39c5069b47f905c0b4616841183c87a9685a7d43f61d0cf50334a6232868', // 64 characters random
		auth_key: '4616841183c84616841183c88f5b3947j3t616841183c8c5069b47f905c0b46164616841183c8841183c87a9685a74616841183c8d43f61d0cf50334a6232868', // 128 characters random
	},

	preferences: {
		language: "en-US", // Look at the keys in config/langs.php for all possible values
		currency: "EUR" // Look at helpers/Utils (Currencies class) for all possible values
	}
~~~