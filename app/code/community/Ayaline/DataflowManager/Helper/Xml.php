<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

/**
 * Class Ayaline_DataflowManager_Helper_Xml
 *
 * credit: https://gist.github.com/stojg/3045663
 */
class Ayaline_DataflowManager_Helper_Xml extends Ayaline_DataflowManager_Helper_Data
{

    protected $_readHandle = null;
    protected $_searchWithRegex = false;
    protected $_lastMbEregSearchPos = false;

    protected function _getPosition($needle, $from = 0, $chunk = 1024, $previousData = '')
    {
        fseek($this->_readHandle, $from, SEEK_SET);

        $data = fread($this->_readHandle, $chunk);

        $needlePosition = false;
        if ($this->_searchWithRegex) {
            if (mb_ereg_search_init($previousData . $data, $needle)) {
                $this->_lastMbEregSearchPos = mb_ereg_search_pos();
                if (is_array($this->_lastMbEregSearchPos)) {
                    $needlePosition = $this->_lastMbEregSearchPos[0];
                }
            }
        } else {
            $encodings = array("ISO-8859-15", "ISO-8859-1", "UTF-8");
            $encoding = mb_detect_encoding($previousData . $data, $encodings);
            $needlePosition = mb_strpos($previousData . $data, $needle, null, $encoding);
        }

        if ($needlePosition !== false) {
            return $needlePosition + $from - mb_strlen($previousData);
        }

        if (feof($this->_readHandle)) {
            return false;
        }

        return $this->_getPosition($needle, ($chunk + $from), $chunk, $data);
    }

    /**
     * @param string $file
     * @param string $openTag
     * @param string $closeTag
     * @param string $callback
     * @param array  $callbackArgs
     * @param bool   $useRegex
     * @throws Mage_Core_Exception
     */
    public function read($file, $openTag, $closeTag, $callback, $callbackArgs = array(), $useRegex = false)
    {
        $this->_readHandle = @fopen($file, 'r');
        if ($this->_readHandle === false) {
            Mage::throwException("Can't open file: {$file}");
        }

        $this->_searchWithRegex = $useRegex;
        $cursorPosition = 0;

        while (true) {
            $this->_lastMbEregSearchPos = false;

            $startPosition = $this->_getPosition($openTag, $cursorPosition);

            if ($startPosition === false) {
                break;
            }

            $endPosition = $this->_getPosition($closeTag, $startPosition);
            if ($this->_searchWithRegex) {
                if (is_array($this->_lastMbEregSearchPos)) {
                    $endPosition += $this->_lastMbEregSearchPos[1];
                }
            } else {
                $endPosition += mb_strlen($closeTag);
            }

            fseek($this->_readHandle, $startPosition);

            $nodeAsString = fread($this->_readHandle, ($endPosition - $startPosition));

            call_user_func_array($callback, array($nodeAsString, $callbackArgs));


            $cursorPosition = ftell($this->_readHandle);
        }
    }

}