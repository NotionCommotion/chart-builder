<?php
namespace DataLogger\RestApp\Chart;

class ChartService
{
    protected $model,
    $validator,
    $validationRuleDirectory,     //JSON validation rules (used by Validator)
    //$container,
    $pdo;	//Required for transactions only

    public function __construct($model, $validator, $validationRuleDirectory, $pdo) {
        $this->model = $model;
        $this->validator = $validator;
        $this->validationRuleDirectory = $validationRuleDirectory;
        $this->pdo = $pdo;
        //$this->container = $container;
    }

    public function update(array $data) {
        //Validate data first
        return $this->model->update();
    }

    public function create(array $data) {
        //Validate data first
        return $this->model->wrapTransation(function($response) {
            return $this->model->create();
        });
    }

    public function updateSeries(int $seriesId, array $data) {
        //Validate data first
        return $this->model->seriesModel->update($seriesId, $data);
    }

    public function index(array $filter) {
        return [];
    }

    public function read() {
        return $this->model->read();
    }

    final protected function wrapTransation($f){
        //Can't wrap a transaction in the model because the model doesn't know if the service uses multiple models.
        try {
            $this->pdo->beginTransaction();
            $f();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw($e);
        }
    }

}