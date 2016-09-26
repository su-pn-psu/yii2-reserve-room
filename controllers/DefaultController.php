<?php

namespace suPnPsu\reserveRoom\controllers;

use Yii;
use suPnPsu\room\models\Room;
use suPnPsu\reserveRoom\models\RoomReserve;
use suPnPsu\reserveRoom\models\RoomReserveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use suPnPsu\room\models\RoomSearch;
use suPnPsu\room\models\RoomListSearch;

/**
 * DefaultController implements the CRUD actions for RoomReserve model.
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all RoomReserve models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RoomReserveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RoomReserve model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RoomReserve model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new RoomReserve();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->created_by = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }


        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RoomReserve model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RoomReserve model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RoomReserve model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoomReserve the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RoomReserve::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    ##############################

    public function actionRoomList() {

        $searchModel = new RoomListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        //$this->view->title = 'รายการห้องทั้งหมด';
        return $this->renderPartial('room-list', [
                    //'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);

//        if (Yii::$app->request->isAjax) {
//            return $this->renderAjax('room-list', [
//                        //'model' => $model,
//                        'searchModel' => $searchModel,
//                        'dataProvider' => $dataProvider,
//            ]);
//        }else{            
//            return $this->renderPartial('room-list', [
//                    //'model' => $model,
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//        ]);
//        }
    }
    
    
    public function actionJsoncalendar($start = NULL, $end = NULL, $_ = NULL) {
        $events = RoomReserve::getActivity();
        header('Content-type: application/json');
        echo Json::encode($events);
        Yii::$app->end();
    }

}
