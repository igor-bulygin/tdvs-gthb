### People collection

This is how a single person looks like:

~~~javascript
	_id: '507f1f77bcf86cd799439011', // MongoDB _id

	short_id: '4q1c838', // 7 characters long custom ID (url-safe)

	slug: "foo-bar", // This will exist if the person is a deviser. Note that this field isn't an object-like value containing translations for multiple languages. This is by design; devisers aren't supposed to have slugs in more than one language.

    text_short_description: '', // String

    text_biography: '', // String

    account_state: ['draft', 'active', 'blocked'], // String

	type: [0, 1], // Each person can have a single or multiple roles, for example, Client and Deviser.
	// Check the constants in Person model for all the valid values for this. Note that even if the person has only one type, the value would still be an array.

	categories: ["30000", "50000"], //Informational only (for the time being, but it might be used at some point). Note that this field doesn't make any sense for non-devise type users.

	collections: [
		{
			short_id: "10000",
			name: {
				"en-US": "Summer",
				"es-ES": "Verano"
			}
		},
		{
			short_id: "20000",
			name: {
				"en-US": "Winter",
				"es-ES": "Invierno"
			}
		}
	], //Exists only if deviser (not implemented yet)

	personal_info: {
		name: '', // String
		last_name: '', // String
		brand_name: '', // String
		country: 'EN' // Country code
		city: '', // Free text
		surnames: ['', '', ''...], // Array of strings (deprecated)
		bday: , ISODate('1969-12-31'), // MongoDB Date
	},

	media: {
		header: "header.jpg" // This is (at least per current specs) available only for devisers. This is the image that appears as a background in the deviser's profile page.
		header_cropped: "header_cropped.jpg" // Same as header, but cropped...
		profile: "profile.png", // This is the person's avatar (the circular image)
		profile_cropped: "profile_croped.png", // Same as profile, but cropped...
		photos: [
		    "deviser.photo.01234.jpg",
		    "deviser.photo.56789.jpg",
		],
		video_links: [
			"http://foo.bar",
			"https://bar.foo"
		],
	},

    settings: {
        lang: "ES",
        weight_measure: "g"
    },

    press: [

    ],

    videos: [
        "http://foo.bar",
        "https://bar.foo"
    ],

    faq: [

    ]

	credentials: {
		email: 'foo@bar.com', // User email
		password: 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', // Scrypt encrypted password, 64 characters,
		salt: '8f5b39c5069b47f905c0b4616841183c87a9685a7d43f61d0cf50334a6232868', // 64 characters random
		auth_key: '4616841183c84616841183c88f5b3947j3t616841183c8c5069b47f905c0b46164616841183c8841183c87a9685a74616841183c8d43f61d0cf50334a6232868', // 128 characters random
	},

	preferences: {
        language: "en-US", // Look at the keys in config/langs.php for all possible values
        currency: "EUR" // Look at helpers/Utils (Currencies class) for all possible values
    },

    curriculum: '', // String

    created_at: '', // Mongo date

    updated_at: '' // Mongo date

~~~
