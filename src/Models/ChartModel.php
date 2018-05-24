<?php
namespace DataLogger\RestApp\Chart;

abstract class ChartModel extends Model
{
    //public function __construct() {} //Implement in child class as only some charts have categories
    private $requireTransaction=['addSeries','create'];

}