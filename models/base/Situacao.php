<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "situacao".
 *
 * @property int $id
 * @property string $sit_descricao
 *
 * @property Forum[] $forums
 * @property Solicitacao[] $solicitacaos
 */
class Situacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'situacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sit_descricao'], 'required'],
            [['sit_descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sit_descricao' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::className(), ['situacao_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitacaos()
    {
        return $this->hasMany(Solicitacao::className(), ['situacao_id' => 'id']);
    }
}
