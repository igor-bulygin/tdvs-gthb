(function() {
    "use strict";

    function controller(personDataService, deviserEvents, languageDataService, UtilService, Upload,
        uploadDataService, $uibModal, locationDataService, $scope, $location, $rootScope, $window) {
        var vm = this;
        vm.showCities = false;
        vm.limit_text_biography = 140;
        vm.selected_language = _lang;
        vm.description_language = vm.selected_language;
        vm.required = {};
        vm.openCropModal = openCropModal;
        vm.selectCity = selectCity;
        vm.searchPlace = searchPlace;
        vm.editHeader = editHeader;
        vm.saveHeader = saveHeader;
        vm.cancelEdit = cancelEdit;
        vm.isDeviser = UtilService.isDeviser;
        vm.isInfluencer = UtilService.isInfluencer;
        vm.isClient = UtilService.isClient;
        vm.has_error = UtilService.has_error;
        vm.follow = follow;
        vm.unFollow = unFollow;
        vm.init = init;

        function init(isFollowed) {
            vm.editingHeader = false;
            vm.isFollowed = isFollowed;
            getPerson();
            getLanguages();
            console.log(vm.isFollowed);
        }

        function unFollow(personId) {
            var connectedUser = UtilService.getConnectedUser();
            if (!connectedUser) {
                $window.location.href = '/timeline';
            } else {
                function onSetFollowSuccess(data) {
                    vm.isFollowed = false;
                }

                function onSetFollowError(err) {
                    UtilService.onError(err);
                }

                var params = {
                    personId: personId
                }
                personDataService.unFollowPerson(params, params, onSetFollowSuccess, onSetFollowError);
            }
        }

        function follow(personId) {
            var connectedUser = UtilService.getConnectedUser();
            if (!connectedUser) {
                $window.location.href = '/timeline';
            } else {
                function onSetFollowSuccess(data) {
                    vm.isFollowed = true;
                }

                function onSetFollowError(err) {
                    UtilService.onError(err);
                }

                var params = {
                    personId: personId
                }
                personDataService.followPerson(params, params, onSetFollowSuccess, onSetFollowError);
            }
        }


        function getPerson() {
            function onGetProfileSuccess(data) {
                vm.person = angular.copy(data);
                parsePersonInfo(vm.person);
                if (!vm.editingHeader)
                    vm.person_original = angular.copy(data);
            }

            if (vm.editingHeader) {
                personDataService.getProfile({
                    personId: person.short_id,
                }, onGetProfileSuccess, UtilService.onError)
            } else {
                personDataService.getProfilePublic({
                    personId: person.short_id,
                }, onGetProfileSuccess, UtilService.onError);
            }

        }

        function getLanguages() {
            function onGetLanguagesSuccess(data) {
                vm.languages = data.items;
            }

            languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
        }

        function parsePersonInfo(person) {
            function setHostImage(image) {
                return currentHost() + person.url_images + image;
            }
            //set status
            vm.isProfilePublic = (person.account_state === 'draft' ? false : true);
            person.text_short_description = UtilService.emptyArrayToObject(person.text_short_description);
            //set city
            if (person.city && person.country)
                vm.city = person.city + ', ' + person.country;
            //set images
            if (person.media.header_cropped)
                vm.header = setHostImage(person.media.header_cropped);
            if (person.media.profile_cropped)
                vm.profile = setHostImage(person.media.profile_cropped);
            if (person.media.header)
                vm.header_original = setHostImage(person.media.header);
            if (person.media.profile)
                vm.profile_original = setHostImage(person.media.profile);
        }

        function searchPlace(place) {
            function onGetLocationSuccess(data) {
                if (data.items.length === 0) {
                    vm.showCities = false;
                }
                if (data.items.length > 0) {
                    vm.showCities = true;
                    vm.cities = angular.copy(data.items);
                }
            }

            if (place)
                locationDataService.getLocation({ q: place }, onGetLocationSuccess, UtilService.onError);
            else {
                selectCity({
                    city: null,
                    country_code: null
                })
            }

        }

        function selectCity(city) {
            vm.person.personal_info.city = city.city;
            vm.person.personal_info.country = city.country_code;
            if (city.city && city.country_code)
                vm.city = vm.person.personal_info.city + ', ' + vm.person.personal_info.country;
            vm.showCities = false;
        }

        function editHeader() {
            vm.editingHeader = true;
            getPerson();
        }

        function saveHeader() {
            function onSaveHeaderSuccess(data) {
                vm.editingHeader = false;

                personDataService.getProfilePublic({
                    personId: person.short_id
                }, function(data) {
                    vm.person = angular.copy(data);
                    vm.person_original = angular.copy(data);
                    parsePersonInfo(vm.person);
                    var newObject = {
                        media: {
                            header: vm.person.media.header || null,
                            header_cropped: vm.person.media.header_cropped || null,
                            header_cropped_small: vm.person.media.header_cropped_small || null,
                            profile: vm.person.media.profile || null,
                            profile_cropped: vm.person.media.profile_cropped || null
                        },
                        personal_info: vm.person.personal_info || {},
                        text_short_description: vm.person.text_short_description || {}
                    }
                    $rootScope.$broadcast(deviserEvents.updated_deviser, newObject);
                }, UtilService.onError);
            }

            personDataService.updateProfile(vm.person, {
                personId: person.short_id
            }, onSaveHeaderSuccess, UtilService.onError);
        }

        function cancelEdit() {
            vm.editingHeader = false;
            parsePersonInfo(vm.person_original);
            vm.person = angular.copy(vm.person_original);
        }

        //modals
        function openCropModal(photo, type) {
            var modalInstance = $uibModal.open({
                component: 'modalCrop',
                resolve: {
                    photo: function() {
                        return photo;
                    },
                    type: function() {
                        return type;
                    },
                    person: function() {
                        return vm.person;
                    }
                }
            })

            modalInstance.result.then(function(data) {
                vm.person.media[type] = data.data.filename;
                parsePersonInfo(vm.person);
                if (type === 'header_cropped') {
                    openCropModal(photo, 'header_cropped_small');
                }
            }, function() {
                console.log("dismissed");
            });
        }

        function setErrors(error_array) {
            error_array.forEach(function(element) {
                vm.required[element] = true;
            })
        }

        //watches
        $scope.$watch('personHeaderCtrl.new_header', function(newValue, oldValue) {
            function onUploadHeaderSuccess(data) {
                vm.person.media['header'] = angular.copy(data.data.filename);
                //then, open modal
                openCropModal(newValue, 'header_cropped');
            }
            if (newValue) {
                //upload original
                var data = {
                    person_id: vm.person.id,
                    type: 'deviser-media-header-original',
                    file: newValue
                }
                uploadDataService.UploadFile(data, onUploadHeaderSuccess, UtilService.onError, console.log);
            }
        });

        $scope.$watch('personHeaderCtrl.new_profile', function(newValue, oldValue) {
            function onUploadProfileSuccess(data) {
                vm.person.media['profile'] = angular.copy(data.data.filename);
                //then, open modal
                openCropModal(newValue, 'profile_cropped');
            }
            if (newValue) {
                //upload original
                var data = {
                    person_id: vm.person.id,
                    type: 'deviser-media-profile-original',
                    file: newValue
                }
                uploadDataService.UploadFile(data, onUploadProfileSuccess, UtilService.onError, console.log);
            }
        });

        $scope.$watch('personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language]', function(newValue, oldValue) {
            if (newValue && newValue.length > vm.limit_text_biography)
                vm.person.text_short_description[vm.description_language] = oldValue;
        });

        $scope.$on(deviserEvents.updated_deviser, function(event, args) {
            vm.person_original = Object.assign(vm.person, args)
            vm.person = Object.assign(vm.person, args);
            UtilService.setLeavingModal(false);
        })

        $scope.$on(deviserEvents.make_profile_public_errors, function(event, args) {
            //set form as submitted
            if (vm.form)
                vm.form.$setSubmitted();
            //check for required fields and sections
            if (args.required_fields && args.required_fields.length > 0) {
                setErrors(args.required_fields);
            }
            if (args.required_sections && args.required_sections.length > 0) {
                setErrors(args.required_sections);
            }
        })

    }

    angular
        .module('person')
        .controller('personHeaderCtrl', controller);
}());