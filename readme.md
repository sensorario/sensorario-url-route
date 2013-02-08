Install with composer
---------------------

    {
        "require": {
            "sensorario/sensorariourlroute": "1.0.1"
        }
    }

Install
-------

Load SensorarioUrlRoute module in config/main.php configuration file:

    'modules' => array(
        'SensorarioUrlRoute',
    ),

Enable URLs in path format:

    'components' => array(
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => require __DIR__ . '/../modules/SensorarioUrlRoute/routing.php',
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

