<?php
namespace DataLogger\RestApp\Chart;
interface ChartResponderInterface
{
   public function update(Response $response, $data);
   public function create(Response $response, ChartEntityInterface $data);
   public function updateSeries(Response $response, $data);
   public function index(Response $response, array $data);
   public function read(Response $response, ChartEntityInterface $data);
}
