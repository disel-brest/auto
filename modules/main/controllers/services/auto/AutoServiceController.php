<?php

namespace app\modules\main\controllers\services\auto;


use app\board\entities\AutoService;
use app\board\entities\AutoServiceWork;
use app\board\readModels\AutoServiceReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AutoServiceController extends Controller
{
    private $autoServices;

    public function __construct($id, $module, AutoServiceReadRepository $autoServices, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->autoServices = $autoServices;
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $count = $this->autoServices->count();
        $work_id = \Yii::$app->request->get('work_id');
        $work = $work_id ? AutoServiceWork::findOne($work_id) : null;

        $dataProvider = $work_id ? $this->autoServices->getByWorkID($work_id) : $this->autoServices->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'count' => $count,
            'work' => $work,
        ]);
    }

    public function actionView($id)
    {
        if (!$autoService = $this->autoServices->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->layout = "@app/views/layouts/auto-service";

        $autoService->updateCounters(['views' => 1]);

        return $this->render('view', [
            'autoService' => $autoService,
        ]);
    }

    public function actionMapUpdate()
    {
        $leftDownLAT = \Yii::$app->request->post('swlat');
        $leftDownLNG = \Yii::$app->request->post('swlng');
        $rightUpLAT = \Yii::$app->request->post('nelat');
        $rightUpLNG = \Yii::$app->request->post('nelng');
        $workID = \Yii::$app->request->post('work_id');

        $autoServices = AutoService::find()->byCoordinates($leftDownLAT, $leftDownLNG, $rightUpLAT, $rightUpLNG);
        if ($workID) {
            $autoServices->byWorkID($workID);
        }

        $elements = [];
        foreach ($autoServices->each() as $autoService) {
            /* @var $autoService AutoService */
            $elements[] = [
                "type" => "node",
                "name" => $autoService->name,
                "lat" => $autoService->lat,
                "lon" => $autoService->lng,
                "info" => $autoService->info,
            ];
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['elements' => $elements];
    }
}