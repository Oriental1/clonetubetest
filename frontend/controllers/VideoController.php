<?php


namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\Video;
use common\models\VideoLike;
use yii\web\NotFoundHttpException;
use common\models\VideoView;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class VideoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['like', 'dislike', 'history'],
                'rules' => [
                    [
                    'allow' => true,
                    'roles' => ['@']
                    ]
                ]
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'like' => ['post'],
                    'dislike' => ['post'],
                ]
            ]
        ];
    }
    public function actionIndex()
    {
        $this->layout = 'main';
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()->with('createdBy')->published()->latest(),
            'pagination' => [
                'pageSize' => 2
            ]
        ]);
        return $this->render(view: 'index', params: [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id= null)
    {
        $this->layout="auth";
        if ($id === null) {
            $id = Yii::$app->request->get('id');
        }
      
        $video = $this->findVideo($id);

        $videoView = new VideoView();
        $videoView->video_id = $id;
        $videoView->user_id = \Yii::$app->user->id;
        $videoView->created_at = time();
        $videoView->save();

        $similarVideos = Video::find()
            ->published()
            ->byKeyword($video->title)
            ->andWhere(['NOT', ['video_id' => $id]])
            ->limit(10)
            ->all();

        return $this->render('view', [
            'model' => $video,
            'similarVideos' => $similarVideos
        ]);
    }

    public function actionLike($id=null)
    {
        if ($id === null) {
            $id = Yii::$app->request->get('id');
        }
        $video = $this->findVideo($id);
        $userId = \Yii::$app->user->id;

        $videoLikeDislike = VideoLike::find()
            ->userIdVideoId($userId, $id)
            ->one();
        
        if (!$videoLikeDislike) {
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_LIKE);
        } else if ($videoLikeDislike->type == VideoLike::TYPE_LIKE){
            $videoLikeDislike->delete();
        } else {
            $videoLikeDislike->delete();
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_LIKE);
        }

        return $this->renderAjax('_buttons',[
            'model' => $video
        ]);
    }
    public function actionDislike($id=null)
    {
        if ($id === null) {
            $id = Yii::$app->request->get('id');
        }
        $video = $this->findVideo($id);
        $userId = \Yii::$app->user->id;

        $videoLikeDislike = VideoLike::find()
            ->userIdVideoId($userId, $id)
            ->one();
        
        if (!$videoLikeDislike) {
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_DISLIKE);
        } else if ($videoLikeDislike->type == VideoLike::TYPE_DISLIKE){
            $videoLikeDislike->delete();
        } else {
            $videoLikeDislike->delete();
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_DISLIKE);
        }

        return $this->renderAjax('_buttons',[
            'model' => $video
        ]);
    }

    public function actionSearch($keyword)
    {
        $this->layout = 'main';
        $quary = Video::find()
            ->with('createdBy')
            ->published()
            ->latest();
        if ($keyword){
            $quary->byKeyword($keyword);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $quary
        ]);

        return $this->render('search',  [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionHistory()
    {
        $this->layout = 'main';
        $query = Video::find()
            ->alias('v')
            ->innerJoin("(SELECT video_id, MAX(created_at) as max_date FROM video_view
            WHERE user_id = :userId
            GROUP BY video_id) vv", 'vv.video_id = v.video_id', [
                'userId' => \Yii::$app->user->id
            ])
            ->orderBy("vv.max_date DESC");
        
            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);

            return $this->render('history', [
                'dataProvider' => $dataProvider
            ]);
    }
    

    protected function findVideo($id)
    {
        $video = Video::find()->where(['video_id' => $id])->one();
        if (!$video){
            throw new NotFoundHttpException("Video does not exist");
        }
        return $video;
    }

    protected function saveLikeDislike($videoId, $userId, $type)
    {
        $videoLike = new VideoLike();
        $videoLike->video_id = $videoId;
        $videoLike->user_id = $userId;
        $videoLike->type = $type;
        $videoLike->created_at = time();
        $videoLike->save();
    }
}
