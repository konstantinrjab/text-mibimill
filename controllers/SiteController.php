<?php

namespace app\controllers;

use app\models\Hybrids;
use app\models\StatisticsData;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\db\Expression;

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

            $subquery = StatisticsData::find()
                ->select('CONCAT(`StatisticsData`.`Latitude`, `StatisticsData`.`Longitude`)')
                ->where('StatisticsData.HybridId=:hybridFirst', [':hybridFirst' => $hybridFirst]);

            $result = StatisticsData::find()
                ->select('COUNT(`Hybrids`.`Name`) as result')
                ->join('JOIN', 'Cultures', 'StatisticsData.CultureId = Cultures.id')
                ->join('JOIN', 'Hybrids', 'StatisticsData.HybridId = Hybrids.id')
                ->where(['in', ' CONCAT( StatisticsData.Latitude, StatisticsData.Longitude ) ', $subquery])
                ->andWhere('Hybrids.id='.$hybridSecond)
                ->groupBy('Hybrids.Name')
                ->asArray()
                ->one();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'response' => $result['result'],
            ];
        }
    }

    public function actionStatisticsExample()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $hybrid = $data['hybridID'];

            $subquery = StatisticsData::find()
                ->select('CONCAT(`StatisticsData`.`Latitude`, `StatisticsData`.`Longitude`)')
                ->where('StatisticsData.HybridId=:hybrid', [':hybrid' => $hybrid]);

            $expression = new Expression('CONCAT(Hybrids.Name, \' ( \', COUNT(Hybrids.Name), \' )\') as result');
            $result = StatisticsData::find()
                ->addSelect($expression)
                ->join('JOIN', 'Cultures', '`StatisticsData`.`CultureId` = `Cultures`.`id`')
                ->join('JOIN', 'Hybrids', '`StatisticsData`.`HybridId` = `Hybrids`.`id`')
                ->where(['in', ' CONCAT( StatisticsData.Latitude, StatisticsData.Longitude ) ', $subquery])
                ->andWhere('Hybrids.id<>'.$hybrid)
                ->groupBy('Hybrids.Name')
                ->column();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'response' => $result,
            ];
        }
    }
}
