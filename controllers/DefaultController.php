<?php


class DefaultController extends Controller
{

  private function createRoutingFile()
  {
    $handle = fopen($this->getRoutingFileName(), "w+");
    fwrite($handle, "<?php return array();");
    fclose($handle);
  }

  public function actionDelete()
  {
    $this->createRoutingFile();
    $this->redirect($this->createUrl('index'));
  }

  public function actionGenerate()
  {

    $controllerFolder = Yii::app()->basePath . "/controllers";
    $dirHandle = opendir($controllerFolder);
    $routes = [];
    while($controllerName = readdir($dirHandle)){
      if (!in_array($controllerName, ['.', '..'])) {
        require_once __DIR__ . '/../../../../protected/controllers/' . $controllerName;
        $metodiDelController = (new ReflectionClass(explode('.', $controllerName)[0]))->getMethods();
        foreach($metodiDelController as $reflectionMethod){
          if (strpos($reflectionMethod->name, "action") === 0) {
            if (!($reflectionMethod->name === "actions")) {
              $commento = (new ReflectionMethod($reflectionMethod->class, $reflectionMethod->name))->getDocComment();
              foreach(explode("\n", $commento) as $item){
                if (strpos($item, "@")) {
                  preg_match_all("/\@Route\(value=\"(.*)\"\);/", $item, $matches);
                  if (isset($matches[1][0])) {
                    $class = $reflectionMethod->class;
                    $explodedControllerName = strtolower(explode("Controller", $class)[0]);
                    $explodedAction = strtolower(explode("action", $reflectionMethod->name)[1]);
                    $routes[$matches[1][0]] = "{$explodedControllerName}/{$explodedAction}";
                  }
                }
              }
            }
          }
        }
      }
    }

    $file = "<?php return array(\n";
    foreach($routes as $action => $rotta){
      $file .= "\t'{$action}'=>'{$rotta}',\n";
    }
    $file .= ");";

    $handle = fopen($this->getRoutingFileName(), "w+");
    fwrite($handle, $file);
    fclose($handle);

    $this->redirect($this->createUrl('index'));
  }

  private function getRoutingFileName()
  {
    return __DIR__ . '/../../../../protected/config/routing.php';
  }

  public function actionIndex()
  {
    $filename = $this->getRoutingFileName();

    if (!file_exists($filename)) {
      $this->createRoutingFile();
    }

    $this->render('index', array(
      'routes' => file_get_contents($filename)
    ));
  }

}

