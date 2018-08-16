<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Form\Validator;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Form\Validator\Password
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Password extends \Zend\Validator\AbstractValidator
{

    const LENGTH = 'length';
    const CHARS = 'chars';
    const NOT_MATCH = 'notMatch';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::LENGTH => 'Password lenght can be minimum 8 characters',
        self::CHARS => 'Only english letters and numbers allowed',
    );

    /**
     * Password constructor.
     *
     * @param null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * Function isValid
     *
     *
     *
     * @param $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        $isValid = true;

        $validator = new \Zend\Validator\StringLength(array('min' => 8, 'max' => 60));
        if (!$validator->isValid($value)) {
            $this->error(self::LENGTH);
            $isValid = false;
        }

        $filter = new \Zend\Filter\PregReplace('/([^0-9  \\\\#\'\**&@,-.:!a-z_\/\n\(\)])*/i', '');
        $string = $filter->filter($value);
        if ($string !== $value) {
            $this->error(self::CHARS);
            $isValid = false;
        }

        return $isValid;
    }

}
