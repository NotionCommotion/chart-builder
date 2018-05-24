<?php
namespace DataLogger\RestApp\Chart;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
interface ChartControllerInterface
{
    public function update(Request $request, Response $response, array $args);
    public function updateSeries(Request $request, Response $response, array $args);
    public function index(Request $request, Response $response, array $args);
    public function read(Request $request, Response $response, array $args);
    /*
    public function get(int $id): ?Chart;
    public function create(array $data): Chart;
    public function update(array $data, int $id): Chart;
    public function delete(int $id): void;

    public function getConfig($reset);
    public function clone($name): Chart;
    //public function addPoint(array $params);

    public function updateSeries($seriesPosition, array $params);
    public function updateSeriesPositions(array $params);
    public function deleteSeries($seriesPosition);
    public function addSeries(array $params);
    */
}
