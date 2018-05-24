<?php
namespace DataLogger\RestApp\Chart;
abstract class CategorizedChartEntity extends ChartEntity
{
    //Common and pie charts only.  Gauges by definition do not have categories.  Time chart categories are dynamically created via time

    protected $categories=[];
    public function __construct(Collection $series, Collection $categories, array $data) {
        $this->series=$series;
        $this->categories=$categories;
        $this->data=$data;
    }
}
