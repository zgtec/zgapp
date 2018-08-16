<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Validates if user locked for Authorization
 *
 */

namespace ZgApp\Form\Validator;

/**
 *
 * Class ZgApp\Form\Validator\UserLocked
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class UserLocked extends \Zend\Validator\AbstractValidator
{
    const LOCKED = 'locked';

    protected $messageTemplates;
    protected $options;
    protected $controller;
    protected $column;

    /**
     * UserLocked constructor.
     *
     * @param $options
     */
    public function __construct($options)
    {
        $this->options = $options;
        $this->controller = $options['controller'];
        $this->messageTemplates[self::LOCKED] = "Your account was locked for " . ceil(($this->controller->authAcl()->getLockTime()) / 60) . " minutes after " . $this->controller->authAcl()->getLockCount() . " incorrect login attempts. You can try again later.";
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
        $user = $this->controller->db()->model('\ZgApp\Model\Db\User')->find(array('email' => $this->getValue()));
        if ($user && $user->lockcount >= $this->controller->authAcl()->getLockCount()) {
            $locktime = time() - $user->locktime;
            if ($locktime <= $this->controller->authAcl()->getLockTime()) {
                $this->error(self::LOCKED);
                return false;
            } else {
                $data = array('id' => $user->id, 'locktime' => 0, 'lockcount' => 0);
                $this->controller->db()->model('\ZgApp\Model\Db\User')->save($data);
            }
        }
        return true;
    }
}