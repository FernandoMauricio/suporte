<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "emailusuario_emus".
 *
 * @property int $emus_codusuario
 * @property string $emus_email
 *
 * @property UsuarioUsu $emusCodusuario
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'emailusuario_emus';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_base');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emus_codusuario', 'emus_email'], 'required'],
            [['emus_codusuario'], 'integer'],
            [['emus_email'], 'string', 'max' => 60],
            [['emus_codusuario', 'emus_email'], 'unique', 'targetAttribute' => ['emus_codusuario', 'emus_email']],
            [['emus_codusuario'], 'exist', 'skipOnError' => true, 'targetClass' => UsuarioUsu::className(), 'targetAttribute' => ['emus_codusuario' => 'usu_codusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emus_codusuario' => 'Emus Codusuario',
            'emus_email' => 'Emus Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['usu_codusuario' => 'emus_codusuario']);
    }
}
