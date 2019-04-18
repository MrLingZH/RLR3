<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rlationship_contacts".
 *
 * @property int $id
 * @property int $me 我
 * @property int $contacts 我的联系人
 * @property string $addTime
 */
class RelationshipContacts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relationship_contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['me', 'contacts'], 'integer'],
            [['addTime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'me' => 'Me',
            'contacts' => 'Contacts',
            'addTime' => 'Add Time',
        ];
    }
}
