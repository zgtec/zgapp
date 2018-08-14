<?php

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Service\LogEvent
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class LogEvent implements \Zend\ServiceManager\Factory\FactoryInterface
{

    public $format = '%timestamp%; %priorityName%; %priority%; %message%';
    protected $controller;
    protected $logger;
    protected $ccvalidator;

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
        $this->logger = new \ZgApp\Model\LogEventFile();

        if (!is_dir($config['logEvent']['save_path'])) {
            umask(0);
            mkdir($config['logEvent']['save_path'], 0777, true);
        }

        $this->logger->addWriterPath($config['logEvent']['save_path'] . "events-" . date("Y-m") . ".log");
        $this->logger->addWriterPath($config['logEvent']['save_path'] . "critical-" . date("Y-m") . ".log", 3);
        $this->logger->addWriterPath($config['logEvent']['save_path'] . "fails-" . date("Y-m") . ".log", 2);
        $this->logger->addWriterPath($config['logEvent']['save_path'] . "fails-details-" . date("Y-m") . ".log", 1);

        // Email Writer
        $mail = new \ZgApp\Model\Mail($config['mailer']);
        $mail->addTo($config['logEvent']['mail_to']);
        $writer = new \Zend\Log\Writer\Mail($mail->getMessage());
        $writer->setSubjectPrependText($_SERVER['HTTP_HOST'] . ' - Critical Logs Mailer');
        $writer->addFilter($config['logEvent']['mail_priority']);
        if (strlen($config['logEvent']['mail_regex']) && $config['logEvent']['mail_regex']) {
            $writer->addFilter(new \Zend\Log\Filter\Regex($config['logEvent']['mail_regex']));
        }
        $this->logger->addWriter($writer);

        $this->ccvalidator = new \Zend\Validator\CreditCard();

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
        $this->controller = $controller;
        return $this;
    }

    /**
     * Function logEvent
     *
     *
     *
     * @param $event
     * @param $status
     * @param int $priority
     * @param bool $username
     * @param bool $userid
     * @param bool $code
     * @param bool $detailed
     */
    public function logEvent($event, $status, $priority = 3, $username = false, $userid = false, $code = false, $detailed = false)
    {
        $property = $this->controller->property;

        if (!$code && isset($this->controller->domain)) {
            $code = $this->controller->domain;
        }

        $filter = new \ZgApp\Filter\HideCard();
        $username = $filter->filter($username);

        $this->logger->log($priority, "$event; $status; $username; $userid; " . $_SERVER['HTTP_HOST'] . "; " . $_SERVER['REMOTE_ADDR'] . "; " . $code . "; $detailed;");
    }


}
