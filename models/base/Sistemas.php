<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "sistemas".
 *
 * @property int $id
 * @property string $sist_descricao
 *
 * @property Solicitacao[] $solicitacaos
 */
class Sistemas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sistemas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sist_descricao'], 'required'],
            [['sist_descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sist_descricao' => 'Sist Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitacaos()
    {
        return $this->hasMany(Solicitacao::className(), ['solic_sistema_id' => 'id']);
    }
}
