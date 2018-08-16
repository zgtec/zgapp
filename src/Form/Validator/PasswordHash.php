<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Validates if user tries use password he used before
 *
 */

namespace ZgApp\Form\Validator;

/**
 *
 * Class ZgApp\Form\Validator\PasswordHash
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class PasswordHash extends \Zend\Validator\AbstractValidator
{
    const LOCKED = 'locked';

    protected $messageTemplates;
    protected $options;
    protected $controller;
    protected $email;

    /**
     * PasswordHash constructor.
     *
     * @param $options
     */
    public function __construct($options)
    {
        $this->options = $options;
        $this->controller = $options['controller'];
        $this->messageTemplates[self::LOCKED] = "Sorry, but you can not use same password as one was used last " . $this->controller->authAcl()->getHashesNum() . " times";
        parent::__construct(is_array($options) ? $options : null);
    }


    /**
     * Function isValid
     *
     *
     *
     * @param $value
     * @param array|null $context
     * @return bool
     */
    public function isValid($value, array $context = null)
    {
        $this->setValue($value);
        $options = $this->options;

        if (is_array($options)) {
            while (is_array($options)) {
                $key = $options['token'];
                if (!isset($context[$key])) {
                    break;
                }
                $context = $context[$key];
            }
        }

        if (is_array($context) || !strlen($context)) {
            return true;
        } elseif (strlen($context)) {
            $user = $this->controller->db()->model("\ZgApp\Model\Db\User")->find(array($options['token'] => $context));
            if ($user && $this->controller->authAcl()->checkHashes($user, $value)) {
                return true;
            }
        }

        $this->error(self::LOCKED);
        return false;

        return false;
    }

}