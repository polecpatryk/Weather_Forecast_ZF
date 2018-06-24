<?php

namespace Weather\Model;

use Zend\Http\Client;
use Zend\Http\PhpEnvironment\RemoteAddress;

class WeatherHttp
{
    private $forecastAPI = 'http://api.openweathermap.org/data/2.5/forecast?mode=xml&APPID=9c398cd4cf22ab63cebf65a655f9d64d&lang=pl&units=metric&q=';
	
	public $weather;
	
    public function __construct($city)
    {
		$client = new Client($this->forecastAPI.$city);
		$this->weather = simplexml_load_string($client->send()->getBody());
    }

	public function isWeather()
	{
		if(isset($this->weather->cod))
			return false;
		else
			return true;
	}
	
    public function getData()
    {
		return $this->weather;
    }
	
	public function getSearch()
	{
		$ip = new RemoteAddress();

		return array(
				"city" => (string)$this->weather->location->name,
				"from_date" => str_replace('T', ' ', $this->weather->forecast->time[0]['from']),
				"to_date" => str_replace('T', ' ', $this->weather->forecast->time[count($this->weather->forecast->time)-1]['to']),
				"ip" => ip2long($ip->getIpAddress()),
			);
	}
}