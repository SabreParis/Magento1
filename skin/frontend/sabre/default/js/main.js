var $jQuery = jQuery.noConflict();
jQuery(document).ready(function () {
    //décalage galerie image - fiche produit
    var rightBlockHeight = jQuery('.product-view #gallery .bloc.right').height();
    var leftBlockHeight = jQuery('.product-view #gallery .bloc.left').height();
    var img = jQuery('.product-view #gallery .bloc img');
    var height;

    if(img.length){
        if (window.matchMedia("(min-width: 481px)").matches) {
            if(rightBlockHeight > leftBlockHeight){
                height = leftBlockHeight;
            }
            else if(rightBlockHeight < leftBlockHeight) {
                height = rightBlockHeight ;
            }
            else {
              height = 465;
            }

            jQuery('.product-view #gallery .bloc.right').css( "height", height );
            jQuery('.product-view #gallery .bloc.left').css( "height", height );
        }
    }

    //sizing guide
    jQuery("#sizing-guide").click(function(){
        if(jQuery('.block_sizing').is(":hidden")){
            jQuery(".block_sizing").slideDown(500);

        }else{
            jQuery(".block_sizing").slideUp(500);
        }
    });

    jQuery(".iframe1 .text").click(function(){
        jQuery(".iframe1 iframe")[0].src += "?autoplay=1";
        jQuery(this).unbind("click");
        jQuery(".iframe1 .text").css({'display':'none'});
    });

    jQuery(".iframe2 .text").click(function(){
        jQuery(".iframe2 iframe")[0].src += "?autoplay=1";
        jQuery(this).unbind("click");
        jQuery(".iframe2 .text").css({'display':'none'});
    });

    //minicart header
    var miniCartCta = jQuery(".cart.minicart");
    miniCartCta.click(function(event) {
        event.preventDefault();
        var _elem = jQuery(this), _parent = _elem.parent(), _isOpen = _elem.hasClass('brightness');
        if (_elem.hasClass('click')) {
            jQuery(".minicart_head#header-cart").slideToggle({
                duration: 'slow',
                start: function() {
                    if (!_isOpen) {
                        _elem.toggleClass('brightness');
                        _parent.toggleClass('color_back');
                    }
                },
                complete: function() {
                    if (_isOpen) {
                        _elem.toggleClass('brightness');
                        _parent.toggleClass('color_back');
                    }
                }
            });
        }
    });

    if (miniCartCta.hasClass('open')) {
        miniCartCta.click();
        var miniCartTimer = setTimeout(function() { miniCartCta.click(); }, 3000);
        jQuery(".minicart_head#header-cart").on('mouseover', function() { clearTimeout(miniCartTimer); });
    }



    // Gestion d'ajout dans le panier : increase
    jQuery(".quantityIncrease").click(function(){
        var inputQty = jQuery(this).parent('.qtyy').find('input.qty');
        var quantityDecrease = jQuery(this).parent('.qtyy').find('.quantityDecrease');
        var Quantity=inputQty.val();
        if(Quantity=="") Quantity=0;
        if (Quantity >= 0){quantityDecrease.removeClass("disabled");}

        Quantity=parseInt(Quantity)+1;
        return inputQty.val(Quantity.toString());
    });

    jQuery(".account").click(function(){
        if(jQuery('#header-account').is(":hidden")){
            jQuery("#header-account").slideDown(200);

        }else{
            jQuery("#header-account").slideUp(200);
        }
        jQuery("#header-account").parents().find('li.account-back').toggleClass('color_back');
        jQuery("#header-account").parents().find('a.account').toggleClass('brightness');

    });


    // Gestion d'ajout dans le panier : decrease
    jQuery(".quantityDecrease").click(function () {
        var inputQty = jQuery(this).parent('.qtyy').find('input.qty');
        var quantityDecrease = jQuery(this).parent('.qtyy').find('.quantityDecrease');
        var Quantity = inputQty.val();
        if (Quantity == "") Quantity = 0;
        Quantity = parseInt(Quantity) - 1;
        if (Quantity < 0) Quantity = 0;
        if (Quantity <= 0){quantityDecrease.addClass("disabled");}

        return inputQty.val(Quantity.toString());
    });

    jQuery("#collection  iframe").each(function () {
        var iframheight = jQuery(this).width();
        jQuery(this).height(iframheight);
        jQuery(this).parent().height(iframheight);
    });

    // Fiche produit
    jQuery('a[href="#gallery"]').click(function (event) {
        event.preventDefault();
        var _id = jQuery(this).attr("href"),
            _elem = jQuery(_id);
        if (_elem.length) {
            var offset = _elem.offset().top;
            jQuery('html, body').animate({scrollTop: offset - 80}, 'slow');
        }
    });

    jQuery(".product-description .short").click(function () {
        jQuery(".product-description .full").toggle("fast");
        jQuery(".product-description .less").toggle("fast");
        jQuery(this).toggleClass("clicked");
    });

    jQuery(".bloc-left .old-price").clone().prependTo(jQuery(".bloc-left .price-box"));
    jQuery(".bloc-left .old-price").parent().addClass('blocRemise');

    // Quantité



    jQuery(".decrease").addClass("disabled");

    /* Get initial price, discount price and currency */
    var realPrice, currency, realDiscountPrice;
    realPrice = jQuery('.price-box').find('.price').text();
    realDiscountPrice = jQuery('.price-box').find('.special-price .price').text()

    function isDiscount() {

        return jQuery('.price-box').find('.special-price .price').length;
    }

    currency = isDiscount()?realDiscountPrice.split(/\s+/,3).pop():realPrice.split(/\s+/).pop();

    realPrice = parseFloat(realPrice.replace(',','.'));
    realDiscountPrice = parseFloat(realDiscountPrice.replace(',','.'));


    /* Get discount */
    function getNewPrice(qty) {

        return (Math.round(realPrice*qty*100)/100).toString().replace('.',',')+' '+currency;
    }

    /* Get discount */

    function getNewDiscount(qty) {
        if (isDiscount()) {
            return (Math.round(realDiscountPrice*qty*100)/100).toString().replace('.',',')+' '+currency;
        }
    }

    function changePrice(qty) {
        jQuery('.price-box').find('.price').text(getNewPrice(qty));
        if (isDiscount()) {
            jQuery('.price-box').find('.special-price .price').text(getNewDiscount(qty));
        }
    }


    jQuery(".increase").click(function () {
        var inputQty = jQuery(this).parents('.bloc-left').find('input.qty'),
            quantityDecrease = jQuery(this).parents('.bloc-left').find('.decrease'),
            Quantity = inputQty.val();

        if (Quantity == "") Quantity = 1;
        if (Quantity >= 1) {
            quantityDecrease.removeClass("disabled");
        }
        Quantity = parseInt(Quantity) + 1;
        changePrice(Quantity);

        return inputQty.val(Quantity.toString());
    });

    // Gestion d'ajout dans le panier : decrease

    jQuery(".decrease").click(function () {
        var inputQty = jQuery(this).parents('.bloc-left').find('input.qty');
        var quantityDecrease = jQuery(this).parents('.bloc-left').find('.decrease');
        var Quantity = inputQty.val();
        if (Quantity == "") Quantity = 1;
        Quantity = parseInt(Quantity) - 1;
        changePrice(Quantity);

        if (Quantity < 1) Quantity = 1;
        if (Quantity <= 1) {
            quantityDecrease.addClass("disabled");
        }
        return inputQty.val(Quantity.toString());
    });

    //leave qty textbox

        jQuery('#qty').focusout(function() {
            if(jQuery(this).val()<=0){
                jQuery(this).val(1);
            }
        })



    var _updateInputsRadio = function(selector){
        jQuery(selector).each(function(){
            jQuery(this).next().removeClass("checked");
            if(jQuery(this).is(':checked')) {
                jQuery(this).next().addClass("checked");
            }
        });
    };

    _updateInputsRadio('.col-main .opc  input');
    jQuery('.col-main .opc  input').change(function () {
        _updateInputsRadio('.col-main .opc  input');
    });

    _updateInputsRadio('.col-main .my-account  input');
    jQuery('.col-main .my-account   input').change(function () {
        _updateInputsRadio('.col-main .my-account   input');
    });

    _updateInputsRadio('.col-main .cart  input');
    jQuery('.col-main .cart  input').change(function () {
        _updateInputsRadio('.col-main .cart  input');
    });

    // Cookies
    if (document.formCNIL) {
        document.formCNIL.acceptCookieCNIL[(/ga-disable/.test(document.cookie) ? 1 : 0)].checked = true;
    }

    // input Page Confidentialite

    _updateInputsRadio('.col-main .cookies  input');
    jQuery('.col-main .cookies  input').change(function () {
        _updateInputsRadio('.col-main .cookies  input');
    });

    //Navbar recherche
    jQuery(".search").click(function () {
        jQuery(".navbar-search").toggle("fast");
    });

    //Menu
    jQuery('#navbarCollapse.collapse') .on('show.bs.collapse', function(e) {
        jQuery(".nav-container").addClass("clicked");
    });
    jQuery('#navbarCollapse.collapse').on('hide.bs.collapse', function(e) {
        jQuery(".nav-container").removeClass("clicked");
    });

    //flech position Landing page
    var top= (jQuery(".global").height()/2)-(jQuery(".height-img").height()/2) ;
    jQuery(".position-top").css('top',top);

    //Filtre
    jQuery(".filter-nav .category-title").click(function () {
        if (jQuery(this).parent().hasClass("active")) {
            jQuery(this).parent().removeClass("active");
            jQuery(this).next().slideToggle("medium");
        }
        else {
            jQuery(".filter-nav .level-2").hide();
            jQuery(".filter-nav .level-1 > li.active").removeClass("active");
            jQuery(this).parent().addClass("active");
            jQuery(this).next().slideToggle("medium");
        }
    });

    jQuery(".filter-nav .item input").change(function () {
        var selected = jQuery(this).val();
        jQuery(".filter-nav .item input").each(function () {
            jQuery(this).next().removeClass("checked");
        });
        if (jQuery(this).is(':checked')) {
            jQuery(this).next().addClass("checked");
            jQuery(this).parents(".item").find(".selected").text(selected);
            hideFiltre();
        }
        else {
            jQuery(this).next().removeClass("checked");
        }
    });

    // Filtre couleurs
    jQuery(".filter-nav .couleurs input").change(function () {
        var selected = jQuery(this).val();
        var selected_bg = jQuery(this).next().find('img').attr('src');
        if (jQuery(this).is(':checked')) {
            if (selected != "tout") {
                jQuery(this).parents(".item").find(".selected").css('background-image', 'url(' + selected_bg + ')');
                jQuery(this).parents(".item").find(".selected").text('');
                jQuery(this).parents(".item").find(".selected").addClass("color");
            }
            else {
                jQuery(this).parents(".item").find(".selected").css('background', 'transparent');
                jQuery(this).parents(".item").find(".selected").removeClass("color");
            }
        }
    });

    //Delet filtre
    jQuery(".delete-filtre").click(function () {
        var selected = jQuery(".filter-nav .item .all input").val();
        jQuery(".filter-nav .item input").next().removeClass("checked");
        jQuery(".filter-nav .item .all input").prop("checked", true);
        jQuery(".filter-nav .item .all input").next().addClass("checked");
        jQuery(".filter-nav .item .all input").parents(".item").find(".selected").text(selected);
        jQuery(".filter-nav .item .all input").parents(".item").find(".selected").removeClass("color");
        jQuery(".filter-nav .item .all input").parents(".item").find(".selected").css('background', 'transparent');
    });

    //Hide filtre
    function hideFiltre() {
        jQuery(".filter-nav .level-2").slideUp("medium");
        jQuery(".filter-nav .level-1 > li.active").removeClass("active");
    }

    //Scroll
//    jQuery("#scroller").mousewheel(function(event, delta) {
//        this.scrollLeft -= (delta * 30);
//        event.preventDefault();
//    });

    jQuery(".more-articles .articles").mousewheel(function (event, delta) {
        this.scrollLeft -= (delta * 30);
        event.preventDefault();
    });

    //List produits
    jQuery('.slider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 0,
        minItems: 2,
        maxItems: getGridSize()
    });

    //Echantillon
    jQuery('.filter-nav .level-2 li label').hover(function () {
        jQuery('.filter-nav .level-2 .exp').toggleClass("show");
    });

    jQuery(".imagelink").hover(
        function () {
            var background = jQuery(this).data('background');
            if (jQuery(this).attr("data-background") == undefined) {
                jQuery('.exportimage').find(".filterimage").remove();
            } else {
                jQuery('.exportimage').append(jQuery("<img class ='filterimage' src='" + background + "' />"));
            }
        }, function () {
            jQuery('.exportimage').find(".filterimage").remove();
        }
    );
    jQuery("#all").hover(
        function () {
            jQuery('.exportimage').find(".filterimage").remove();
        });

});

jQuery(window).resize(function () {
    //décalage galerie image - fiche produit
    var rightHeight = jQuery('.product-view #gallery .bloc.right img').height();
    var leftHeight = jQuery('.product-view #gallery .bloc.left img').height();
    var heightBlock ;
    if (window.matchMedia("(min-width: 481px)").matches) {
        if(rightHeight > leftHeight){
            heightBlock = leftHeight;
        }
        else{
            heightBlock = rightHeight;
        }
        jQuery('.product-view #gallery .bloc.right').css("height", heightBlock);
        jQuery('.product-view #gallery .bloc.left').css("height" , heightBlock);
        jQuery( ".catalog-category-view .filter-nav .level-1" ).append( jQuery(".catalog-category-view .filter-nav .level-1 > li.filter-delete"));
    }
    else{
        jQuery('.product-view #gallery .bloc.right').css("height", '');
    }

    jQuery('.slider').each(function () {
        if (jQuery(this).length) {
            jQuery(this).data('flexslider').vars.maxItems = getGridSize();
        }
    });

    if (window.matchMedia("(min-width: 601px)").matches) {
        var imgHeight = jQuery(".main-container .billboards img").height();
        jQuery(".main-container .billboards-container").height(imgHeight);

        var imgHeight2 = jQuery(".main-container .billboards.active-slide img").height();
        jQuery(".main-container .billboards-container").height(imgHeight2);
    }


    jQuery("#collection  iframe").each(function () {

        var iframheight = jQuery(this).width();
        jQuery(this).height(iframheight);
        jQuery(this).parent().height(iframheight);
    });

    var top= (jQuery(".landing-page > div > img").height()/2)-(jQuery(".height-img").height()/2) ;
    jQuery(".position-top").css('top',top);


    if ((window.matchMedia("(max-width: 600px)").matches) && (window.matchMedia("(min-width: 481px)").matches)) {
        var blocHeight = jQuery(".list_produits .products-grid .product-image").height();
        var paddingAdd = 40;
        var newPosition = blocHeight + paddingAdd;
        jQuery(".list_produits .flex-direction-nav .flex-prev").css('margin-top',newPosition);
        jQuery(".list_produits .flex-direction-nav .flex-next").css('margin-top',newPosition);
    }

    if (window.matchMedia("(max-width: 480px)").matches) {
        var blocHeight2 = jQuery(".list_produits .products-grid .product-image").height();
        jQuery(".list_produits .flex-direction-nav .flex-prev").css('margin-top',blocHeight2);
        jQuery(".list_produits .flex-direction-nav .flex-next").css('margin-top',blocHeight2);
        jQuery( ".catalog-category-view .filter-nav .level-1" ).prepend( jQuery(".catalog-category-view .filter-nav .level-1 > li.filter-delete"));
    }

    /**
     * Evol Home
     **/

    if (window.matchMedia("(max-width: 600px)").matches){
        var blocSelector = jQuery(".list-categoris #mosaique .item");
        var blocWidth = blocSelector.width();
        var blocWidth2 = blocWidth * 2;
        jQuery(".list-categoris #mosaique .item:nth-child(2)").css('left',blocWidth);
        jQuery(".list-categoris #mosaique .item:nth-child(3)").css('left', blocWidth2);
    }

});

function getGridSize() {
    return (window.innerWidth <= 600) ? 2 : (window.innerWidth <= 1040) ? 3 : (window.innerWidth <= 1600) ? 4 : 5;
}

//Isotope
jQuery(window).load(function () {
    var mosaique = jQuery('#mosaique');
    if (mosaique.length) {
        mosaique.isotope({
            itemSelector: '.item',
            getSortData: {
                name: '.name',
                symbol: '.symbol',
                number: '.number parseInt',
                category: '[data-category]',
                weight: function (itemElem) {
                    var weight = jQuery(itemElem).find('.weight').text();
                    return parseFloat(weight.replace(/[\(\)]/g, ''));
                }
            }
        });
    }


    var collection = jQuery('#collection');
    if (collection.length) {
        collection.isotope({
            itemSelector: '.bloc',
            getSortData: {
                name: '.name',
                symbol: '.symbol',
                number: '.number parseInt',

                category: '[data-category]',
                weight: function (itemElem) {
                    var weight = jQuery(itemElem).find('.weight').text();
                    return parseFloat(weight.replace(/[\(\)]/g, ''));
                }
            }
        });
    }

    var galerie = jQuery('#galerie');
    if (galerie.length) {
        galerie.isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                columnWidth: '.grid-sizer'
            }
        });
    }


    var imgHeight = jQuery(".main-container .billboards img").height();
    jQuery(".main-container .billboards-container").height(imgHeight);

    if (navigator.appVersion.indexOf("Mac")!=-1 || navigator.appVersion.indexOf("Linux")!=-1){
        jQuery('.header-title .title p').css({'position': 'relative','bottom': '-5px'});
    }



    var top= (jQuery(".landing-page > div > img").height()/2)-(jQuery(".height-img").height()/2) ;
    jQuery(".position-top").css('top',top);

    if ((window.matchMedia("(max-width: 600px)").matches) && (window.matchMedia("(min-width: 481px)").matches)) {
        var blocHeight = jQuery(".list_produits .products-grid .product-image").height();
        var paddingAdd = 40;
        var newPosition = blocHeight + paddingAdd;
        jQuery(".list_produits .flex-direction-nav .flex-prev").css('margin-top',newPosition);
        jQuery(".list_produits .flex-direction-nav .flex-next").css('margin-top',newPosition);
    }

    if (window.matchMedia("(max-width: 480px)").matches) {
        var blocHeight2 = jQuery(".list_produits .products-grid .product-image").height();
        jQuery(".list_produits .flex-direction-nav .flex-prev").css('margin-top',blocHeight2);
        jQuery(".list_produits .flex-direction-nav .flex-next").css('margin-top',blocHeight2);
        jQuery( ".catalog-category-view .filter-nav .level-1" ).prepend( jQuery(".catalog-category-view .filter-nav .level-1 > li.filter-delete"));
    }

    /**
     * Evol Home
     *
     **/

    if (window.matchMedia("(max-width: 600px)").matches){
        var blocSelector = jQuery(".list-categoris #mosaique .item");
        var blocWidth = blocSelector.width();
        var blocWidth2 = blocSelector.width() * 2;

        jQuery(".list-categoris #mosaique .item:nth-child(2)").css('left',blocWidth);
        jQuery(".list-categoris #mosaique .item:last-child").css('left',blocWidth2);
    }

});

jQuery(document).ready(function(){
    jQuery('.scrollbar-macosx').scrollbar();
});

jQuery(document).scroll(function()
{
    jQuery(".catalog.list_produits .scroller-page-container .products-grid li.item a").unbind('touchstart touchend');
});