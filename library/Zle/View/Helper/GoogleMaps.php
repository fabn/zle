<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * GoogleMaps Helper
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_Googlemaps extends Zend_View_Helper_Abstract
{
    /**
     * Google maps url
     */
    const GOOGLE_MAPS_URL = 'http://maps.google.com/maps?';

    /**
     * @var array Default options for maps
     */
    protected static $defaultOptions = array(
        't' => 'm',          // map type
        'hl' => 'en',        // language
        'f' => 'q',          // output control
        'output' => 'embed', // embedded google map
        'z' => '10',         // zoom level
        'ie' => 'UTF8',      // input encoding
        'oe' => 'UTF8');     // output encoding

    /**
     * @var array custom options
     */
    private $_options = array();

    /**
     * @var string IFRAME default width
     */
    private $_height = 350;

    /**
     * @var string IFRAME default width
     */
    private $_width = '100%';

    /**
     * @var Zend_Locale
     */
    private $_locale;

    /**
     * Height getter
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * Width getter
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Locale getter
     * 
     * @return Zend_Locale
     */
    public function getLocale()
    {
        if (!$this->_locale) {
            // init locale based on the following order
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                // application wide locale
                $this->_locale = Zend_Registry::get('Zend_Locale');
            } else {
                // default automatic locale
                $this->_locale = new Zend_Locale();
            }
        }
        return $this->_locale;
    }

    /**
     * Height setter
     *
     * @param string $height the height to set in pixels
     *                       or percentage (followed by a % sign)
     *
     * @return void
     */
    public function setHeight($height)
    {
        $this->_height = $height;
    }

    /**
     * Width setter
     *
     * @param string $width the width to set in pixels
     *                      or percentage (followed by a % sign)
     *
     * @return void
     */
    public function setWidth($width)
    {
        $this->_width = $width;
    }

    /**
     * Set the maps locale
     *
     * @param string|Zend_Locale $locale the locale to set
     *
     * @see Zend_Locale::__construct for the string arguments
     * @return void
     */
    public function setLocale($locale)
    {
        if ($locale instanceof Zend_Locale) {
                        $this->_locale = $locale;
        } else {
            $this->_locale = new Zend_Locale($locale);
        }
    }

    /**
     * Set the location to search in google map
     *
     * @param string $where the location expressed as a sentence (i.e. an address)
     *
     * @return Zle_View_Helper_GoogleMaps provides fluent interface
     */
    public function setLocation($where)
    {
        $this->setOption('q', $where);
        return $this;
    }

    /**
     * Return the helper
     * 
     * @return Zle_View_Helper_GoogleMaps
     */
    public function googleMaps()
    {
        return $this;
    }

    /**
     * Used to encode parameters before render
     *
     * @param string &$value the value to encode
     * @param string $key    key fof option
     *
     * @return void
     */
    protected function encodeValue(&$value, $key)
    {
        // by reference because used with array_walk
        $value = $key . '=' . urlencode($value);
    }

    /**
     * Return an iframe using the provided options for maps parameters
     *
     * @param array $options options to render the map (overwrite
     *                       the individual setOption calls)
     *
     * @return string the html code for the map
     */
    public function render(array $options = array())
    {
        $iframe = '<iframe width="%s" height="%s" frameborder="0" scrolling="no" ' .
                  'marginheight="0" marginwidth="0" src="%s"></iframe>';
        // calculate locale for the maps
        $this->_options['hl'] = $this->getLocale()->getLanguage();
        // build options
        $finalOptions = array_merge(self::$defaultOptions, $this->_options);
        $finalOptions = array_merge($finalOptions, $options);
        // encode options parameters
        array_walk($finalOptions, array($this, 'encodeValue'));
        // build query string
        $qs = implode('&amp;', $finalOptions);
        // url
        $url = self::GOOGLE_MAPS_URL . $qs;
        return sprintf($iframe, $this->getWidth(), $this->getHeight(), $url);
    }

    /**
     * Magic method for using the object with echo
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Set an option for the google maps API
     * 
     * @param string $option the name of the option
     * @param string $value  the value of the option
     *
     * @see http://mapki.com/wiki/Google_Map_Parameters
     * @return Zle_View_Helper_GoogleMaps provides fluent interface
     */
    public function setOption($option, $value)
    {
        $this->_options[$option] = (string)$value;
        return $this;
    }
}
