/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

Sabre = window.Sabre || {};
Sabre.Express = window.Sabre.Express || {};

(function ($) {
    'use strict';

    var Express, dom = $('html, body');

    Express = {
        _addToCartBtn: null,
        _ctaElem: null,
        _ctaLabelElem: null,
        _tableState: null,
        _tableStateExpires: 3600, // 1h
        _tableForm: null,
        _messagesContainer: null,
        _headerMiniCartCountElem: null,
        _headerMiniCartItemsElem: null,
        _productViewElem: null,

        init: function () {
            Express._ctaElem = $('#product-cmd-button');
            if (Express._ctaElem.length) {
                Express._ctaLabelElem = Express._ctaElem.find('span');
                Express._addToCartBtn = $('#product-addtocart-button');
                Express._productViewElem = $('.product-essential');

                Express._ctaElem.off('click')
                    .on('click', function (event) {
                        event.preventDefault();

                        Express.toggleTable(true);
                    });

                if (Cookies.get('express-table-state')) {
                    Express.toggleTable(false);
                }
            }
            Express._headerMiniCartCountElem = $('.cart_back .cart.minicart');
            Express._headerMiniCartItemsElem = $('.header-minicart');
        },

        toggleTable: function (scrollToTable) {
            if (Express._tableState === null) {
                $.ajax(Express._ctaElem.data('expressTableUrl'), {
                    type: 'POST',
                    cache: false,
                    data: {product_id: Express._ctaElem.data('productId')},
                    dataType: 'html',
                    beforeSend: function () {
                        Express._ctaElem.prop('disabled', true);
                    },
                    success: function (data, textStatus, jqXHR) {
                        $('.product-essential').after(data);
                        Express._tableForm = $('#table-express-form');
                        Express._messagesContainer = Express._tableForm.find('#express-messages');
                        Express.updateTableState(false, scrollToTable);
                        Express.initTableActions();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {

                    },
                    complete: function (jqXHR, textStatus) {
                        Express._ctaElem.prop('disabled', false);
                    }
                });
            }

            Express.updateTableState(undefined, scrollToTable);
        },

        initTableActions: function () {
            var _inputElements = Express._tableForm.find('input[type="text"]'),
                _submitBtn = Express._tableForm.find('button[type="submit"]'),
                _nbChange = 0;

            Express._tableForm.off('submit')
                .on('submit', function (event) {
                    event.preventDefault();

                    $.ajax(Express._tableForm.attr('action'), {
                        type: 'POST',
                        cache: false,
                        data: Express._tableForm.serialize(),
                        dataType: 'json',
                        beforeSend: function () {
                            if (_nbChange === 0) {
                                return false;
                            }
                            _submitBtn.prop('disabled', true);
                            _inputElements.prop('disabled', true);
                            Express._tableForm.addClass('processing');
                            Express._messagesContainer.html('');
                        },
                        success: function (data, textStatus, jqXHR) {
                            if (data.success) {
                                $('.product-essential').after(data.html);
                                Express._tableForm.remove();
                                Express._tableForm = $('#table-express-form');
                                Express._messagesContainer = Express._tableForm.find('#express-messages');
                                Express.updateTableState(false);
                                Express.initTableActions();
                                Express.updateHeaderMiniCart(data.cart_header_count, data.cart_header_html);
                            } else {
                                Express._messagesContainer.html(data.messages);
                            }
                        },
                        complete: function (jqXHR, textStatus) {
                            _submitBtn.prop('disabled', false);
                            _inputElements.prop('disabled', false);
                            Express._tableForm.removeClass('processing');
                        }
                    });
                });

            _inputElements.numeric({negative: false, decimal: false});
            _inputElements.on('change', function () {
                var _elem = $(this), _elemValue = _elem.val(),
                    _liParent = _elem.parent().parents('li');

                if (_elemValue == '') {
                    _liParent.removeClass('item-selected');

                    if (_nbChange > 0) {
                        _nbChange--;
                    }
                } else if (_elemValue == 0) {
                    //_elem.val(1);
                    _liParent.addClass('item-selected');
                    _nbChange++
                } else {
                    _liParent.addClass('item-selected');
                    _nbChange++;
                }
            });
        },

        updateTableState: function (flag, scrollToTable) {
            Express._tableState = (typeof flag === 'undefined') ? Express._tableState : flag;

            if (Express._tableState === false) {
                Express._addToCartBtn.addClass('hideButton');
                Express._ctaLabelElem.html(Express._ctaElem.data('expressLabelOn'));
                Express._tableForm.show();
                if (scrollToTable) {
                    dom.animate({scrollTop: Express._productViewElem.offset().top + Express._productViewElem.height()}, 'slow');
                }

                Express._tableState = true;
                Cookies.set('express-table-state', true, {expires: Express._tableStateExpires});
            } else if (Express._tableState === true) {
                Express._addToCartBtn.removeClass('hideButton');
                Express._tableForm.hide();
                Express._ctaLabelElem.html(Express._ctaElem.data('expressLabelOff'));

                Express._tableState = false;
                Cookies.expire('express-table-state');
            }
        },

        updateHeaderMiniCart: function (count, html) {
            if (count > 0) {
                Express._headerMiniCartCountElem.addClass('click');
                Express._headerMiniCartCountElem.html('<span class="cart-counter">' + count + '</span>');
            } else {
                Express._headerMiniCartCountElem.removeClass('click');
                Express._headerMiniCartCountElem.html('');
            }
            Express._headerMiniCartCountElem.removeClass('brightness');
            Express._headerMiniCartCountElem.parent().removeClass('color_back');

            Express._headerMiniCartItemsElem.html(html);

            if (count > 0) {
                Express._headerMiniCartCountElem.click();
                var miniCartTimer = setTimeout(function() { Express._headerMiniCartCountElem.click(); }, 3000);
                Express._headerMiniCartItemsElem.find("#header-cart").on('mouseover', function() { clearTimeout(miniCartTimer); });
            }
        }

    };

    Express.init();

})(jQuery);