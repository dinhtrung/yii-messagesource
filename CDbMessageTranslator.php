<?php
/*
 * Automatically generate missing translation into category translate file for CPhpMessageSource
 * @author Nguyen Dinh Trung <nguyendinhtrung141@gmail.com>
 */
class CDbMessageTranslator extends CApplicationComponent{
	public static $message = array();
	public static $init = FALSE;
	/**
	 * Store the message in a list
	 * @param CMissingTranslationEvent $event
	 */
	public static function appendMessage(CMissingTranslationEvent $event){
		self::$message[] = array('language' => $event->language, 'category' => $event->category, 'message' => $event->message);
		if (! self::$init){
			Yii::app()->attachEventHandler('onEndRequest', array('CDbMessageTranslator', 'writeMessage'));
			self::$init = TRUE;
		}
	}
	/**
	 * Do saving the messages into db
	 */
	public static function writeMessage(){
		foreach (self::$message as $msg){
			$model=new MessageSource();
			$model->attributes = $msg;
			$model->save();
		}
	}
}