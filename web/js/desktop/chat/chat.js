(function() {
    "use strict";

    function config($provide, $translatePartialLoaderProvider, $locationProvider) {
        $translatePartialLoaderProvider.addPart('chat');
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    }

    angular
        .module('chat', ['api', 'util', 'header', 'ui.bootstrap', 'pascalprecht.translate'])
        .config(config);

}());