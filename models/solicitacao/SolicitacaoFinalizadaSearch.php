<?php

namespace app\models\solicitacao;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\solicitacao\Solicitacao;

/**
 * SolicitacaoFinalizadaSearch represents the model behind the search form of `app\models\solicitacao\Solicitacao`.
 */
class SolicitacaoFinalizadaSearch extends Solicitacao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['solic_id', 'solic_patrimonio', 'solic_unidade_solicitante', 'solic_usuario_solicitante', 'solic_sistema_id', 'situacao_id'], 'integer'],
            [['solic_titulo', 'solic_desc_equip', 'solic_desc_serv', 'solic_data_solicitacao', 'solic_data_prevista', 'solic_data_finalizacao', 'solic_prioridade', 'solic_tipo', 'solic_usuario_suporte'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Solicitacao::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $connect = \Yii::$app->db_base;
        //$query->joinWith('tecnico');

        // grid filtering conditions
        $query->andFilterWhere([
            'solic_id' => $this->solic_id,
            'solic_patrimonio' => $this->solic_patrimonio,
            'solic_unidade_solicitante' => $this->solic_unidade_solicitante,
            'solic_usuario_solicitante' => $this->solic_usuario_solicitante,
            'solic_data_solicitacao' => $this->solic_data_solicitacao,
            'solic_data_prevista' => $this->solic_data_prevista,
            'solic_data_finalizacao' => $this->solic_data_finalizacao,
            'solic_usuario_suporte' => $this->solic_usuario_suporte,
            'solic_sistema_id' => $this->solic_sistema_id,
            'situacao_id' => 6, //Solicitações Finalizadas
        ]);


        $query->andFilterWhere(['like', 'solic_titulo', $this->solic_titulo])
            ->andFilterWhere(['like', 'solic_desc_equip', $this->solic_desc_equip])
            ->andFilterWhere(['like', 'solic_desc_serv', $this->solic_desc_serv])
            ->andFilterWhere(['like', 'solic_prioridade', $this->solic_prioridade])
            ->andFilterWhere(['like', 'solic_tipo', $this->solic_tipo])
            ->andFilterWhere(['like', 'solic_usuario_suporte', $this->solic_usuario_suporte]);

        return $dataProvider;
    }
}
