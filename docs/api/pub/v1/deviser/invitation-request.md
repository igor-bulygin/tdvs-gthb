### Request invitation to become a Deviser - POST 

Example about how to call to Web Service to request an invitation
to become a Deviser

**URL**: `/api/pub/v1/devisers/invitation-requests`
**Method**: `POST`
**Response codes**: 
* `201`: Success (without body)
* `400`: Bad request
  
**Request parameters**:
* `email`: email of user (required)
* `representative_name`: representative name (required)
* `brand_name`: brand name (optional)
* `phone_number`: phone number (optional)
* `creations_description`: text with their description (required)
* `urls_portfolio`: [] array of url links to their portfolio (required)
* `urls_video`: [] array of url links to a video (optional)
* `observations`: observations (optional)
