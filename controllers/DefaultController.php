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
                $metodiDelController = (new ReflectionClass(explode('.', $controllerName)[0]))->getMethods();
                foreach ($metodiDelController as $reflectionMethod) {
                    if(strpos($reflectionMethod->name, "action") === 0) {
                        if(!($reflectionMethod->name === "actions")) {
                            $commento = (new ReflectionMethod($reflectionMethod->class, $reflectionMethod->name))->getDocComment();
                            foreach (explode("\n", $commento) as $item) {
                                if(strpos($item, "@")) {
                                    preg_match_all("/\@(.*)\((.*)=\"(.*)\"\);/", $item, $matches);
                                    $explodedControllerName = strtolower(explode("Controller", $reflectionMethod->class)[0]);
                                    $explodedAction = strtolower(explode("action", $reflectionMethod->name)[1]);
                                    $routes[$matches[3][0]] = "{$explodedControllerName}/{$explodedAction}";
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