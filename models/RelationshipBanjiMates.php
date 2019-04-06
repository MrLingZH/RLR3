<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relationship_banji_mates".
 *
 * @property int $id
 * @property int $banji
 * @property int $mates
 */

//该表为关系表，班级与成员的关系。

class RelationshipBanjiMates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relationship_banji_mates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['banji', 'mates'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'banji' => 'Banji',
            'mates' => 'Mates',
        ];
    }
}
