<?php

namespace app\controllers;

use app\models\Hybrids;
use app\models\StatisticsData;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $hybrids = Hybrids::find()
            ->select('hybrids.Name, hybrids.id')
            ->join('JOIN', 'cultures', 'hybrids.culturesId = cultures.id')
            ->where(['cultures.name' => 'Кукурудза'])
            ->all();
        return $this->render('index', ['hybrids' => $hybrids]);
    }

    public function actionStatisticsTask()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $hybridFirst = $data['first'];
            $hybridSecond = $data['second'];

            $query = "SELECT COUNT(Hybrids.Name) as result
FROM StatisticsData
JOIN Cultures ON StatisticsData.CultureId = Cultures.id
JOIN Hybrids ON StatisticsData.HybridId = Hybrids.id
WHERE CONCAT( StatisticsData.Latitude, StatisticsData.Longitude ) 
IN (SELECT CONCAT(StatisticsData.Latitude, StatisticsData.Longitude) FROM StatisticsData WHERE StatisticsData.HybridId = $hybridFirst)
AND Hybrids.id = $hybridSecond
GROUP BY Hybrids.Name";

            $response = Yii::$app->db->createCommand($query)
                ->queryColumn();


            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'response' => $response,
            ];
        }
    }

    public function actionStatisticsExample()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $hybrid = $data['hybridID'];

            $query = "SELECT CONCAT(Hybrids.Name, ' (', COUNT(Hybrids.Name), ')') as result
FROM StatisticsData
JOIN Cultures ON StatisticsData.CultureId = Cultures.id
JOIN Hybrids ON StatisticsData.HybridId = Hybrids.id
WHERE CONCAT( StatisticsData.Latitude, StatisticsData.Longitude ) 
IN (SELECT CONCAT(StatisticsData.Latitude, StatisticsData.Longitude) FROM StatisticsData WHERE StatisticsData.HybridId = $hybrid)
AND Hybrids.id <> $hybrid
GROUP BY Hybrids.Name";

            $response = Yii::$app->db->createCommand($query)
                ->queryColumn();


            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'response' => $response,
            ];
        }
    }
}
