<?php

namespace ZgApp\Form\Validator;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
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