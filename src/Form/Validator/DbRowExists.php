<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Validates for unique value added to the DB
 *
 */

namespace ZgApp\Form\Validator;

/**
 *
 * Class ZgApp\Form\Validator\DbRowExists
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class DbRowExists extends \Zend\Validator\AbstractValidator
{
    const EXISTS = 'exists';

    protected $messageTemplates = array(
        self::EXISTS => "This element already used",
    );

    protected $options;
    protected $controller;
    protected $table;
    protected $column;

    /**
     * DbRowExists constructor.
     *
     * @param $options
     */
    public function __construct($options)
    {
        $this->options = $options;
        $this->table = $options['table'];
        $this->column = $options['column'];
        $this->controller = $options['controller'];
        $this->messageTemplates = array(
            self::EXISTS => $options['message'],
        );

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

        if (is_array($context) || (int)$context === 0) {
            // New
            $exists = $this->controller->db()->model($this->table)->find(array($this->column => $this->getValue()));
        } elseif ($context > 0) {
            // Update
            $exists = $this->controller->db()->model($this->table)->find(array($this->column => $this->getValue(), 'id !=?' => $context));
        }

        if ($exists) {
            $this->error(self::EXISTS);
            return false;
        }

        return true;
    }
}