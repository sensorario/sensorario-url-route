Install
-------

I suggest to install this module using composer.

    {
      ...
      "require": {
        ...
        "sensorario/sensorariourlroute": "@dev"
      }
      ...
    }


Load SensorarioUrlRoute module in config/main.php configuration file:

    'sensorariourlroute' => array(
      'class' => 'webroot.vendor.sensorario.sensorariourlroute.SensorarioUrlRouteModule',
    ),

Enable URLs in path format:

    'components' => array(
      'urlManager' => array(
        'urlFormat' => 'path',
        'rules' => require 'routing.php',
      ),
    ),

Usage
-----

Add annotations in DocComments of actions:

    class SiteController extends Controller

      /**
       * @Route(name="homepage");
       */
      public function actionIndex()
      {
        $this->render('index');
      }

    }

Go to index.php/SensorarioUrlRoute and click on "Generate routing file". A file
will be generated with this contents:

    <?php return array(
        'homepage'=>'site/index',
    );

Now, to get rout of 'homepage' route, you could use this code:

    ((new CreateUrl('homepage'))->getRoute()))


Usage with parameters
---------------------

Instead of /site/hello/username/sensorario you can type url /hello/sensorario.

    /**
     * @Route(value="hello/<username:\w+>");
     */
    public function actionHello($username)
    {
      echo "Hello {$username}";
      die;
    }