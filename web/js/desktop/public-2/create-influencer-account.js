(function () {
    "use strict";

    function controller(UtilService, personDataService, invitationDataService, toastr, $location, $window) {
        var vm = this;
        vm.submitForm = submitForm;
        vm.has_error = UtilService.has_error;

        function init() {
            vm.influencer = Object.assign({}, invitation);
        }

        init();

        function submitForm(form) {
            function onCreateInfluencer(data) {
                $window.location.href = '/influencer/' + dataSaved.slug + '/' + dataSaved.id + '/about/edit';
            }
            if (form.password_confirm.$error.same)
                form.$setValidity('password_confirm', false);
            else {
                form.$setValidity('password_confirm', true);
            }
            if (form.$valid) {

                form.$setSubmitted();
                personDataService.createInfluencer(vm.influencer, null, onCreateInfluencer, UtilService.onError);
            }
        }

    }

    angular
        .module('todevise', ['api', 'util', 'header', 'toastr', 'ui.bootstrap'])
        .controller('createInfluencerCtrl', controller);

}());