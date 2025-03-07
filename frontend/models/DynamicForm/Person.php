<?php

namespace frontend\models\DynamicForm;

use Yii;
use yii\db\ActiveRecord;
use frontend\models\DynamicForm\PersonAddress;


/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property PersonAddress[] $addresses
 */
class Person extends ActiveRecord
{
    public $addresses; // Temporary variable for dynamic form handling

    public static function tableName()
    {
        return 'person';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
            [['addresses'], 'safe'],
            [['addresses'], 'validateAddresses'], // Allow dynamic data to be assigned
        ];
    }

    public function getAddresses()
    {
        return $this->hasMany(PersonAddress::class, ['person_id' => 'id']);
    }

    public function validateAddresses($attribute, $params)
    {
        if (!empty($this->addresses)) {
            foreach ($this->addresses as $address) {
                if (!$address->validate()) {
                    $this->addError($attribute, 'One or more addresses are invalid.');
                }
            }
        }
    }
}
