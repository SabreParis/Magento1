/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

if (document.getElementById('product-view-gallery')) {
    (function ($) {
        var _img = $("#image");
        _img.elevateZoom({
            zoomType: "lens",
            lensShape: "round",
            lensSize: 200,
            responsive: true
        });

        $(window).resize(function() {
            $('.zoomContainer').remove();
            _img.elevateZoom({
                zoomType: "lens",
                lensShape: "round",
                lensSize: 200,
                responsive: true
            });
        });
    })(jQuery);
}
