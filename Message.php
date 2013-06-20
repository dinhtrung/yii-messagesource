<?php
class Message extends CActiveRecord{

    public $message,$category;

	public function __construct($scenario = 'insert'){
		try {
			return parent::__construct($scenario);
		} catch (CDbException $e){
			if (! $this->createTable()) throw $e;
			$this->refreshMetaData();
			return parent::__construct($scenario);
		}
	}

	static function model($className=__CLASS__){
		return parent::model($className);
	}

	function tableName(){
		return '{{message}}';
	}

	function relations(){
		return array(
            'source'=>array(self::BELONGS_TO,'MessageSource','id'),
		);
	}


	/*
	 * CREATE TABLE Message
		(
		    id INTEGER,
		    language VARCHAR(16),
		    translation TEXT,
		    PRIMARY KEY (id, language),
		    CONSTRAINT FK_Message_SourceMessage FOREIGN KEY (id)
		         REFERENCES SourceMessage (id) ON DELETE CASCADE ON UPDATE RESTRICT
		);
	 */
	protected function createTable(){
		$columns = array(
				'id'	=>	'int',
				'language'	=>	'string',
				'translation'	=>	'text',
		);
		try {
			$this->getDbConnection()->createCommand(
				Yii::app()->getDb()->getSchema()->createTable($this->tableName(), $columns)
			)->execute();
			$this->getDbConnection()->createCommand(
				Yii::app()->getDb()->getSchema()->addPrimaryKey('id_lang', $this->tableName(), 'id,language')
			)->execute();
			$ref = new MessageSource();
			$this->getDbConnection()->createCommand(
				Yii::app()->getDb()->getSchema()->addForeignKey('fk_message_sourcemessage', $this->tableName(), 'id', $ref->tableName(), 'id')
			)->execute();
		} catch (CDbException $e){
			Yii::log($e->getMessage(), 'warning');
		}
	}
}