<?php

class DefaultController extends Controller
{

    public function actionDelete()
    {
        $handle = fopen(__DIR__ . '/../routing.php', "w+");
        fwrite($handle, "<?php return array();");
        fclose($handle);

        $this->redirect($this->createUrl('index'));
    }

    public function actionGenerate()
    {
        $controllerFolder = Yii::app()->basePath . "/controllers";
        $dirHandle = opendir($controllerFolder);
        $routes = [];
        while ($controllerName = readdir($dirHandle)) {
            if(!in_array($controllerName, ['.', '..'])) {
                require_once __DIR__ . '/../../../controllers/' . $controllerName;
                $controllerNameExploded = explode('.', $controllerName);
                $className = $controllerNameExploded[0];
                $reflectionClass = new ReflectionClass($className);
                $metodiDelController = $reflectionClass->getMethods();
                foreach ($metodiDelController as $reflectionMethod) {
                    if(strpos($reflectionMethod->name, "action") === 0) {
                        if(!($reflectionMethod->name === "actions")) {
                            $reflectionMethodClass = $reflectionMethod->class;
                            $reflectionMethodName = $reflectionMethod->name;
                            $myReflectionMethod = new ReflectionMethod($reflectionMethodClass, $reflectionMethodName);
                            $commento = $myReflectionMethod->getDocComment();
                            foreach (explode("\n", $commento) as $item) {
                                if(strpos($item, "@")) {
                                    preg_match_all("/\@(.*)\((.*)=\"(.*)\"\);/", $item, $matches);
                                    $explodedControllerName = explode("Controller", $reflectionMethod->class);
                                    $controllerCamelCase = $explodedControllerName[0];
                                    $explodedControllerNameLowered = strtolower($controllerCamelCase);
                                    $explodedActionName = explode("action", $reflectionMethod->name);
                                    $camelCaseAcion = $explodedActionName[1];
                                    $explodedAction = strtolower($camelCaseAcion);
                                    $routes[$matches[3][0]] = "{$explodedControllerNameLowered}/{$explodedAction}";
                                }
                            }
                        }
                    }
                }
            }
        }

        $file = "<?php return array(\n";
        foreach ($routes as $action => $rotta) {
            $file .= "\t'{$action}'=>'{$rotta}',\n";
        }
        $file .= ");";

        $handle = fopen(__DIR__ . '/../routing.php', "w+");
        fwrite($handle, $file);
        fclose($handle);

        $this->redirect($this->createUrl('index'));
    }

    public function actionIndex()
    {
        $this->render('index', array(
            'routes' => file_get_contents(__DIR__ . '/../routing.php')
        ));
    }

}