<?php

namespace Weather\Controller;

use Weather\Form\WeatherForm;
use Weather\Model\Weather;
use Weather\Model\SearchTable;
use Weather\Model\WeatherHttp;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WeatherController extends AbstractActionController
{
	private $form;
	private $table;
	
    public function __construct(SearchTable $table)
    {
		$this->form = new WeatherForm();
        $this->table = $table;
    }
	
    public function indexAction()
    {		
		return new ViewModel([
			'form' => $this->form,
            'searchs' => $this->table->getSearch(),
        ]);
    }

    public function searchAction()
    {				
        $request = $this->getRequest();

        if(!$request->isPost()) {
            return $this->redirect()->toRoute('weather');
        }
		
		$weather = new Weather();
        $this->form->setInputFilter($weather->getInputFilter());
        $this->form->setData($request->getPost());

        if(!$this->form->isValid()) {
            return new ViewModel([
				'isCity' =>true,
				'isWeather' => false,
				'form' => $this->form,
				'searchs' => $this->table->getSearch(),
			]);
        }
				
		$weather_http = new WeatherHttp($this->form->getData()['city']);

		if(!$weather_http->isWeather()) {
			return new ViewModel([
				'isCity' =>false,
				'isWeather' => false,
				'form' => $this->form,
				'searchs' => $this->table->getSearch(),
			]);
		}
				
		$this->table->saveSearch($weather_http->getSearch());
		
        return new ViewModel([
				'isCity' =>true,
				'isWeather' => true,
				'form' => $this->form,
				'forecast' => $weather_http->getData(),
			]);
    }
}