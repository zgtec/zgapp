<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Form\Validator;

/**
 *
 * Class ZgApp\Form\Validator\Email
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Email extends \Zend\Validator\EmailAddress
{
    protected $messageTemplates = array(
        self::INVALID => "Please enter a valid email address.",
        self::INVALID_FORMAT => "Please enter a valid email address.",
        self::INVALID_HOSTNAME => "Please enter a valid email address.",
        self::INVALID_MX_RECORD => "Please enter a valid email address.",
        self::INVALID_SEGMENT => "Please enter a valid email address.",
        self::DOT_ATOM => "Please enter a valid email address.",
        self::QUOTED_STRING => "Please enter a valid email address.",
        self::INVALID_LOCAL_PART => "Please enter a valid email address.",
        self::LENGTH_EXCEEDED => "Please enter a valid email address.",
    );

}