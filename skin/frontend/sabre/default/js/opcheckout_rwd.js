/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    design
 * @package     rwd_default
 * @copyright   Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

Checkout.prototype.gotoSection = function (section, reloadProgressBlock) {
    // Adds class so that the page can be styled to only show the "Checkout Method" step
    if ((this.currentStep == 'login' || this.currentStep == 'billing') && section == 'billing') {
        $j('body').addClass('opc-has-progressed-from-login');
    }

    if (reloadProgressBlock) {
        this.reloadProgressBlock(this.currentStep);
        if (this.currentStep == "shipping_method") {
            this.reloadProgressBlock('shipping');   //update shipping address section after selecting a shipping method
        }
    }
    this.currentStep = section;
    var sectionElement = $('opc-' + section);
    sectionElement.addClassName('allow');
    this.accordion.openSection('opc-' + section);

    // Scroll viewport to top of checkout steps for smaller viewports
    //if (Modernizr.mq('(max-width: ' + bp.xsmall + 'px)')) {
    //    $j('html,body').animate({scrollTop: $j('#checkoutSteps').offset().top}, 800);
    //}

    if (!reloadProgressBlock) {
        this.resetPreviousSteps();
    }

    if (section === 'shipping_method') {
        initUPSAP(); // init UPS AP
    }

    if (section == 'shipping') {
        document.getElementById('shipping:same_as_billing').checked = false;
        $j('label[for="shipping:same_as_billing"]').removeClass("checked");
        if (document.getElementById('shipping:use_for_Withdrawal_store').checked) {
            document.getElementById('shipping:use_for_Withdrawal_store').checked = false;
            $j('label[for="shipping:use_for_Withdrawal_store"]').removeClass("checked");
        }
        console.log("test goToSection");
    }
};

//Return to shipping method if the method for shipping was withrawal or UPS Access Point.
Checkout.prototype.changeSection = Checkout.prototype.changeSection.wrap(function (changeSection, section) {
    var radios_methods = document.getElementsByName('shipping_method');
    for (var i = 0, length = radios_methods.length; i < length; i++) {
        if (radios_methods[i].checked) {
            if (radios_methods[i].value == "sabreshop_sabreshop" || radios_methods[i].value.indexOf("owebiashipping4") >= 0) {
                if (section == "opc-shipping" && this.currentStep != "shipping_method") {
                    section = "opc-shipping_method";
                    break;
                }
            }
        }
    }
    changeSection(section);
});

