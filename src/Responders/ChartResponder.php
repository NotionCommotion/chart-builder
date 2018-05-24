<?php
namespace DataLogger\RestApp\Chart;
use \Psr\Http\Message\ResponseInterface as Response;

class ChartResponder
{
    //Why use a responder?

    //protected $container;
    //public function __construct($container) {$this->container = $container;}

    public function update(Response $response, $data) {
        return $response->withJson($data);
    }

    public function create(Response $response, ChartEntityInterface $data) {
        return $response->withJson($data);
    }

    public function updateSeries(Response $response, $data) {
        return $response->withJson($data);
    }

    public function index(Response $response, array $data) {
        return $response->withJson($data);
    }

    public function read(Response $response, ChartEntityInterface $data) {
        return $response->withJson($data);
    }
}