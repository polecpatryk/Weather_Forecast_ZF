<?php

namespace Weather\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Weather implements InputFilterAwareInterface
{
    public $city;
    public $from_date;
    public $to_date;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->city = !empty($data['city']) ? $data['city'] : null;
        $this->from_date = !empty($data['from_date']) ? $data['from_date'] : null;
		$this->to_date = !empty($data['to_date']) ? $data['to_date'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'city',
            'continue_if_empty' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
						'messages' => [
							\Zend\Validator\StringLength::INVALID => 'Niepoprawna nazwa miasta.',
							\Zend\Validator\StringLength::TOO_SHORT => 'Nazwa miasta nie może być pusta.',
							\Zend\Validator\StringLength::TOO_LONG => 'Nazwa miasta jest za długa.'
						]
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
		
        return $this->inputFilter;
    }
}