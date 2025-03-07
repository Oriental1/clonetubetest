<?php

namespace frontend\models\DynamicForm;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $person_id
 * @property string $city
 * @property string $state
 * @property string $postal_code
 */
class PersonAddress extends ActiveRecord
{
    public static function tableName()
    {
        return 'address';
    }

    public function rules()
    {
        return [
            
            [['city', 'state'], 'string', 'max' => 255],
            [['postal_code'], 'string', 'max' => 10],
        ];
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }

    /**
     * Creates multiple instances of Address model
     *
     * @param string $class
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($class, $multipleModels = [])
    {
        $models = [];
        $formName = (new $class)->formName();
        $post = Yii::$app->request->post($formName);

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[$i] = new $class;
                }
                $models[$i]->load($item, '');
            }
        }
        return $models;
    }
}
