<?php

namespace ZgApp\Service;

use Zend\Authentication\Storage\Session;
use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Service\Mpdf
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Mpdf implements \Zend\ServiceManager\Factory\FactoryInterface
{
    protected $version = '573';
    protected $mpdf;

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $vendorDir = dirname(dirname(dirname(dirname(__FILE__)))) . "/vendor/";
        require_once $vendorDir . '/MPDF' . $this->version . '/mpdf.php';
        return $this;
    }

    /**
     * Function create
     *
     *
     *
     * @param $html
     * @param $options
     * @return $this
     */
    public function create($html, $options)
    {
        $title = (isset($options['title'])) ? $options['title'] : 'ZgApp PDF Sample File';
        $author = (isset($options['author'])) ? $options['author'] : 'ZGtec Inc';
        $password = (isset($options['password'])) ? $options['password'] : false;
        $permissions = (isset($options['permissions'])) ? $options['permissions'] : array();

        $mode = (isset($options['mode'])) ? $options['mode'] : '';
        $format = (isset($options['format'])) ? $options['format'] : '';
        $fontSize = (isset($options['fontSize'])) ? $options['fontSize'] : 14;
        $font = (isset($options['font'])) ? $options['font'] : '';
        $marginLeft = (isset($options['marginLeft'])) ? $options['marginLeft'] : 15;
        $marginRight = (isset($options['marginRight'])) ? $options['marginRight'] : 15;
        $marginTop = (isset($options['marginTop'])) ? $options['marginTop'] : 16;
        $marginBottom = (isset($options['marginBottom'])) ? $options['marginBottom'] : 16;
        $marginHeader = (isset($options['marginHeader'])) ? $options['marginHeader'] : 9;
        $marginFooter = (isset($options['marginFooter'])) ? $options['marginFooter'] : 9;
        $orientation = (isset($options['orientation'])) ? $options['orientation'] : 'P';


        $mpdf = new \mPDF($mode, $format, $fontSize, $font, $marginLeft, $marginRight, $marginTop, $marginBottom, $marginHeader, $marginFooter, $orientation);
        $mpdf->SetTitle($title);
        $mpdf->SetSubject($title);
        $mpdf->SetAuthor($author);
        $mpdf->SetCreator($author);
        if ($password) {
            $mpdf->SetProtection($permissions, $password, $password, 128);
        }

        $mpdf->WriteHTML($html);
        $this->mpdf = $mpdf;
        return $this;
    }

    /**
     * Function saveToFile
     *
     *
     *
     * @param $filename
     * @return bool
     */
    public function saveToFile($filename)
    {
        if ($this->mpdf) {
            $this->mpdf->Output($filename, 'F');
            return true;
        }
        return false;
    }

    /**
     * Function downloadFile
     *
     *
     *
     * @param $filename
     * @return bool
     */
    public function downloadFile($filename)
    {
        if ($this->mpdf) {
            return $this->mpdf->Output($filename, 'D');
        }
        return false;
    }

    /**
     * Function getString
     *
     *
     *
     * @return bool
     */
    public function getString()
    {
        if ($this->mpdf) {
            return $this->mpdf->Output($filename, 'S');
        }
        return false;
    }


}
