<?php

/**
 * created: 2015
 *
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

require_once Mage::getModuleDir('controllers', 'Infomodus_Upsap') . DS . 'IndexController.php';

class Sabre_Upsap_IndexController extends Infomodus_Upsap_IndexController
{

    public function frameAction()
    {
        $url = $this->getRequest()->getParam('url');

        $rawHTML = <<<HTML
<!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
                overflow: hidden;
                height:100%;
            }
        </style>
        <script type="text/javascript">
            window.onload = function() {
                var el = document.querySelector("iframe"),
                    p7 = window.parent.document.getElementById('div_upsap_access_points7172837');

                el.style.width = '100%';
                el.style.height = window.innerHeight + 'px';

                window.parent.onresize = function() {
                    el.style.height = window.parent.innerHeight + 'px';
                    if (window.parent.innerWidth > 960) {
                        p7.style.left = ((window.parent.innerWidth / 2) - (p7.getWidth() / 2)) + 'px';
                        el.style.width = '960px';
                    } else {
                        el.style.width = '100%';
                    }
                }
            };
        </script>
        <iframe src="//www.ups.com/lsw/invoke?{$url}" frameborder="0" width="100%" height="750px" name="dialog_upsap_access_points2"></iframe>
    </body>
</html>
HTML;

        $this->getResponse()->setHeader('Access-Control-Allow-Origin', 'http://www.ups.com');
        $this->getResponse()->setBody($rawHTML);
    }

}
