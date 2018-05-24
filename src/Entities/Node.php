<?php
namespace DataLogger\RestApp\Chart;
abstract class Node
{
    //protected $someProperty;  //Add properties as applicable.

    public function __construct(array $node, array $keys=[]) {
        if($keys) {
            foreach($keys as $key) {
                if (isset($node[$key])) throw new NodeException("Node element for '$key' not provided");
                else $this->__set($property, $value);
            }
        }
        else {
            foreach($node as $property => $value) {
                $this->__set($property, $value);
            }
        }
    }

    public function __get($property) {
        if (property_exists($this, $property)) return $this->$property;
        else throw new NodeException("Node property '$property' does not exist");
    }

    public function __set($property, $value) {
        if (!property_exists($this, $property)) throw new NodeException("Node property '$property' is not allowed");
        $this->$property = $value;
        return $this;
    }
}
