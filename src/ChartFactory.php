<?php
/**
* @package DataLogger\RestApp\Chart
* Any valid call to this object will:
* 1. Create chart object based on chart ID or chart type.
* 2. Dynamically enforcing interface.
* 3. Execute method on chart object similar to typical Slim controller: $chart->chartMethod($request, $response, $args).
* 4. Return results.
* Errows will be used by throwing ChartFactoryException.
*/

namespace DataLogger\RestApp\Chart;

class ChartFactory
{
    protected
    //$container,   //Should I be passing the DI container around?  Seems like a bunch of baggage.
    $pdo,
    $accountsId,
    $validationRuleDirectory,
    $validator;

    public function __construct(\Interop\Container\ContainerInterface $container) {
        //$this->container = $container;
        $this->pdo=$container->get('pdo');
        $this->accountsId=$container->get('account')->id;
        $this->validationRuleDirectory=$container->get('validationRuleDirectory');
        $this->validator=$container->get('validator');
    }

    public function __call($name, array $arguments) {
        //Can't use functions in this class since they will be intercepted by __call()!
        syslog(LOG_INFO,"__call name: $name arguements: ".json_encode($arguments));
        $errors=[];
        if($name=='__construct') {
            $errors[]="chart method '$name' is not allowed";
        }
        if(count($arguments)!==3) {
            $errors[]='request, response, and arguments must be passed to constructor';
        }
        else {
            if(!$arguments[0] instanceof \Psr\Http\Message\ServerRequestInterface) {
                $errors[]='first element of third arguement must be \Psr\Http\Message\ServerRequestInterface';
            }
            if(!$arguments[1] instanceof \Psr\Http\Message\ResponseInterface) {
                $errors[]='second element of third arguement must be \Psr\Http\Message\ResponseInterface';
            }
            if(!is_array($arguments[2])) {
                $errors[]='third element of third arguement must be an array';
            }
        }
        if($errors) {
            throw new ChartFactoryException(ucfirst(implode(', ',$errors)).'.');
        }

        if(!empty($arguments[2]['chartId'])) {
            $stmt = $this->pdo->prepare('SELECT id, idPublic, name, config, type, themesId FROM charts WHERE accountsId=? AND idPublic=?');
            $stmt->execute([$this->accountsId, $arguments[2]['chartId']]);
            if(!$properties=$stmt->fetch(\PDO::FETCH_ASSOC)) {
                throw new ChartFactoryException("Invalid chart ID '$id'.");
            }
        }
        elseif(!empty($arguments[2]['chartType'])) {
            $properties['type']=[$arguments[2]['chartType']];
        }
        else {
            throw new ChartFactoryException('Chart ID or type must be specified in parameters.');
        }

        switch($properties['type']) {
            case 'common':
                $seriesModel=new CommonChartSeriesModel($this->pdo, $this->accountsId, $properties);
                $categoriesModel=new CommonChartCategoryModel($this->pdo, $this->accountsId, $properties);
                $chartModel=new CommonChartModel(
                    //$chartBodyModel(),
                    $seriesModel,
                    $categoriesModel,
                    $this->pdo,
                    $this->accountsId,
                    $properties
                );
                $chartService=new CommonChartService(
                    //new CommonChartBody(),
                    //new CommonChartSeries(),
                    //new CommonChartCategory(),
                    $chartModel,
                    $this->validator,
                    $this->validationRuleDirectory,   //Include extention here or elsewhere?  i.e. common_chart.json,
                    $this->pdo
                    //$this->container
                );
                $chartResponder = new CommonChartResponder();
                $chartController = new CommonChartController(
                    $chartService,
                    $chartResponder
                    //$this->container
                );
                break;
            case 'pie':
                //Similar as common chart
                break;
            case 'gauge':
                //Similar as common chart
                break;
            case 'time':
                //Similar as common chart
                break;
            default: throw new ChartFactoryException("Invalid chart type '$type'.");
        }

        if(!method_exists($chartController, $name)) {
            throw new ChartFactoryException("Chart method '$name' is not supported.");
        }
        // following will return $chart->chartMethod($request, $response, $args) and will throw exceptions if applicable
        return $chartController->$name($arguments[0], $arguments[1], $arguments[2]);   //Can this be done using something like extract or list?
    }
}