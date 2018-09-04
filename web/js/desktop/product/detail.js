(function() {
    "use strict";

    function controller(productDataService, tagDataService, cartDataService, lovedDataService, boxDataService,
        $location, toastr, UtilService, $window, $uibModal, localStorageUtilService, $timeout, $rootScope, cartEvents) {
        var vm = this;
        vm.quantity = 1;
        vm.parseDate = UtilService.parseDate;
        vm.option_selected = {};
        vm.has_error = UtilService.has_error;
        vm.isString = angular.isString;
        vm.optionsChanged = optionsChanged;
        vm.changeQuantity = changeQuantity;
        vm.changeOriginalArtwork = changeOriginalArtwork;
        vm.selectComparator = selectComparator;
        vm.addToCart = addToCart;
        vm.setLoved = setLoved;
        vm.setBox = setBox;
        vm.selected_language = _lang;
        vm.sendComment = sendComment;
        var select_order = ['size', 'color', 'select'];
        vm.stars_counter = { val1: 0, val2: 0, val3: 0, val4: 0, val5: 0 };
        vm.newComment = { text: '', stars: 0 };
        vm.showReplyComment = showReplyComment;
        vm.sendCommentReply = sendCommentReply;


        function init() {
            getTags();
        }

        init();

        function getProduct() {
            function onGetProductSuccess(data) {
                vm.product = angular.copy(data);
                vm.view_sizechart = true;
                vm.require_options = true;
                //checks
                var original_artwork = getOriginalArtwork(vm.product);
                setPrints(vm.product);
                vm.total_stock = getTotalStock(vm.product.price_stock);
                vm.minimum_price = getMinimumPrice(vm.product.price_stock);
                if (!UtilService.isObject(original_artwork)) {
                    vm.stock = vm.total_stock;
                    vm.price = vm.minimum_price;
                    vm.product.options.forEach(function(option) {
                        //parse options with only one value
                        if (option.values.length === 1) {
                            vm.option_selected[option.id] = option.values[0].value;
                            //parseOptions(option.id, option.values[0].value);
                        }
                    });
                    vm.reference_id = getReferenceId(vm.option_selected);
                } else {
                    vm.stock = original_artwork.stock;
                    vm.price = original_artwork.price;
                    vm.reference_id = original_artwork.short_id;
                    vm.require_options = false;
                }
                setProductValoration();
            }

            productDataService.getProductPub({
                idProduct: product.id
            }, onGetProductSuccess, UtilService.onError);
        }

        function setProductValoration() {
            vm.productStars = 0;
            var valorations_counter = 0;
            vm.stars_counter = { val1: 0, val2: 0, val3: 0, val4: 0, val5: 0 };
            angular.forEach(vm.product.comments, function(comment) {
                if (comment.stars > 0) {
                    vm.productStars = vm.productStars + comment.stars;
                    valorations_counter++;
                    switch (comment.stars) {
                        case 1:
                            vm.stars_counter.val1++;
                            break;
                        case 2:
                            vm.stars_counter.val2++;
                            break;
                        case 3:
                            vm.stars_counter.val3++;
                            break;
                        case 4:
                            vm.stars_counter.val4++;
                            break;
                        case 5:
                            vm.stars_counter.val5++;
                            break;
                    }
                }
            });
            vm.productStars = vm.productStars / valorations_counter;
        }

        function getTags() {
            function onGetTagsSuccess(data) {
                vm.tags = angular.copy(data.items);
                getProduct();
            }
            tagDataService.getTags(null, onGetTagsSuccess, UtilService.onError);
        }

        function getMinimumPrice(references) {
            if (references.length > 0) {
                var price = references[0].price;
                for (var i = 0; i < references.length; i++) {
                    if (references[i].price < price && references[i].price !== 0) {
                        price = references[i].price
                    }
                }
                return price;
            } else {
                return null;
            }
        }

        function getTotalStock(references) {
            var stock = null;
            for (var i = 0; i < references.length; i++) {
                if (references[i].available && !references[i].original_artwork)
                    stock += references[i].stock;
            }
            return stock;
        }

        function optionsChanged(option_id, value) {
            resetOptions();
            parseOptions(option_id, value);
        }

        function parseOptions(option_id, value) {
            if (option_id === 'size')
                value = getSizeText(value);
            vm.reference_id = getReferenceId(vm.option_selected);
            vm.product.price_stock.forEach(function(element) {
                if (element.stock === 0 && ((angular.isArray(element.options[option_id]) && element.options[option_id].indexOf(value) > -1) ||
                        (angular.isArray(element.options[option_id]) && angular.isArray(value) && angular.equals(element.options[option_id], value)) ||
                        (option_id === 'size' && angular.equals(element.options[option_id], value)))) {
                    for (var key in element.options) {
                        if (key !== option_id) {
                            vm.product.options.forEach(function(option) {
                                if (key === option.id) {
                                    option.values.forEach(function(unit) {
                                        if (key == 'size') {
                                            if (unit.text == element.options[key]) {
                                                //unit.disabled=true;
                                            }
                                        } else {
                                            if (unit.value == element.options[key][0]) {
                                                //unit.disabled=true;
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            });
        }

        function getSizeText(value) {
            var text = null;
            vm.product.options.forEach(function(option) {
                if (option.id == "size") {
                    option.values.forEach(function(element) {
                        if (value == element.value) {
                            text = element.text;
                        }
                    })
                }
            })
            return text;
        }

        function resetOptions() {
            vm.product.options.forEach(function(element) {
                element.values.forEach(function(value) {
                    value['disabled'] = false;
                })
            })
        }

        function isOptionRequired(key) {
            var tag = vm.product.options.find(function(element) {
                return angular.equals(element.id, key);
            });
            if (tag) {
                return (tag.change_reference);
            }
            return null;
        }

        function getReferenceId(options_selected) {
            vm.stock = null;
            vm.quantity = 1;
            var prices = [];
            var options = angular.copy(options_selected);
            var reference;
            if (options['size'] && !UtilService.isObject(options['size'])) {
                options['size'] = getSizeText(options['size']);
            }
            for (var key in options) {
                var optionRequired = isOptionRequired(key);
                if (key !== 'size' && optionRequired !== undefined && !optionRequired) {
                    delete options[key];
                }
            }
            vm.product.price_stock.forEach(function(element) {
                var isReference = true;
                for (var key in options) {
                    var valueToCompare;
                    if (key === 'size' || !angular.isString(options[key]))
                        valueToCompare = options[key];
                    else {
                        valueToCompare = [options[key]];
                    }
                    if (!angular.equals(valueToCompare, element.options[key]))
                        isReference = false;
                }
                if (isReference && element.available && !element.original_artwork) {
                    reference = element.short_id;
                    if (element.stock !== null)
                        vm.stock += element.stock;
                    else {
                        vm.stock = angular.copy(vm.stock, element.stock)
                    }
                    prices.push(element.price);
                }
            });
            if (angular.isArray(prices) && prices.length > 0)
                vm.price = Math.min(...prices);
            else {
                vm.price = '-';
            }
            return reference;
        }

        function changeQuantity(value) {
            if ((vm.quantity <= vm.stock) || (vm.stock === null)) {
                if (value < 0) {
                    if (vm.quantity > 1)
                        vm.quantity += value;
                } else if ((vm.quantity < vm.stock) || (vm.stock === null)) {
                    vm.quantity += value;
                }
            }
        }

        function setPrints(product) {
            if (UtilService.isObject(product.prints)) {
                vm.has_prints = true;
                vm.view_sizechart = false;
                if (angular.isArray(product.prints.sizes) && product.prints.sizes.length > 0) {
                    var object = {
                        name: 'product.detail.PRINT_SIZE',
                        id: 'size',
                        change_reference: true,
                        required: true
                    };
                    object['values'] = product.prints.sizes.map(function(element) {
                        var object = {};
                        if (element.width && element.length && element.metric_unit) {
                            object.text = element.width + 'x' + element.length + element.metric_unit;
                            object.value = {
                                width: element.width,
                                length: element.length,
                                metric_unit: element.metric_unit
                            }
                        }
                        return object;
                    });
                    vm.product.options.push(object);
                }
            } else {
                vm.has_prints = false;
            }
        }

        function getOriginalArtwork(product) {
            var original_artwork = product.price_stock.find(function(element) {
                return element.original_artwork && element.available;
            })
            if (original_artwork) {
                vm.original_artwork = true;
            }
            return original_artwork;
        }

        function changeOriginalArtwork(value) {
            if (!vm.addingToCart) {
                var original_artwork = getOriginalArtwork(vm.product);
                if (value == true && UtilService.isObject(original_artwork)) {
                    vm.stock = original_artwork.stock;
                    vm.price = original_artwork.price;
                    vm.reference_id = original_artwork.short_id;
                    vm.require_options = false;
                } else {
                    vm.stock = vm.total_stock;
                    vm.price = vm.minimum_price;
                    vm.require_options = true;
                    vm.reference_id = getReferenceId(vm.option_selected);
                }
            }
        }

        function selectComparator(option) {
            return select_order.indexOf(option.widget_type)
        }

        function saveProduct(cart_id) {
            function onSaveProductSuccess(data) {
                //$window.location.href = currentHost() + '/cart';
                vm.cart = angular.copy(data);
                vm.addingToCart = false;
                vm.showCartPanel = true;
                $rootScope.$broadcast(cartEvents.cartUpdated, { cart: vm.cart });
                $timeout(function() { vm.showCartPanel = false; }, 10000);
            }

            function onSaveProductError(err) {
                switch (err.status) {
                    case 400: //bad request
                        if (err.data.message && err.data.message.toUpperCase().includes('STOCK')) {
                            vm.stock = 0;
                        }
                        break;
                        //not my cart
                    case 401:
                        //cart doesn't exist
                    case 404:
                        //cart not valid
                    case 409:
                        cartDataService.createCart(onCreateCartSuccess, onCreateCartError);
                        break;
                    default:
                        console.log(err);
                        break;
                }
                vm.addingToCart = false;
            }
            cartDataService.addProduct({
                product_id: vm.product.id,
                price_stock_id: vm.reference_id,
                quantity: vm.quantity
            }, {
                id: cart_id
            }, onSaveProductSuccess, onSaveProductError);
        }

        function onCreateCartSuccess(data) {
            var cart_id = angular.copy(data.id);
            localStorageUtilService.setLocalStorage('cart_id', cart_id);
            saveProduct(cart_id);
        }

        function onCreateCartError(err) {
            console.log(err);
        }

        function addToCart(form) {
            form.$setSubmitted();
            if (form.$valid && vm.reference_id) {
                vm.addingToCart = true;
                vm.showCartPanel = false;
                var cart_id = localStorageUtilService.getLocalStorage('cart_id');
                if (cart_id) {
                    saveProduct(cart_id);
                } else {
                    cartDataService.createCart(onCreateCartSuccess, onCreateCartError);
                }
            }
        }

        function setLoved() {
            function setLovedSuccess(data) {
                getProduct();
            }

            function setLovedError(err) {
                if (err.status === 401) openSignUpModal('loved');
            }

            //if is not loved
            if (!vm.product.isLoved) {
                lovedDataService.setLoved({
                    product_id: product.id
                }, setLovedSuccess, setLovedError);

            }
            //(if it's loved, delete it)
            if (vm.product.isLoved) {
                lovedDataService.deleteLoved({
                    productId: product.id
                }, setLovedSuccess, setLovedError)
            }
        }

        function setBox() {
            function onGetBoxSuccess(data) {
                var modalInstance = $uibModal.open({
                    component: 'modalSaveBox',
                    size: 'sm',
                    resolve: {
                        productId: function() {
                            return product.id;
                        },
                        boxes: function() {
                            return data;
                        }
                    }
                });

                modalInstance.result.then(function() {
                    getProduct();
                });
            }

            function onGetBoxError(err) {
                if (err.status === 401 || err.status === 404)
                    openSignUpModal('boxes')
            }

            boxDataService.getBoxPriv(null, onGetBoxSuccess, onGetBoxError);
        }

        function openSignUpModal(component) {
            var modalInstance = $uibModal.open({
                component: 'modalSignUpLoved',
                size: 'sm',
                resolve: {
                    icon: function() {
                        return component;
                    }
                }
            });
        }

        function getCart() {
            var cart_id = localStorageUtilService.getLocalStorage('cart_id');

            function onGetCartSuccess(data) {
                vm.cart = angular.copy(data);
                cartService.parseTags(vm.cart, vm.tags);
                cartService.setTotalItems(vm.cart);
                cartService.setProductsAmount(vm.cart);
            }

            function onGetCartError(err) {
                createCart();
                UtilService.onError(err);
            }

            if (cart_id) {
                cartDataService.getCart({ id: cart_id }, onGetCartSuccess, onGetCartError);
            } else {
                createCart();
            }
        }

        function sendComment() {
            if (!UtilService.getConnectedUser()) {
                openSignUpModal();
            } else {
                function onSendCommentSuccess(data) {
                    vm.product.comments = data.comments;
                    if (vm.newComment.stars > 0) {
                        setProductValoration();
                    }
                    vm.newComment = { text: '', stars: 0 };
                }
                productDataService.sendProductComment({ text: vm.newComment.text, stars: vm.newComment.stars }, { idProduct: vm.product.id }, onSendCommentSuccess, UtilService.onError);
            }
        }

        function showReplyComment(comment) {
            if (!UtilService.getConnectedUser()) {
                openSignUpModal();
            } else {
                comment.newReply = { text: '' };
                comment.showReply = true;
            }
        }

        function sendCommentReply(comment) {
            if (!UtilService.getConnectedUser()) {
                openSignUpModal();
            } else {
                function onSendReplySuccess(data) {
                    comment.newReply = { text: '' };
                    comment.showReply = false;
                }
                productDataService.sendProductComment({ text: vm.newComment.text }, { idProduct: vm.product.id, idComment: comment.id }, onSendReplySuccess, UtilService.onError);
            }
        }
    }

    angular.module('product')
        .controller('detailProductCtrl', controller)

}());