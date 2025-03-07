<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\DynamicForm\Person;
use frontend\models\DynamicForm\PersonSearch;
use frontend\models\DynamicForm\PersonAddress;
use yii\data\ActiveDataProvider;

class FormController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel->search(Yii::$app->request->queryParams)->query,
            'pagination' => ['pageSize' => 10],
        ]);
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    public function actionCreate()
    {
        $person = new Person();
        $addresses = []; // Ensure at least one empty model exists [new PersonAddress()]

        if (Yii::$app->request->isPost) {
            $addresses = PersonAddress::createMultiple(PersonAddress::class);
            PersonAddress::loadMultiple($addresses, Yii::$app->request->post());

            // Remove empty models (all fields empty)
            $addresses = array_filter($addresses, function ($address) {
                return !empty($address->city) || !empty($address->state) || !empty($address->postal_code);
            });
        }


        if ($person->load(Yii::$app->request->post())) {
            $addresses = PersonAddress::createMultiple(PersonAddress::class);
            PersonAddress::loadMultiple($addresses, Yii::$app->request->post());

            $isPersonValid = $person->validate();
            $isAddressValid = PersonAddress::validateMultiple($addresses);
            
            if ($isPersonValid && $isAddressValid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$person->save(false)) {
                        throw new \Exception('Failed to save person details.');
                        Yii::$app->session->setFlash('error', 'Failed to save person details.');
                        return $this->render('create', [
                            'person' => $person,
                            'addresses' => $addresses,
                        ]);
                    }
                    foreach ($addresses as $address) {
                        $address->person_id = $person->id;
                        if (!$address->save(false)) {
                            throw new \Exception('Failed to save address.');
                        }
                    }
                    
                    $transaction->commit();
                    //Yii::$app->session->setFlash('success', 'Person and addresses saved successfully!');
                    return $this->redirect(['view', 'id' => $person->id]);
                } catch (\Exception $e) {
                    Yii::error($e->getMessage(), __METHOD__);
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'An error occurred while saving.');
                }
            } 
        }
        if (empty($addresses)) {
            $addresses[] = new PersonAddress();
        }

        return $this->render('create', [
            'person' => $person,
            'addresses' => $addresses,
        ]);
    }

    public function actionView($id)
    {
        $person = Person::findOne($id);
    
        if (!$person) {
            throw new NotFoundHttpException("Person not found.");
        }
    
        // Force fetch addresses using `person_id`
        $addresses = PersonAddress::find()
            ->where(['person_id' => $id])
            ->all();
    
        return $this->render('view', [
            'person' => $person,
            'addresses' => $addresses,
        ]);
    }

    public function actionDelete($id)
    {
        $person = Person::findOne($id);
        if ($person) {
            $person->delete();
        }
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $person = Person::findOne($id);

        if (!$person) {
            throw new NotFoundHttpException("Person not found.");
        }

        $addresses = PersonAddress::find()->where(['person_id' => $id])->all();

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            $newAddresses = Yii::$app->request->post('PersonAddress', []);

            PersonAddress::deleteAll(['person_id' => $id]);

            foreach ($newAddresses as $newAddressData) {
                $newAddress = new PersonAddress();
                $newAddress->attributes = $newAddressData;
                $newAddress->person_id = $id;
                $newAddress->save();
            }

            Yii::$app->session->setFlash('success', 'Record updated successfully.');
            return $this->redirect(['view', 'id' => $person->id]);
        }

        if (empty($addresses)) {
            $addresses[] = new PersonAddress(); // Ensure at least one blank row
        }
        return $this->render('update', [
            'person' => $person,
            'addresses' => $addresses,
        ]);
    }

}

