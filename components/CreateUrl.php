<?php

/**
 * @author Simone Gentili <sensorario@gmail.com>
 */
class CreateUrl
{

    private $routes;
    private $name;

    public function getRoute()
    {
        $route = isset($this->routes[$this->name]) ?
                $this->routes[$this->name] :
                null;
        return Yii::app()->createUrl($route);
    }

    public function __construct($name)
    {
        $this->routes = include __DIR__ . '/../routing.php';
        $this->name = $name;
        return $this;
    }

}