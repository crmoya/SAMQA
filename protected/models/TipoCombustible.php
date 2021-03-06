<?php

/**
 * This is the model class for table "tipoCombustible".
 *
 * The followings are the available columns in table 'tipoCombustible':
 * @property integer $id
 * @property string $nombre
 */
class TipoCombustible extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TipoCombustible the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getNombre($id){
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		nombre
			from		tipoCombustible
			where 		id = :id  
			"
		);
		$command->bindParam(":id",$id,PDO::PARAM_INT);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		foreach($rows as $row){
			return $row['nombre'];
		}
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tipoCombustible';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nombre', 'required'),
			array('nombre', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nombre,vigente', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nombre' => 'Nombre',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nombre',$this->nombre,true);
		$criteria->compare('vigente',$this->vigente,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function listar(){

		$data = array();
		$connection=Yii::app()->db;
		$connection->active=true;
		$command=$connection->createCommand("
			select		id,nombre
			from		tipoCombustible
			where		vigente = 'S??'
			order by	nombre
			"
		);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active=false;
		$command = null;
		$data[0]=array('nombre'=>"Seleccione un tipo de Combustible",'id'=>'');
		$i=1;
		foreach($rows as $row){
			$data[$i]=$row;
			$i++;
		}
		return $data;
	}
}