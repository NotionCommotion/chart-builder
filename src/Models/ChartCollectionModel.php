<?php
namespace DataLogger\RestApp\Chart;

class ChartCollectionModel extends Model
{
    protected $pdo, $accountsId, $properties;

    public function __construct(\Pdo $pdo, int $accountsId, array $properties) {
        $this->pdo=$pdo;
        $this->accountsId=$accountsId;
        $this->properties=$properties;
    }
    //specialized methods as applicable
}