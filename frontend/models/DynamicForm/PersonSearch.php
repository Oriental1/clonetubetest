<?php

namespace frontend\models\DynamicForm;

use yii\data\ActiveDataProvider;
use frontend\models\DynamicForm\Person;

class PersonSearch extends Person
{
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Person::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10, // Adjust as needed
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
              ->andFilterWhere(['like', 'last_name', $this->last_name]);

        return $dataProvider;
    }
}
