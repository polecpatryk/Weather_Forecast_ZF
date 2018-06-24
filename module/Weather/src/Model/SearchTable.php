<?php

namespace Weather\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;


class SearchTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getSearch()
    {
        return	$this->tableGateway->select(function(Select $select){
        $select
		->order('id DESC')
        	->limit(5);
    });
    }

    public function saveSearch($weather)
    {
        $data = [
            'ip'        => $weather['ip'],
            'city'      => $weather['city'],
            'from_date' => $weather['from_date'],
            'to_date'   => $weather['to_date'],
        ];

        $this->tableGateway->insert($data);
    }
}
