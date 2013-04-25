<?php


class SensorarioUrlRouteModule extends CWebModule
{

  public function init()
  {
    $this->setImport(array(
      'sensorariourlroute.components.*',
    ));
  }

  public function beforeControllerAction($controller, $action)
  {
    if (parent::beforeControllerAction($controller, $action)) {
      return true;
    }else{
      return false;
    }
  }

}
