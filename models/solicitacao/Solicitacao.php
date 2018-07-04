<?php

namespace app\models\solicitacao;

use Yii;
use app\models\base\Sistemas;
use app\models\base\Situacao;
use app\models\base\Usuario;
use app\models\base\Unidade;

/**
 * This is the model class for table "solicitacao".
 *
 * @property int $solic_id
 * @property string $solic_titulo
 * @property int $solic_patrimonio
 * @property string $solic_desc_equip
 * @property string $solic_desc_serv
 * @property int $solic_unidade_solicitante
 * @property int $solic_usuario_solicitante
 * @property string $solic_data_solicitacao
 * @property string $solic_data_prevista
 * @property string $solic_data_finalizacao
 * @property string $solic_prioridade
 * @property int $solic_usuario_suporte
 * @property int $solic_sistema_id
 * @property string $tipo
 * @property int $situacao_id
 *
 * @property Forum[] $forums
 * @property Sistemas $solicSistema
 * @property Situacao $situacao
 */
class Solicitacao extends \yii\db\ActiveRecord
{
    public $countDias;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['solic_patrimonio', 'solic_unidade_solicitante', 'solic_usuario_solicitante', 'solic_usuario_suporte', 'solic_sistema_id', 'situacao_id'], 'integer'],
            [['solic_desc_serv'], 'string'],
            [['solic_titulo', 'solic_desc_serv', 'solic_unidade_solicitante', 'solic_usuario_solicitante', 'solic_data_solicitacao', 'solic_tipo', 'situacao_id'], 'required'],
            [['solic_data_solicitacao', 'solic_data_prevista', 'solic_data_finalizacao', 'countDias'], 'safe'],
            [['solic_titulo'], 'string', 'max' => 100],
            [['solic_desc_equip'], 'string', 'max' => 255],
            [['solic_prioridade'], 'string', 'max' => 20],
            [['solic_tipo'], 'string', 'max' => 50],
            [['solic_sistema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sistemas::className(), 'targetAttribute' => ['solic_sistema_id' => 'id']],
            [['situacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Situacao::className(), 'targetAttribute' => ['situacao_id' => 'id']],
            [['solic_patrimonio', 'solic_desc_equip'], 'required', 'when' => function ($model, $attribute) {
                    return $model->solic_tipo=='Equipamentos';
                }, 'whenClient' => "function (attribute, value) {
                    return $('#solicitacao-solic_tipo').val() == 'Equipamentos';
                }"
            ],
            [['solic_sistema_id'], 'required', 'when' => function ($model, $attribute) {
                    return $model->solic_tipo=='Sistemas';
                }, 'whenClient' => "function (attribute, value) {
                    return $('#solicitacao-solic_tipo').val() == 'Sistemas';
                }"
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'solic_id' => 'Cód',
            'solic_titulo' => 'Título',
            'solic_patrimonio' => 'Patrimônio',
            'solic_desc_equip' => 'Descrição do Equipamento',
            'solic_desc_serv' => 'Descrição do Serviço',
            'solic_unidade_solicitante' => 'Unidade Solicitante',
            'solic_usuario_solicitante' => 'Usuário Solicitante',
            'solic_data_solicitacao' => 'Data da Solicitação',
            'solic_data_prevista' => 'Data Prevista',
            'solic_data_finalizacao' => 'Data da Finalização',
            'solic_prioridade' => 'Prioridade',
            'solic_usuario_suporte' => 'Técnico',
            'solic_sistema_id' => 'Categoria',
            'solic_tipo' => 'Tipo',
            'situacao_id' => 'Situação',
            'countDias' => 'Dias Restantes',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::className(), ['solicitacao_id' => 'solic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriaSistema()
    {
        return $this->hasOne(Sistemas::className(), ['id' => 'solic_sistema_id']);
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
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usu_codusuario' => 'solic_usuario_solicitante']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTecnico()
    {
        return $this->hasOne(Usuario::className(), ['usu_codusuario' => 'solic_usuario_suporte']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidade()
    {
        return $this->hasOne(Unidade::className(), ['uni_codunidade' => 'solic_unidade_solicitante']);
    }

}
