(function () {
	"use strict";

	function controller(productDataService, languageDataService) {
		var vm = this;
		vm.title_language = 'en-US';
		vm.categories_helper = [];
		vm.product = {
			categories: []
		};
		vm.addCategory = addCategory;
		vm.categorySelected = categorySelected;

		function init(){
			getLanguages();
			getCategories();
		}

		init();

		function getCategories() {
			productDataService.Categories.get({scope: 'all'})
				.$promise.then(function (dataCategories) {
					vm.allCategories = dataCategories.items;
					vm.rootCategories = filterCategory(vm.allCategories, '');
					addCategory();
				}, function (err) {
					toastr.error(err);
				});
				productDataService.Categories.get()
				.$promise.then(function (dataCategories) {
					console.log(dataCategories.items);
				}, function (err) {
					toastr.error(err);
				});
		}

		function getLanguages() {
			languageDataService.Languages.get()
				.$promise.then(function (dataLanguages) {
					vm.languages = dataLanguages.items;
				}, function (err) {
					toastr.error(err);
				});
		}

		function addCategory() {
			vm.categories_helper.push({
				categories_selected: [null],
				categories: [vm.rootCategories]
			})
		}

		function categorySelected(category, index_helper, index) {
			vm.categories_helper[index_helper].categories_selected[index] = category;
			console.log(filterCategory(vm.allCategories, category));
			if(filterCategory(vm.allCategories,category).length > 0) {
				vm.categories_helper[index_helper].categories[index+1] = filterCategory(vm.allCategories, category);
				vm.categories_helper[index_helper].categories_selected[index+1] = null;
			} else {
				vm.product.categories[index_helper] = category;
			}
		}

		function filterCategory(categories, id) {
			var sub_categories = [];
			for(var i = 0; i < categories.length; i++) {
				var split_fathers = categories[i].path.split('/');
				for(var j = 0; j < split_fathers.length; j++) {
					if(split_fathers[j] === "")
						split_fathers.splice(j, 1);
				}
				var father = split_fathers[split_fathers.length-1];
				if(father === id)
					sub_categories.push(categories[i]);
			}
			return sub_categories;
		}
	}

	var component = {
		templateUrl: currentHost() + '/js/desktop/product/basic-info/basic-info.html',
		controller: controller,
		controllerAs: 'productBasicInfoCtrl'
	}

	angular
		.module('todevise')
		.component('productBasicInfo', component);

}());

/**
{
    "_id" : ObjectId("577f74b042ee97ee258b456c"),
    "short_id" : "a04c31dc",
    "categories" : [ 
        "77c3t"
    ],
    "collections" : [],
    "name" : {
        "en-US" : "CARRE SCARF 2"
    },
    "slug" : {
        "en-US" : "carre-scarf-2"
    },
    "description" : {
        "en-US" : "The piece\n\n30°C washable, dry flat, inside/out\n\nThe collection\n\n‘MK Ultra’ is one of the most secret neuroleptic experimentation projects, it recalls the razing and building of an ideal, an upgraded human creature, at the boundaries of the robotics and genetic manipulation. The collection evokes the genesis of a different woman, coming out of a laboratory and adorned for the Fall-Winter 14/15 with PVC< aluminum, silver, metallic velvet or 3D printed elements. The patterns present machines and factories imaging, considered as allegories for a sort of a contemporary and experimental ‘Rappacini’s Daughter’ – a dark, rebel and technological vertigo."
    },
    "media" : {
        "videos_links" : [],
        "photos" : [ 
            {
                "name" : "2016-07-08-09-40-29-d59fo.jpg",
                "tags" : [],
                "main_product_photo" : true
            }, 
            {
                "name" : "2016-07-08-09-40-29-50d4l.jpg",
                "tags" : []
            }
        ]
    },
    "options" : {
        "731ct" : [ 
            [ 
                "purple"
            ]
        ],
        "d0e2g" : [ 
            [ 
                "silk"
            ]
        ],
        "22eb6" : [ 
            [ 
                "formal"
            ]
        ],
        "b0bd5" : [ 
            [ 
                "all-year"
            ]
        ]
    },
    "madetoorder" : {
        "value" : NumberLong(0)
    },
    "sizechart" : {
        "values" : []
    },
    "bespoke" : [],
    "preorder" : {
        "type" : NumberLong(0)
    },
    "returns" : {
        "type" : NumberLong(0)
    },
    "warranty" : {
        "type" : NumberLong(0)
    },
    "currency" : "EUR",
    "weight_unit" : "g",
    "price_stock" : [ 
        {
            "options" : {
                "731ct" : [ 
                    "purple"
                ],
                "d0e2g" : [ 
                    "silk"
                ]
            },
            "weight" : NumberLong(0),
            "stock" : NumberLong(1),
            "price" : NumberLong(280)
        }
    ],
    "deviser_id" : "deb5902"
}
*/