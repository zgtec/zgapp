<?php

namespace ZgApp\Model;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\Mail
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Mail
{
    protected $_message;
    protected $_transport;
    protected $_variables;
    protected $_controller;

    /**
     * Mail constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->_message = new \Zend\Mail\Message();
        //$this->_message->setEncoding('UTF-8');
        $this->_message->setFrom($config['from_email'], $config['from_name']);

        if ($config['smtp'] === true) {
            $transport = new \Zend\Mail\Transport\Smtp();
            $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['smtp_config']));
        } else {
            $transport = new \Zend\Mail\Transport\Sendmail();
        }

        $this->_transport = $transport;
        $this->_variables = $config;

        return $this;
    }

    /**
     * Function setController
     *
     *
     *
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
        return $this;
    }

    /**
     * Function setBodyHtml
     *
     *
     *
     * @param $html
     * @return $this
     */
    public function setBodyHtml($html)
    {
        $bodyPart = new \Zend\Mime\Message();
        $bodyMessage = new \Zend\Mime\Part($html);
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        $this->_message->setBody($bodyPart);
        return $this;
    }

    public function sendEmail()
    {
        $this->_transport->send($this->_message);
    }

    /**
     * Function setBodyView
     *
     *
     *
     * @param $view
     * @param $renderer
     * @return $this
     */
    public function setBodyView($view, $renderer)
    {
        foreach ($this->_variables as $key => $val) {
            $view->setVariable($key, $val);
        }
        $this->setBodyHtml($renderer->render($view));
        return $this;
    }


    /**
     * Function setTemplate
     *
     *
     *
     * @param $template
     * @param $config
     * @return $this
     */
    public function setTemplate($template, $config)
    {

        $view = $this->_controller->getView()->setTemplate($template);
        foreach ($config as $key => $val) {
            $view->setVariable($key, $val);
        }

        $this->setBodyView($view, $this->_controller->service('ViewPhpRenderer'));
        return $this;
    }

    /**
     * Function setSubject
     *
     *
     *
     * @param $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->_message->setSubject($subject);
        return $this;
    }

    /**
     * Function addTo
     *
     *
     *
     * @param $email
     * @param null $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $this->_message->addTo($email, $name);
        return $this;
    }

    /**
     * Function setTo
     *
     *
     *
     * @param $email
     * @return $this
     */
    public function setTo($email)
    {
        $this->_message->setTo($email);
        return $this;
    }

    /**
     * Function setReplyTo
     *
     *
     *
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setReplyTo($email, $name = null)
    {
        $this->_message->setReplyTo($email, $name);
        return $this;
    }

    /**
     * Function setFrom
     *
     *
     *
     * @param $email
     * @param null $name
     * @return $this
     */
    public function setFrom($email, $name = null)
    {
        $this->_message->setFrom($email, $name);
        return $this;
    }

    /**
     * Function getMessage
     *
     *
     *
     * @return \Zend\Mail\Message
     */
    public function getMessage()
    {
        return $this->_message;
    }


}
