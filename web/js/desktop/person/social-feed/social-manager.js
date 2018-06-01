(function() {
    "use strict";

    function controller(personDataService, UtilService, languageDataService, uploadDataService, $timeout, lovedDataService, $uibModal, $translate, $scope) {
        var vm = this;
        vm.posts = [];
        vm.tempFiles = [];
        vm.showCreatePost = false;
        vm.selected_language = _lang;
        vm.stripHTMLTags = UtilService.stripHTMLTags;
        vm.truncateString = UtilService.truncateString;
        vm.viewingConnectedUser = viewingConnectedUser;
        vm.uploadPhoto = uploadPhoto;
        vm.showNewPost = showNewPost;
        vm.createPost = createPost;
        vm.lovePost = lovePost;
        vm.unLovePost = unLovePost;
        vm.maxCharacters = 18;
        vm.openPostDetailsModal = openPostDetailsModal;
        vm.editPost = editPost;
        vm.deletePost = deletePost;
        vm.mandatory_langs = Object.keys(_langs_required);
        vm.mandatory_langs_names = "";

        init();

        function init() {
            setMandatoryLanguagesNames();
            getLanguages();
            getPosts();
        }

        function viewingConnectedUser() {
            return UtilService.isConnectedUser(person.short_id);
        }

        function getPosts() {
            vm.loading = true;

            function onGetPostsSuccess(data) {
                vm.posts = data.items;
                vm.loading = false;
            }

            function onGetPostsError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            personDataService.getPost({ person_id: person.short_id }, onGetPostsSuccess, onGetPostsError);

        }

        function showNewPost() {
            vm.newPost = { photo: "", text: "", person_id: person.short_id, post_state: 'post_state_active' };
            vm.newPostModal = $uibModal.open({
                templateUrl: 'newPostModal',
                scope: $scope,
                size: 'sm'
            });
        }


        function getLanguages() {
            function onGetLanguagesSuccess(data) {
                vm.languages = data.items;
            }
            languageDataService.getLanguages(onGetLanguagesSuccess, UtilService.onError);
        }

        function parseText(post) {
            post.completedLanguages = [];
            vm.languages.forEach(function(element) {
                if (post.text[element.code] && post.text[element.code] !== "") {
                    post.completedLanguages.push(element.code)
                }
            })
        }

        function requiredText() {
            var completedLanguages = 0;
            vm.languages.forEach(function(element) {
                if (vm.newPost.text[element.code] && vm.newPost.text[element.code] !== "") {
                    completedLanguages = completedLanguages + 1;
                }
            });
            return completedLanguages === vm.languages.length - 1;
        }

        function setMandatoryLanguagesNames() {
            angular.forEach(Object.keys(_langs_required), function(lang) {
                var translationLang = "product.".concat(_langs_required[lang].toUpperCase());
                $translate(translationLang).then(function(tr) {
                    if (vm.mandatory_langs_names.length > 0) {
                        vm.mandatory_langs_names = vm.mandatory_langs_names.concat(', ');
                    }
                    vm.mandatory_langs_names = vm.mandatory_langs_names.concat(tr);
                });
            });
        }

        function dismiss() {
            vm.newPostModal.close();
        }

        function createPost() {
            var hasError = false;
            vm.newPost.required_text = false;
            angular.forEach(vm.mandatory_langs, function(lang) {
                if (angular.isUndefined(vm.newPost.text[lang]) || vm.newPost.text[lang].length < 1) {
                    vm.newPost.required_text = true;
                    hasError = true;
                }
            });
            if (hasError) {
                return;
            }
            vm.loading = true;

            function onCreatePostsSuccess(data) {
                getPosts();
                dismiss();
                vm.loading = false;
            }

            function onCreatePostsError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            var params = {};
            if (vm.isEdition) {
                vm.isEdition = false;
                personDataService.updatePost(vm.newPost, { id: vm.newPost.id }, onCreatePostsSuccess, onCreatePostsError);
            } else {
                personDataService.publishPost(vm.newPost, onCreatePostsSuccess, onCreatePostsError);
            }
        }


        function uploadPhoto(images, errImages) {
            function onUploadPhotoSuccess(data, file) {
                $timeout(function() {
                    delete file.progress;
                }, 1000);
                if (!vm.newPost.photo) {
                    vm.newPost.photo = {};
                }
                vm.newPost.photo = data.data.filename;
                vm.newImage = currentHost() + data.data.url;
                var index = -1;
                angular.forEach(vm.tempFiles, function(uploadingFile) {
                    if (uploadingFile.$$hashKey == file.$$hashKey) {
                        index = -vm.tempFiles.indexOf(uploadingFile);
                    }
                });
                if (index != -1) {
                    vm.tempFiles.splice(index, 1);
                }
            }

            function onWhileUploadingPhoto(evt, file) {
                angular.forEach(vm.tempFiles, function(uploadingFile) {
                    if (uploadingFile.$$hashKey == file.$$hashKey) {
                        uploadingFile.progress = parseInt(100.0 * evt.loaded / evt.total);
                    }
                });
            }
            angular.forEach(images, function(image) {
                vm.tempFiles.push(image);
            });
            vm.files = images
            vm.errFiles = errImages;
            //upload photos
            angular.forEach(vm.files, function(file) {
                var data = {
                    type: 'post-photos',
                    file: file
                };
                uploadDataService.UploadFile(data,
                    function(data) {
                        return onUploadPhotoSuccess(data, file);
                    }, UtilService.onError,
                    function(evt) {
                        return onWhileUploadingPhoto(evt, file);
                    });
            });
        }

        function lovePost(post) {
            if (post.person_id === UtilService.getConnectedUser() || post.isLoved) {
                return;
            }
            vm.loading = true;

            function onLovePostSuccess(data) {
                post.loveds = data.post.loveds;
                post.isLoved = data.post.isLoved;
                vm.loading = false;
            }

            function onLovePostError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.setLoved({ post_id: post.id }, onLovePostSuccess, onLovePostError);
        }

        function unLovePost(post) {
            if (post.person_id === UtilService.getConnectedUser() || !post.isLoved) {
                return;
            }
            vm.loading = true;

            function onUnLovePostSuccess(data) {
                post.loveds = post.loveds - 1;
                post.isLoved = false;
                vm.loading = false;
            }

            function onUnLovePostError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            lovedDataService.deleteLovedPost({ postId: post.id }, onUnLovePostSuccess, onUnLovePostError);
        }

        function openPostDetailsModal(post) {
            var modalInstance = $uibModal.open({
                component: 'modalPostDetails',
                resolve: {
                    post: function() {
                        return post;
                    }
                }
            });
            modalInstance.result.then(function(data) {
                return data;
            }, function(err) {
                UtilService.onError(err);
            });
        }

        function editPost(postId) {
            vm.isEdition = true;

            function onGetPostsSuccess(data) {
                vm.newPost = data;
                vm.newPostModal = $uibModal.open({
                    templateUrl: 'newPostModal',
                    scope: $scope,
                    size: 'sm'
                });
            }

            function onGetPostsError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            personDataService.getOwnerPost({ id: postId }, onGetPostsSuccess, onGetPostsError);
        }

        function deletePost(postId) {
            function onDeletePostsSuccess(data) {
                getPosts();
            }

            function onDeletePostsError(err) {
                vm.loading = false;
                UtilService.onError(err);
            }
            personDataService.deletePost({ id: postId }, onDeletePostsSuccess, onDeletePostsError);
        }


    }

    angular
        .module('person')
        .controller('socialManagerCtrl', controller);

}());