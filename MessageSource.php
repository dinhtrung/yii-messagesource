<?php
class MessageSource extends CActiveRecord{

    public $language;

	static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function __construct($scenario = 'insert'){
		try {
			return parent::__construct($scenario);
		} catch (CDbException $e){
			if (! $this->createTable()) throw $e;
			$this->refreshMetaData();
			return parent::__construct($scenario);
		}
	}

	function tableName(){
		return '{{sourcemessage}}';
	}

	/*
	 * CREATE TABLE SourceMessage
	(
			id INTEGER PRIMARY KEY,
			category VARCHAR(32),
			message TEXT
	);
	*/
	protected function createTable(){
		$columns = array(
				'id'	=>	'pk',
				'category'	=>	'string',
				'message'	=>	'text',
		);
		try {
			$this->getDbConnection()->createCommand(
					Yii::app()->getDb()->getSchema()->createTable($this->tableName(), $columns)
			)->execute();
		} catch (CDbException $e){
			Yii::log($e->getMessage(), 'warning');
		}
	}


	function relations(){
		return array(
				'mt'=>array(self::HAS_MANY,'Message','id','joinType'=>'inner join'),
		);
	}
}