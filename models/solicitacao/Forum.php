<?php

namespace app\models\solicitacao;

use Yii;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Usuario;
use app\models\base\Unidade;

/**
 * This is the model class for table "forum".
 *
 * @property int $id
 * @property string $for_mensagem
 * @property string $for_data
 * @property int $for_usuario_id
 * @property string $for_data_prevista
 * @property int $for_usuario_suporte
 * @property int $solicitacao_id
 * @property int $situacao_id
 *
 * @property Situacao $situacao
 * @property Solicitacao $solicitacao
 */
class Forum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['for_mensagem', 'for_data', 'for_usuario_id', 'solicitacao_id'], 'required'],
            [['for_mensagem'], 'string'],
            [['for_data', 'for_data_prevista'], 'safe'],
            [['for_usuario_id', 'for_usuario_suporte', 'solicitacao_id', 'situacao_id'], 'integer'],
            [['situacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Situacao::className(), 'targetAttribute' => ['situacao_id' => 'id']],
            [['solicitacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitacao::className(), 'targetAttribute' => ['solicitacao_id' => 'solic_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'for_mensagem' => 'Mensagem',
            'for_data' => 'Data',
            'for_usuario_id' => 'Usuario ID',
            'for_data_prevista' => 'Data Prevista',
            'for_usuario_suporte' => 'Usuario Suporte',
            'solicitacao_id' => 'Solicitação',
            'situacao_id' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSituacao()
    {
        return $this->hasOne(Situacao::className(), ['id' => 'situacao_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitacao()
    {
        return $this->hasOne(Solicitacao::className(), ['solic_id' => 'solicitacao_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTecnicoForum()
    {
        return $this->hasOne(Usuario::className(), ['usu_codusuario' => 'for_usuario_suporte']);
    }

}
