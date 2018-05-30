### Timeline - Index (GET list)

Example about how to call to Web Service to get the timeline of the connected user

**URL**: `/api/priv/v1/timeline`

**Method**: `GET`

**Response codes**: 
* `200`: Success
* `400`: Bad request
* `401`: Unauthorized 
* `403`: Forbidden
  
**Request parameters**:
* `page`: Set the result page that want to be retrieved (default: 1)
* `limit`: Limit the results returned for page (default: 20)

**Response body**:

```
{
    "items": [
        {
            "id": "19ebe5",
            "person": {
                "id": "e893746",
                "slug": "judas-arrieta",
                "name": "Judas Arrieta",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/IlYZjzIT4nWX-I4n2o9IMKXxqQM=/1170x0//uploads/deviser/e893746/person.header.cropped.5a0b1c1e63982.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/bYcqXBhvJ7YKIqmtMl3EDFFDYl4=/702x450//uploads/deviser/e893746/person.header.cropped.small.5a0b1c231d994.png",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "main_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "store_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "loved_link": "http://localhost:8080/deviser/judas-arrieta/e893746/loved",
                "boxes_link": "http://localhost:8080/deviser/judas-arrieta/e893746/boxes",
                "stories_link": "http://localhost:8080/deviser/judas-arrieta/e893746/stories",
                "social_link": "http://localhost:8080/deviser/judas-arrieta/e893746/social",
                "about_link": "http://localhost:8080/deviser/judas-arrieta/e893746/about",
                "press_link": "http://localhost:8080/deviser/judas-arrieta/e893746/press",
                "videos_link": "http://localhost:8080/deviser/judas-arrieta/e893746/video",
                "faq_link": "http://localhost:8080/deviser/judas-arrieta/e893746/faq",
                "chat_link": "http://localhost:8080/messages/judas-arrieta/e893746",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "url": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "person_type": "deviser"
            },
            "action_type": "post_created",
            "action_name": "Posted something new",
            "title": null,
            "description": "<p>gatos en calle</p>",
            "photo": "http://localhost.thumbor.todevise.com:8000/AUybyySRRbRw3QNNDyUrF7WWYdM=/600x260//uploads/deviser/e893746/person.post.5b0c5a6a8debb.jpg",
            "link": "#",
            "loveds": 4,
            "date": {
                "sec": 1527536243,
                "usec": 805000
            }
        },
        {
            "id": "534a14",
            "person": {
                "id": "e893746",
                "slug": "judas-arrieta",
                "name": "Judas Arrieta",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/IlYZjzIT4nWX-I4n2o9IMKXxqQM=/1170x0//uploads/deviser/e893746/person.header.cropped.5a0b1c1e63982.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/bYcqXBhvJ7YKIqmtMl3EDFFDYl4=/702x450//uploads/deviser/e893746/person.header.cropped.small.5a0b1c231d994.png",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "main_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "store_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "loved_link": "http://localhost:8080/deviser/judas-arrieta/e893746/loved",
                "boxes_link": "http://localhost:8080/deviser/judas-arrieta/e893746/boxes",
                "stories_link": "http://localhost:8080/deviser/judas-arrieta/e893746/stories",
                "social_link": "http://localhost:8080/deviser/judas-arrieta/e893746/social",
                "about_link": "http://localhost:8080/deviser/judas-arrieta/e893746/about",
                "press_link": "http://localhost:8080/deviser/judas-arrieta/e893746/press",
                "videos_link": "http://localhost:8080/deviser/judas-arrieta/e893746/video",
                "faq_link": "http://localhost:8080/deviser/judas-arrieta/e893746/faq",
                "chat_link": "http://localhost:8080/messages/judas-arrieta/e893746",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "url": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "person_type": "deviser"
            },
            "action_type": "post_created",
            "action_name": "Posted something new",
            "title": null,
            "description": "<p>Gato!</p>",
            "photo": "http://localhost.thumbor.todevise.com:8000/tMJX3n5pyHicWo6D8-c6KrlnMqQ=/600x260//uploads/deviser/e893746/person.post.5b0c59e3dab4a.jpg",
            "link": "#",
            "loveds": 0,
            "date": {
                "sec": 1527536104,
                "usec": 312000
            }
        },
        {
            "id": "0068ef",
            "person": {
                "id": "e893746",
                "slug": "judas-arrieta",
                "name": "Judas Arrieta",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/IlYZjzIT4nWX-I4n2o9IMKXxqQM=/1170x0//uploads/deviser/e893746/person.header.cropped.5a0b1c1e63982.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/bYcqXBhvJ7YKIqmtMl3EDFFDYl4=/702x450//uploads/deviser/e893746/person.header.cropped.small.5a0b1c231d994.png",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "main_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "store_link": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "loved_link": "http://localhost:8080/deviser/judas-arrieta/e893746/loved",
                "boxes_link": "http://localhost:8080/deviser/judas-arrieta/e893746/boxes",
                "stories_link": "http://localhost:8080/deviser/judas-arrieta/e893746/stories",
                "social_link": "http://localhost:8080/deviser/judas-arrieta/e893746/social",
                "about_link": "http://localhost:8080/deviser/judas-arrieta/e893746/about",
                "press_link": "http://localhost:8080/deviser/judas-arrieta/e893746/press",
                "videos_link": "http://localhost:8080/deviser/judas-arrieta/e893746/video",
                "faq_link": "http://localhost:8080/deviser/judas-arrieta/e893746/faq",
                "chat_link": "http://localhost:8080/messages/judas-arrieta/e893746",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/e0TKyLAJOnHoZgG1ciz3bCBFZ_w=/155x155//uploads/deviser/e893746/person.profile.cropped.5a0b1be74f5a1.png",
                "url": "http://localhost:8080/deviser/judas-arrieta/e893746/store",
                "person_type": "deviser"
            },
            "action_type": "post_created",
            "action_name": "Posted something new",
            "title": null,
            "description": "<p>Adiós!</p>",
            "photo": "http://localhost.thumbor.todevise.com:8000/a5kqJTQqKmUxngehEStZxRJwPHY=/600x260//uploads/deviser/e893746/person.post.5b0be3eadeb0d.jpg",
            "link": "#",
            "loveds": 199,
            "date": {
                "sec": 1527505900,
                "usec": 916000
            }
        },
        {
            "id": "3d3651",
            "person": {
                "id": "8e6c7bl",
                "slug": "elo-matute",
                "name": "Elo Matute",
                "url_avatar": "/imgs/default-avatar.png",
                "header_image": "/imgs/default-cover.jpg",
                "header_small_image": "/imgs/default-cover.jpg",
                "profile_image": "/imgs/default-avatar.png",
                "main_link": "http://localhost:8080/client/elo-matute/8e6c7bl/loved",
                "store_link": null,
                "loved_link": "http://localhost:8080/client/elo-matute/8e6c7bl/loved",
                "boxes_link": "http://localhost:8080/client/elo-matute/8e6c7bl/boxes",
                "stories_link": "http://localhost:8080/person/stories?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "social_link": "http://localhost:8080/person/social?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "about_link": "http://localhost:8080/person/about?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "press_link": "http://localhost:8080/person/press?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "videos_link": "http://localhost:8080/person/videos?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "faq_link": "http://localhost:8080/person/faq?slug=elo-matute&person_id=8e6c7bl&person_type=client",
                "chat_link": "http://localhost:8080/messages/elo-matute/8e6c7bl",
                "is_followed": false,
                "photo": "/imgs/default-avatar.png",
                "url": "http://localhost:8080/client/elo-matute/8e6c7bl/loved",
                "person_type": "client"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Urban loock",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/LnLVE-maYITuP6sWoaLMUIsoLg0=/600x260//uploads/product/e0cd42f/product.photo.5a4261ba202be.png",
            "link": "/client/elo-matute/8e6c7bl/box/326f4e2",
            "loveds": 7,
            "date": {
                "sec": 1525112535,
                "usec": 692000
            }
        },
        {
            "id": "adbd41",
            "person": {
                "id": "77e7749",
                "slug": "josep-feliu-badosa",
                "name": "Josep Feliu badosa",
                "url_avatar": "/imgs/default-avatar.png",
                "header_image": "/imgs/default-cover.jpg",
                "header_small_image": "/imgs/default-cover.jpg",
                "profile_image": "/imgs/default-avatar.png",
                "main_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "store_link": null,
                "loved_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "boxes_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/boxes",
                "stories_link": "http://localhost:8080/person/stories?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "social_link": "http://localhost:8080/person/social?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "about_link": "http://localhost:8080/person/about?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "press_link": "http://localhost:8080/person/press?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "videos_link": "http://localhost:8080/person/videos?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "faq_link": "http://localhost:8080/person/faq?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "chat_link": "http://localhost:8080/messages/josep-feliu-badosa/77e7749",
                "is_followed": false,
                "photo": "/imgs/default-avatar.png",
                "url": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "person_type": "client"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Casc",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/LNhl_G0jawqQiHH-RAgSzMU4aI0=/600x260//uploads/product/821453v/product.photo.59c1215843369.jpg",
            "link": "/client/josep-feliu-badosa/77e7749/box/92a64dg",
            "loveds": 6,
            "date": {
                "sec": 1524602010,
                "usec": 507000
            }
        },
        {
            "id": "b74447",
            "person": {
                "id": "77e7749",
                "slug": "josep-feliu-badosa",
                "name": "Josep Feliu badosa",
                "url_avatar": "/imgs/default-avatar.png",
                "header_image": "/imgs/default-cover.jpg",
                "header_small_image": "/imgs/default-cover.jpg",
                "profile_image": "/imgs/default-avatar.png",
                "main_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "store_link": null,
                "loved_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "boxes_link": "http://localhost:8080/client/josep-feliu-badosa/77e7749/boxes",
                "stories_link": "http://localhost:8080/person/stories?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "social_link": "http://localhost:8080/person/social?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "about_link": "http://localhost:8080/person/about?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "press_link": "http://localhost:8080/person/press?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "videos_link": "http://localhost:8080/person/videos?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "faq_link": "http://localhost:8080/person/faq?slug=josep-feliu-badosa&person_id=77e7749&person_type=client",
                "chat_link": "http://localhost:8080/messages/josep-feliu-badosa/77e7749",
                "is_followed": false,
                "photo": "/imgs/default-avatar.png",
                "url": "http://localhost:8080/client/josep-feliu-badosa/77e7749/loved",
                "person_type": "client"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Anells",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/8AwmX8MPquCS9-dyn_u-7Grg2Po=/600x260//uploads/product/dbcd9ec/product.photo.59dbb8db2eafa.png",
            "link": "/client/josep-feliu-badosa/77e7749/box/ad834e0",
            "loveds": 5,
            "date": {
                "sec": 1524575275,
                "usec": 39000
            }
        },
        {
            "id": "b602aa",
            "person": {
                "id": "9f75b72",
                "slug": "ariadna-gasull",
                "name": "Ariadna Gasull",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/iL0EPug5tTh07dFywFcaFREMWRM=/1170x0//uploads/deviser/9f75b72/person.header.cropped.5addbc4529a92.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/2w1SySZzgU5jMJSetssFRrNqDTQ=/702x450//uploads/deviser/9f75b72/person.header.original.5addbc325b89b.jpg",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "main_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "store_link": null,
                "loved_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/loved",
                "boxes_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/boxes",
                "stories_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/stories",
                "social_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/social",
                "about_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "press_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/press",
                "videos_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/video",
                "faq_link": "http://localhost:8080/person/faq?slug=ariadna-gasull&person_id=9f75b72&person_type=influencer",
                "chat_link": "http://localhost:8080/messages/ariadna-gasull/9f75b72",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "url": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "person_type": "influencer"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Summer Mood",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/7RQ4hc_uQYVOdbSvxdbRhc4GCFE=/600x260//uploads/product/f3d5485/product.photo.599db27e4fd30.jpg",
            "link": "/influencer/ariadna-gasull/9f75b72/box/8d5567p",
            "loveds": 43,
            "date": {
                "sec": 1524571494,
                "usec": 505000
            }
        },
        {
            "id": "03b5ba",
            "person": {
                "id": "9f75b72",
                "slug": "ariadna-gasull",
                "name": "Ariadna Gasull",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/iL0EPug5tTh07dFywFcaFREMWRM=/1170x0//uploads/deviser/9f75b72/person.header.cropped.5addbc4529a92.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/2w1SySZzgU5jMJSetssFRrNqDTQ=/702x450//uploads/deviser/9f75b72/person.header.original.5addbc325b89b.jpg",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "main_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "store_link": null,
                "loved_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/loved",
                "boxes_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/boxes",
                "stories_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/stories",
                "social_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/social",
                "about_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "press_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/press",
                "videos_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/video",
                "faq_link": "http://localhost:8080/person/faq?slug=ariadna-gasull&person_id=9f75b72&person_type=influencer",
                "chat_link": "http://localhost:8080/messages/ariadna-gasull/9f75b72",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "url": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "person_type": "influencer"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Casual Outfits",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/0cWON3yfCio7JNx2DE8r4hM2AfA=/600x260//uploads/product/bb2e50m/product.photo.599f1cb8cb47f.jpg",
            "link": "/influencer/ariadna-gasull/9f75b72/box/17cf823",
            "loveds": 1,
            "date": {
                "sec": 1524566920,
                "usec": 771000
            }
        },
        {
            "id": "c4c563",
            "person": {
                "id": "9f75b72",
                "slug": "ariadna-gasull",
                "name": "Ariadna Gasull",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/iL0EPug5tTh07dFywFcaFREMWRM=/1170x0//uploads/deviser/9f75b72/person.header.cropped.5addbc4529a92.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/2w1SySZzgU5jMJSetssFRrNqDTQ=/702x450//uploads/deviser/9f75b72/person.header.original.5addbc325b89b.jpg",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "main_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "store_link": null,
                "loved_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/loved",
                "boxes_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/boxes",
                "stories_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/stories",
                "social_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/social",
                "about_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "press_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/press",
                "videos_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/video",
                "faq_link": "http://localhost:8080/person/faq?slug=ariadna-gasull&person_id=9f75b72&person_type=influencer",
                "chat_link": "http://localhost:8080/messages/ariadna-gasull/9f75b72",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "url": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "person_type": "influencer"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Night out outfits",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/PUJ9_DtjqJFeHKb5hY_dnHkqQ8Q=/600x260//uploads/product/aee0d34/product.photo.59844a9ee2d10.jpg",
            "link": "/influencer/ariadna-gasull/9f75b72/box/7016ba9",
            "loveds": 10,
            "date": {
                "sec": 1524566808,
                "usec": 985000
            }
        },
        {
            "id": "4d9255",
            "person": {
                "id": "9f75b72",
                "slug": "ariadna-gasull",
                "name": "Ariadna Gasull",
                "url_avatar": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "header_image": "http://localhost.thumbor.todevise.com:8000/iL0EPug5tTh07dFywFcaFREMWRM=/1170x0//uploads/deviser/9f75b72/person.header.cropped.5addbc4529a92.png",
                "header_small_image": "http://localhost.thumbor.todevise.com:8000/2w1SySZzgU5jMJSetssFRrNqDTQ=/702x450//uploads/deviser/9f75b72/person.header.original.5addbc325b89b.jpg",
                "profile_image": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "main_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "store_link": null,
                "loved_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/loved",
                "boxes_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/boxes",
                "stories_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/stories",
                "social_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/social",
                "about_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "press_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/press",
                "videos_link": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/video",
                "faq_link": "http://localhost:8080/person/faq?slug=ariadna-gasull&person_id=9f75b72&person_type=influencer",
                "chat_link": "http://localhost:8080/messages/ariadna-gasull/9f75b72",
                "is_followed": false,
                "photo": "http://localhost.thumbor.todevise.com:8000/QW7QfHtZsun9jxjVTjUs0nNMJG8=/155x155//uploads/deviser/9f75b72/person.profile.cropped.5addbc43557a2.png",
                "url": "http://localhost:8080/influencer/ariadna-gasull/9f75b72/about",
                "person_type": "influencer"
            },
            "action_type": "box_created",
            "action_name": "Created a new box",
            "title": "Bags",
            "description": null,
            "photo": "http://localhost.thumbor.todevise.com:8000/qpzBhspxro5Idl6Pe3X34W4u0OA=/600x260//uploads/product/ac682dn/product.photo.59bf801953dd2.jpg",
            "link": "/influencer/ariadna-gasull/9f75b72/box/796a388",
            "loveds": 9,
            "date": {
                "sec": 1524566077,
                "usec": 297000
            }
        }
    ],
    "meta": {
        "total_count": 10,
        "current_page": 1,
        "per_page": 99999
    }
}
```