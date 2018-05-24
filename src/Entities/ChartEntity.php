<?php
namespace DataLogger\RestApp\Chart;
abstract class ChartEntity  // extends Entity
{
    protected $config,
    $theme,
    $typeName,
    $masterType,
    $configDefault;

    protected $series=[],
    $categories=[]; //Gauges by definition do not have categories.  Time chart categories are dynamically created via time

}
