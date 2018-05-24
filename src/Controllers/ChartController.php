<?php
namespace DataLogger\RestApp\Chart;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ChartController
{
    protected
    //$container,   //Should I be passing the DI container around?  Seems like a bunch of baggage.
    $chartService,
    $chartResponder;

    public function __construct($chartService, $chartResponder /*,$container*/) {
        $this->chartService = $chartService;
        $this->chartResponder = $chartResponder;
        //$this->container = $container;
    }

    public function update(Request $request, Response $response, array $args) {
        return $this->responder->update($response, $this->chartService->update($request->getParsedBody()));
    }

    public function updateSeries(Request $request, Response $response, array $args) {
        return $this->responder->updateSeries($response, $this->chartService->update($args['seriesId'], $request->getParsedBody()));
    }

    public function index(Request $request, Response $response, array $args) {
        return [];  //Where should this method go since it is not tied to a specific record?
    }

    public function read(Request $request, Response $response, array $args) {
        $data=$this->chartService->read();
        return $this->chartResponder->read($response, $data);
    }

    // Other methods as necessary

}