<?php
namespace DataLogger\RestApp\Chart;
class Collection  // extends Iterator?
{
    protected $stack=[];
    private $originalStack;

    /*
    protected $nodeClass; //Fully qualified class name.
    public function __construct($nodeClass, array $nodes=[]) { //$nodes is a normal array
    $this->nodeClass=$nodeClass;
    foreach($nodes as $node) $this->stack[]=new $nodeClass($node);
    }
    */

    public function __construct(array $nodes=[]) { //$nodes is an array of Node objects
        $this->stack=$nodes;
        $this->originalStack=array_merge(array(), $nodes);    //Arrays not passed by reference but the objects are!
    }

    public function getChanges(){
        $this->stack;
    }

    /**
    * Returns an array who's index is the position array given unique
    *
    * @param string $prop
    * @return array
    */
    public function getPositionChanges($prop){
        return array_udiff_assoc($this->stack, $this->originalStack,
            function ($stack, $originalStack) {
                return is_numeric($stack->$prop) && is_numeric($originalStack->$prop)
                ?$stack->$prop - $originalStack->$prop
                :strcmp($stack->$prop, $originalStack->$prop);
            }
        );
    }

    public function getAll(){
        $this->stack;
    }

    public function getByPosition(int $position){
        $this->stack[$position];
    }

    public function getPropertyByPosition(int $position, $property){
        return $this->stack[$position]->$property;  //Uses __get
    }

    public function add(Node $elem){
        $this->stack[]=$elem;
    }

    public function delete(int $position){
        if(!isset($this->stack[$position])) throw new CollectionException('Index does not exist');
        unset($this->stack[$position]);
        $this->stack=$stack[]=array_values($this->stack);
    }

    public function move(int $initialPosition, int $finalPosition){
        if(!isset($this->stack[$initialPosition]) || !isset($this->stack[$finalPosition])) throw new CollectionException('Index does not exist');
        $node=$this->stack[$initialPosition];
        unset($this->stack[$initialPosition]);
        array_splice($this->stack, $finalPosition, 0, $node);
        $this->stack=array_values($this->stack);
        //?? return $this->stack;
    }

    public function update(int $position, $property, $value){
        if(!isset($this->stack[$position])) throw new CollectionException('Index does not exist');
        $this->stack[$position]->$property=$value;  //Uses __set
    }
}
