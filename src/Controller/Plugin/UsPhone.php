<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * UsPhone Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\UsPhone
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class UsPhone extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $phone
     * @return string
     */
    public function __invoke($phone)
    {
        $filter = new \Zend\Filter\Digits();
        $phone = $filter->filter($phone);
        $len = strlen($phone);
        if ($len == 10) {
            $phone = '+1(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
        } elseif ($len == 11 && substr($phone, 0, 1) == "1") {
            $phone = '+1(' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7);
        }
        return $phone;
    }

}