<?php
/**
 * created : 2016
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2013 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
?>

<?php
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 600');
?>
<html>
<head>
    <title>Site en cours de maintenance - Site under maintenance</title>
    <style type="text/css">
        body {
            background: url('../../media/maintenance.png') no-repeat scroll center 0 #FFFFFF;
            margin: 0;
            padding: 0;
            min-height: 1240px;
        }
    </style>
    <meta http-equiv="refresh" content="60;url=/">
</head>
<body>

</body>
</html>
