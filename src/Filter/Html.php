<?php

/**
 * Html Filter
 * Uses HTMLPurifier Library
 * Removes Tags and Tags attributes for allowed tags.
 *
 */

namespace ZgApp\Filter;

use Zend\Filter\AbstractFilter;
use ZgApp\Filter\HTMLPurifier;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Filter\Html
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Html extends AbstractFilter
{
    protected $purifier;

    public function __construct()
    {
        $vendorDir = dirname(dirname(dirname(dirname(__FILE__)))) . "/vendor/";
        require_once $vendorDir . '/HTMLPurifier/HTMLPurifier.auto.php';
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
        $config->set('HTML.AllowedElements', array('h1', 'h2', 'h3', 'h4', 'h5', 'br', 'td', 'tr', 'table', 'b', 'strong', 'p', 'img', 'a', 'div', 'i', 'ul', 'li', 'ol', 'dl', 'span'));
        $config->set('HTML.AllowedAttributes', 'id,class,name,style,colspan,width,align,a.href,a.target,img.src,img.alt,img.title');
        $config->set('HTML.Nofollow', true);
        $config->set('Attr.EnableID', true);
        $config->set('Attr.AllowedFrameTargets', array('_blank'));
        $this->purifier = new \HTMLPurifier($config);
    }

    /**
     * Function filter
     *
     *
     *
     * @param $value
     * @return mixed|string
     */
    public function filter($value)
    {
        $string = $this->purifier->purify($value);
        $string = str_replace("&amp;", "&", $string);
        return $string;
    }

    /**
     * Function recursive
     *
     *
     *
     * @param $value
     */
    static function recursive(&$value)
    {
        $filter = new \ZgApp\Filter\Html();
        $value = $filter->filter($value);
        // Forcing replacement of divs by p attributes
        $value = str_replace("<div", "<p", $value);
        $value = str_replace("/div>", "/p>", $value);
    }

}