<?php

/**
 * created : 13 juil. 2012
 *
 * @category  Ayaline
 * @package   Ayaline_Core
 * @author    aYaline
 * @copyright Ayaline - 2012 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */
class Ayaline_Core_Helper_Image extends Mage_Core_Helper_Abstract
{

    protected $_imageModel;
    protected $_scheduleResize = false;
    protected $_scheduleRotate = false;
    protected $_angle;

    protected $_watermark;
    protected $_watermarkPosition;
    protected $_watermarkSize;
    protected $_watermarkImageOpacity;

    protected $_model;
    protected $_imageFile;
    protected $_placeholder;

    /**
     * Reset all previos data
     *
     * @return Ayaline_Core_Helper_Image
     */
    protected function _reset()
    {
        $this->_imageModel = null;
        $this->_scheduleResize = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_watermark = null;
        $this->_watermarkPosition = null;
        $this->_watermarkSize = null;
        $this->_watermarkImageOpacity = null;
        $this->_model = null;
        $this->_imageFile = null;

        return $this;
    }

    public function init($model, $attributeName, $imageFile = null)
    {
        $this->_reset();
        $this->_setImageModel($model->getImageModel());
        $this->_getImageModel()->setDestinationSubdir($attributeName);
        $this->setModel($model);

        $this->setWatermark(Mage::getStoreConfig("design/watermark/{$this->_getImageModel()->getDestinationSubdir()}_image"));
        $this->setWatermarkImageOpacity(Mage::getStoreConfig("design/watermark/{$this->_getImageModel()->getDestinationSubdir()}_imageOpacity"));
        $this->setWatermarkPosition(Mage::getStoreConfig("design/watermark/{$this->_getImageModel()->getDestinationSubdir()}_position"));
        $this->setWatermarkSize(Mage::getStoreConfig("design/watermark/{$this->_getImageModel()->getDestinationSubdir()}_size"));

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            // add for work original size
            $this->_getImageModel()->setBaseFile($this->getModel()->getData($this->_getImageModel()->getDestinationSubdir()));
        }

        return $this;
    }

    /**
     * Schedule resize of the image
     * $width *or* $height can be null - in this case, lacking dimension will be calculated.
     *
     * @see Mage_Catalog_Model_Product_Image
     * @param int $width
     * @param int $height
     * @return Ayaline_Core_Helper_Image
     */
    public function resize($width, $height = null)
    {
        $this->_getImageModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;

        return $this;
    }

    /**
     * Set image quality, values in percentage from 0 to 100
     *
     * @param int $quality
     * @return Ayaline_Core_Helper_Image
     */
    public function setQuality($quality)
    {
        $this->_getImageModel()->setQuality($quality);

        return $this;
    }

    /**
     * Guarantee, that image picture width/height will not be distorted.
     * Applicable before calling resize()
     * It is true by default.
     *
     * @see Mage_Catalog_Model_Product_Image
     * @param bool $flag
     * @return Ayaline_Core_Helper_Image
     */
    public function keepAspectRatio($flag)
    {
        $this->_getImageModel()->setKeepAspectRatio($flag);

        return $this;
    }

    /**
     * Guarantee, that image will have dimensions, set in $width/$height
     * Applicable before calling resize()
     * Not applicable, if keepAspectRatio(false)
     *
     * $position - TODO, not used for now - picture position inside the frame.
     *
     * @see Mage_Catalog_Model_Product_Image
     * @param bool  $flag
     * @param array $position
     * @return Ayaline_Core_Helper_Image
     */
    public function keepFrame($flag, $position = array('center', 'middle'))
    {
        $this->_getImageModel()->setKeepFrame($flag);

        return $this;
    }

    /**
     * Guarantee, that image will not lose transparency if any.
     * Applicable before calling resize()
     * It is true by default.
     *
     * $alphaOpacity - TODO, not used for now
     *
     * @see Mage_Catalog_Model_Product_Image
     * @param bool $flag
     * @param int  $alphaOpacity
     * @return Ayaline_Core_Helper_Image
     */
    public function keepTransparency($flag, $alphaOpacity = null)
    {
        $this->_getImageModel()->setKeepTransparency($flag);

        return $this;
    }

    /**
     * Guarantee, that image picture will not be bigger, than it was.
     * Applicable before calling resize()
     * It is false by default
     *
     * @param bool $flag
     * @return Ayaline_Core_Helper_Image
     */
    public function constrainOnly($flag)
    {
        $this->_getImageModel()->setConstrainOnly($flag);

        return $this;
    }

    /**
     * Set color to fill image frame with.
     * Applicable before calling resize()
     * The keepTransparency(true) overrides this (if image has transparent color)
     * It is white by default.
     *
     * @see Mage_Catalog_Model_Product_Image
     * @param array $colorRGB
     * @return Ayaline_Core_Helper_Image
     */
    public function backgroundColor($colorRGB)
    {
        // assume that 3 params were given instead of array
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getImageModel()->setBackgroundColor($colorRGB);

        return $this;
    }

    public function rotate($angle)
    {
        $this->setAngle($angle);
        $this->_getImageModel()->setAngle($angle);
        $this->_scheduleRotate = true;

        return $this;
    }

    /**
     * Add watermark to image
     * size param in format 100x200
     *
     * @param string $fileName
     * @param string $position
     * @param string $size
     * @param int    $imageOpacity
     * @return Ayaline_Core_Helper_Image
     */
    public function watermark($fileName, $position, $size = null, $imageOpacity = null)
    {
        $this->setWatermark($fileName)
            ->setWatermarkPosition($position)
            ->setWatermarkSize($size)
            ->setWatermarkImageOpacity($imageOpacity);

        return $this;
    }

    public function placeholder($fileName)
    {
        $this->_placeholder = $fileName;
    }

    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getImageModel()->getDestinationSubdir();
            $this->_placeholder = $this->_getImageModel()->getSkinPlaceholderPath() . $attr . '.jpg';
        }

        return $this->_placeholder;
    }

    public function __toString()
    {
        try {
            if ($this->getImageFile()) {
                $this->_getImageModel()->setBaseFile($this->getImageFile());
            } else {
                $this->_getImageModel()->setBaseFile($this->getModel()->getData($this->_getImageModel()->getDestinationSubdir()));
            }

            if ($this->_getImageModel()->isCached()) {
                return $this->_getImageModel()->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $this->_getImageModel()->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $this->_getImageModel()->resize();
                }

                if ($this->getWatermark()) {
                    $this->_getImageModel()->setWatermark($this->getWatermark());
                }

                $url = $this->_getImageModel()->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }

        return $url;
    }

    /**
     * Enter description here...
     *
     * @return Ayaline_Core_Helper_Image
     */
    protected function _setImageModel($model)
    {
        $this->_imageModel = $model;

        return $this;
    }

    /**
     * @return Ayaline_Core_Model_Image_Abstract
     */
    protected function _getImageModel()
    {
        return $this->_imageModel;
    }

    protected function setAngle($angle)
    {
        $this->_angle = $angle;

        return $this;
    }

    protected function getAngle()
    {
        return $this->_angle;
    }

    /**
     * Set watermark file name
     *
     * @param string $watermark
     * @return Ayaline_Core_Helper_Image
     */
    protected function setWatermark($watermark)
    {
        $this->_watermark = $watermark;
        $this->_getImageModel()->setWatermarkFile($watermark);

        return $this;
    }

    /**
     * Get watermark file name
     *
     * @return string
     */
    protected function getWatermark()
    {
        return $this->_watermark;
    }

    /**
     * Set watermark position
     *
     * @param string $position
     * @return Ayaline_Core_Helper_Image
     */
    protected function setWatermarkPosition($position)
    {
        $this->_watermarkPosition = $position;
        $this->_getImageModel()->setWatermarkPosition($position);

        return $this;
    }

    /**
     * Get watermark position
     *
     * @return string
     */
    protected function getWatermarkPosition()
    {
        return $this->_watermarkPosition;
    }

    /**
     * Set watermark size
     * param size in format 100x200
     *
     * @param string $size
     * @return Ayaline_Core_Helper_Image
     */
    public function setWatermarkSize($size)
    {
        $this->_watermarkSize = $size;
        $this->_getImageModel()->setWatermarkSize($this->parseSize($size));

        return $this;
    }

    /**
     * Get watermark size
     *
     * @return string
     */
    protected function getWatermarkSize()
    {
        return $this->_watermarkSize;
    }

    /**
     * Set watermark image opacity
     *
     * @param int $imageOpacity
     * @return Ayaline_Core_Helper_Image
     */
    public function setWatermarkImageOpacity($imageOpacity)
    {
        $this->_watermarkImageOpacity = $imageOpacity;
        $this->_getImageModel()->setWatermarkImageOpacity($imageOpacity);

        return $this;
    }

    /**
     * Get watermark image opacity
     *
     * @return int
     */
    protected function getWatermarkImageOpacity()
    {
        if ($this->_watermarkImageOpacity) {
            return $this->_watermarkImageOpacity;
        }

        return $this->_getImageModel()->getWatermarkImageOpacity();
    }

    protected function setModel($model)
    {
        $this->_model = $model;

        return $this;
    }

    protected function getModel()
    {
        return $this->_model;
    }

    protected function setImageFile($file)
    {
        $this->_imageFile = $file;

        return $this;
    }

    protected function getImageFile()
    {
        return $this->_imageFile;
    }

    /**
     * @return array
     */
    protected function parseSize($string)
    {
        $size = explode('x', strtolower($string));
        if (sizeof($size) == 2) {
            return array(
                'width'  => ($size[0] > 0) ? $size[0] : null,
                'heigth' => ($size[1] > 0) ? $size[1] : null,
            );
        }

        return false;
    }

    /**
     * Retrieve original image width
     *
     * @return int|null
     */
    public function getOriginalWidth()
    {
        return $this->_getImageModel()->getImageProcessor()->getOriginalWidth();
    }

    /**
     * Retrieve original image height
     *
     * @return int|null
     */
    public function getOriginalHeight()
    {
        return $this->_getImageModel()->getImageProcessor()->getOriginalHeight();
    }

    /**
     * Retrieve Original image size as array
     * 0 - width, 1 - height
     *
     * @return array
     */
    public function getOriginalSizeArray()
    {
        return array(
            $this->getOriginalWidth(),
            $this->getOriginalHeight()
        );
    }

    /**
     * Check - is this file an image
     *
     * @param string $filePath
     * @return bool
     * @throw Mage_Core_Exception
     */
    public function validateUploadFile($filePath)
    {
        if (!getimagesize($filePath)) {
            Mage::throwException(Mage::helper('ayalinecore')->__('Disallowed file type.'));
        }

        return true;
    }

}