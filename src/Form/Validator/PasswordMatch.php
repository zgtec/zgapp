<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Form\Validator;

/**
 *
 * Class ZgApp\Form\Validator\PasswordMatch
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class PasswordMatch extends \Zend\Validator\Identical
{
    protected $messageTemplates = array(
        self::NOT_SAME => "The two entered passwords do not match",
        self::MISSING_TOKEN => 'No token was provided to match against',
    );

}