/*
 * This manage show/hide text on terms section
 */

(function () {
    "use strict";

    //TODO: $cacheFactory needed?
    function termsCtrl($cacheFactory) {
        var vm = this;

        function init() {
            vm.groupOfTerms = _groupOfTerms;
            vm.activeTermId = vm.groupOfTerms[0].short_id; //activate first group as default
        }

        init();

        vm.showTerms = function (activeTermId) {
            vm.activeTermId = activeTermId;
        }

    }

    angular.module('todevise', [])
        .controller('termsCtrl', termsCtrl)
}());