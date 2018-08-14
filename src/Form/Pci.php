<?php

namespace ZgApp\Form;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Form\Pci
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Pci extends \Zend\Form\Form
{

    protected $intkeys = array();
    protected $controller;
    protected $nameid;

    /**
     * Pci constructor.
     *
     * @param null $controller
     */
    public function __construct($controller = null)
    {
        parent::__construct();
        $this->controller = $controller;
        $this->setAttribute('method', 'post');
        $this->resetInputFilter();
        return $this;
    }


    public function resetInputFilter()
    {
        $inputFilter = str_replace('Form\\', 'Form\InputFilter\\', get_class($this));
        $this->setInputFilter(new $inputFilter($this->controller));
    }


    /**
     * Function create
     *
     *
     *
     * @param $options
     * @return $this
     */
    public function create($options)
    {
        $nameid = $options['id'];
        $this->nameid = $nameid;

        $this->add(array('name' => 'formtype', 'attributes' => array('type' => 'hidden', 'value' => $nameid, 'id' => 'formtype',)));

        $this->setName($nameid);
        $this->setAttribute('id', $nameid);
        $this->setAttribute('accept-charset', "utf-8");

        if (isset($options['onsubmit']))
            $this->setAttribute('onsubmit', $options['onsubmit']);

        if (isset($options['onload']))
            $this->setAttribute('onload', $options['onload']);

        if (isset($options['label'])) {
            $this->add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => $options['label'], 'id' => 'submitbutton',),));
        }

        if ($nameid !== 'ajax') {
            $this->add(array('name' => 'csrf' . $nameid, 'type' => 'Zend\Form\Element\Csrf', 'options' => array('csrf_options' => array('timeout' => 3600))));
            $csrf_validators = $this->getInputFilter()->get('csrf' . $nameid)->getValidatorChain()->getValidators();
            $csrf = $csrf_validators[0]['instance'];

            $csrf->setMessage(
                'Your security token expired due to inactivity. Please try again', \Zend\Validator\Csrf::NOT_SAME
            );
        }

        $this->formElements();
        $this->setAriaLabelBy();
        return $this;
    }

    public function setAriaLabelBy()
    {
        foreach ($this->getElements() as $key => $element) {
            if (!strlen($element->getAttribute('aria-labelled-by')) && $element->getAttribute('type') != 'hidden') {
                $element->setAttribute('aria-labelled-by', $key . "_label");
            }
        }
    }


    public function removeCsrf()
    {
        $this->remove('csrf' . $this->nameid);
        $this->getInputFilter()->remove('csrf' . $this->nameid);
    }


    /**
     * Function formElements
     *
     *
     *
     * @return bool
     */
    protected function formElements()
    {
        return false;
    }

    /**
     * Function isValid
     *
     *
     *
     * @return bool
     */
    public function isValid()
    {
        $ret = parent::isValid();

        $data = $this->getData();
        $translator = new \ZgApp\Filter\Format();
        $filter = new \Zend\Filter\PregReplace('/([^0-9 \\\\#\'\**&@$,-.,:+;?!a-z_\/\n\(\)])*/i', '');
        $errchar = 0;
        foreach ($this->getElements() as $key => $element) {
            if (!isset($data[$key]) || is_array($data[$key]) || $element->getAttribute('skipfilter') == 'true')
                continue;
            $translated = $translator->filter($data[$key]);
            if ($translated !== $filter->filter($translated)) {
                $errchar++;
                $element->setAttribute('style', 'background-color:#FFCCCC');
                $this->intkeys[] = $key;
                if ($errchar == 1)
                    $element->setMessages(array("Please use only numbers, English letters and the following characters: # & - + $ .,?! _ \ /"));
                $ret = false;
            } else {
                $element->setValue($translated);
            }
        }
        return $ret;
    }

    /**
     * Function populateValues
     *
     *
     *
     * @param $values
     */
    public function populateValues($values)
    {
        parent::populateValues($values);
        foreach ($this->getElements() as $key => $element) {
            if (in_array($key, $this->intkeys)) {
                $element->setValue('');
                $element->setMessages(array());
            }
        }
        $this->get('formtype')->setValue($this->getName());
    }

    /**
     * Function populate
     *
     *
     *
     * @param array $vaues
     */
    public function populate(array $vaues)
    {
        return $this->populateValues($vaues);
    }

    /**
     * Function secureQuestionsList
     *
     *
     *
     * @return bool
     */
    public function secureQuestionsList()
    {
        $questions = array(
            "" => "Please select a Secret Question",
            "BORN" => "City Where I was Born",
            "MOTHER" => "Mother's Maiden Name",
            "PET" => "Favorite Pet's Name"
        );

        $this->add(array('name' => 'question', 'type' => 'Zend\Form\Element\Select', 'options' => array('label' => 'Security Question:'),));
        $this->get('question')->setValueOptions($questions);
        return true;
    }

    /**
     * Function CountriesList
     *
     *
     *
     * @param string $name
     * @return bool
     */
    public function CountriesList($name = "country")
    {
        $locale = new \ZgApp\Model\Locale();
        $countries = $locale->countriesList();
        array_pop($countries);
        asort($countries);
        $this->add(array('name' => $name, 'type' => 'Zend\Form\Element\Select', 'options' => array('label' => 'Country:'), 'attributes' => array('id' => $name, 'onchange' => 'updateStates("' . $this->getAttribute('id') . '");')));
        $this->get($name)->setValueOptions($countries);
        return true;
    }

    /**
     * Function StatesList
     *
     *
     *
     * @param $countryId
     * @param bool $value
     * @param string $name
     * @return bool
     */
    public function StatesList($countryId, $value = false, $name = "state")
    {
        if (!$this->controller)
            return false;
        $states = $this->controller->stateslist()->getStates($countryId);

        if ($this->getName() === 'ajax')
            $add = '_ajax';
        else
            $add = '';


        if ($this->has('state'))
            $this->remove('state');

        if (count($states) < 2) {
            $this->add(array('name' => $name, 'attributes' => array('type' => 'text', 'id' => $name, 'aria-labelled-by' => 'state_label'), 'options' => array('label' => 'State:')));
        } else {
            //array_shift($states);
            $this->add(array('name' => $name, 'type' => 'Zend\Form\Element\Select', 'options' => array('label' => 'State:'), 'attributes' => array('id' => 'state', 'aria-labelled-by' => 'state_label')));
            $this->get($name)->setValueOptions($states);
        }

        //$this->resetInputFilter();

        if ($value) {
            $this->get($name)->setValue($value);
        }

        return true;
    }

    /**
     * Function selectOptionsDigits
     *
     *
     *
     * @param $from
     * @param $to
     * @return array
     */
    public function selectOptionsDigits($from, $to)
    {
        $options = array();
        for ($i = $from; $i <= $to; $i++) {
            $options[$i] = $i;
        }
        return $options;
    }


}
