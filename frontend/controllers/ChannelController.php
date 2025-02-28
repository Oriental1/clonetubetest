<?php

namespace frontend\controllers;

use common\models\Video;
use frontend\models\Channel;
use common\models\Subscriber;
use yii\web\Controller;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;


/**
 * Class ChannelController
 * 
 * @package frontend\controllers
 * 
 * ${CARET}
 * 
 * 
 */

class ChannelController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['subscribe'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                        ]
                ]
            ]
        ];
    }

    /*public function actionView($username)
    {
        $channel = $this->findChannel($username);

        $query = Video::find()->creator($channel->id)->published();

        // Debugging: Output SQL query and fetched results
        echo "<pre>";
        print_r($query->createCommand()->sql); // Print the SQL query
        print_r($query->all()); // Fetch and display results
        echo "</pre>";
        exit; // Stop further execution to see output
    }*/


    public function actionView($username)
    {
        $channel = $this->findChannel($username);

        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()->creator($channel->id)->published()
        ]);
        
        return $this->render('view', [
            'channel' => $channel,
            'dataProvider' =>$dataProvider
        ]);
    }

    public function actionSubscribe($username){
        $channel = $this->findChannel($username);
        
        $userId = \Yii::$app->user->id;
        $subscriber = $channel->isSubscribed($userId);
        if (!$subscriber) {
            $subscriber = new Subscriber();
            $subscriber->channel_id = $channel->id;
            $subscriber->user_id = $userId;
            $subscriber->created_at = time();
            $subscriber->save();
            \Yii::$app->mailer->compose([
                'html' => 'subscriber-html', 'text' => 'subscriber-text'
            ], [
                'channel' => $channel,
                'user' => \Yii::$app->user->identity
            ])
                ->setFrom(\Yii::$app->params['senderEmail'])
                ->setTo($channel->email)
                ->setSubject('You have new subscriber')
                ->send(); 
            } else {
            $subscriber->delete();
        }
        
        return $this->renderAjax('_subscribe', [
            'channel' => $channel
        ]);
    }

    /**
    *  @param $username

    * @throw \yii\web\NotFoundHttpException
    */

    protected function findChannel($username)
    {
        $channel = User::findByUsername($username);
        if (!$channel){
            throw new NotFoundHttpException("Channel does not exist");
        }

        return $channel;
    }
}