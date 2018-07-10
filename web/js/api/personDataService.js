(function() {
    "use strict";

    function personDataService($resource, apiConfig, apiMethods) {
        //pub
        var Person = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'person/:personId');
        var Login = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/login');
        var Logout = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/logout');
        var PassResetRequest = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/forgot-password');
        var PassReset = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'auth/reset-password');
        var PublicPost = $resource(apiConfig.baseUrl + 'pub/' + apiConfig.version + 'post');

        //priv
        var Profile = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId', {}, {
            'update': {
                method: 'PATCH'
            }
        });
        var Password = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'person/:personId/update-password', {}, {
            'update': {
                method: 'PUT'
            }
        });

        var Followed = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'follow/:personId', {}, {
            'update': {
                method: 'POST'
            }
        });
        var UnFollowed = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'follow/:personId', {}, {
            'update': {
                method: 'DELETE'
            }
        });

        var Post = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'post/:id', {}, {
            'update': {
                method: 'PATCH'
            },
            'delete': {
                method: 'DELETE'
            }
        });

        var Timeline = $resource(apiConfig.baseUrl + 'priv/' + apiConfig.version + 'timeline');

        //methods
        this.getPeople = getPeople;
        this.createClient = createClient;
        this.createDeviser = createDeviser;
        this.createInfluencer = createInfluencer;
        this.login = login;
        this.logout = logout;
        this.getProfile = getProfile;
        this.getProfilePublic = getProfilePublic;
        this.updateProfile = updateProfile;
        this.updatePassword = updatePassword;
        this.askForResetPassword = askForResetPassword;
        this.resetPassword = resetPassword;
        this.followPerson = followPerson;
        this.unFollowPerson = unFollowPerson;
        this.getPost = getPost;
        this.publishPost = publishPost;
        this.updatePost = updatePost;
        this.getOwnerPost = getOwnerPost;
        this.deletePost = deletePost;
        this.getTimeline = getTimeline;

        function getPeople(params, onSuccess, onError) {
            apiMethods.get(Person, params, onSuccess, onError);
        }

        function createClient(data, params, onSuccess, onError) {
            data = Object.assign(data, { type: [1] });
            apiMethods.create(Person, data, params, onSuccess, onError);
        }

        function createDeviser(data, params, onSuccess, onError) {
            data = Object.assign(data, { type: [2] });
            apiMethods.create(Person, data, params, onSuccess, onError);
        }

        function createInfluencer(data, params, onSuccess, onError) {
            data = Object.assign(data, { type: [3] });
            apiMethods.create(Person, data, params, onSuccess, onError);
        }

        function login(data, params, onSuccess, onError) {
            apiMethods.create(Login, data, params, onSuccess, onError);
        }

        function logout(data, params, onSuccess, onError) {
            apiMethods.create(Logout, data, params, onSuccess, onError);
        }

        function getProfilePublic(params, onSuccess, onError) {
            apiMethods.get(Person, params, onSuccess, onError);
        }

        function getProfile(params, onSuccess, onError) {
            apiMethods.get(Profile, params, onSuccess, onError);
        }

        function updateProfile(data, params, onSuccess, onError) {
            apiMethods.update(Profile, data, params, onSuccess, onError);
        }

        function updatePassword(data, params, onSuccess, onError) {
            apiMethods.update(Password, data, params, onSuccess, onError);
        }

        function askForResetPassword(data, onSuccess, onError) {
            apiMethods.create(PassResetRequest, data, null, onSuccess, onError);
        }

        function resetPassword(data, onSuccess, onError) {
            apiMethods.create(PassReset, data, {}, onSuccess, onError);
        }

        function followPerson(data, params, onSuccess, onError) {
            apiMethods.update(Followed, data, params, onSuccess, onError);
        }

        function unFollowPerson(data, params, onSuccess, onError) {
            apiMethods.update(UnFollowed, data, params, onSuccess, onError);
        }

        function getOwnerPost(params, onSuccess, onError) {
            apiMethods.get(Post, params, onSuccess, onError);
        }

        function publishPost(data, onSuccess, onError) {
            apiMethods.create(Post, data, null, onSuccess, onError);
        }

        function updatePost(data, params, onSuccess, onError) {
            apiMethods.update(Post, data, params, onSuccess, onError);
        }

        function getPost(params, onSuccess, onError) {
            apiMethods.get(PublicPost, params, onSuccess, onError);
        }

        function deletePost(data, params, onSuccess, onError) {
            apiMethods.deleteItem(Post, data, params, onSuccess, onError);
        }

        function getTimeline(params, onSuccess, onError) {
            apiMethods.get(Timeline, params, onSuccess, onError);
        }
    }

    angular
        .module('api')
        .service('personDataService', personDataService);

}());