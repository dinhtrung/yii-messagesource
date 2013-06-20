<?php
/*
 * Automatically generate missing translation into category translate file for CGettextMessageSource
 * @author Nguyen Dinh Trung <nguyendinhtrung141@gmail.com>
 */
class CGettextMessageTranslator extends CApplicationComponent{
	public static $message = array();
	public static $init = FALSE;
	public static $sender;
	public static function appendMessage(CMissingTranslationEvent $event){
		self::$message[$event->language][$event->category][$event->message] = '';
		if (! self::$init){
			Yii::app()->attachEventHandler('onEndRequest', array('CGettextMessageTranslator', 'writeMessage'));
			self::$sender = $event->sender;
			self::$init = TRUE;
		}
	}
	/**
	 * Generate the PO file based on the 
	 */
	public static function writeMessage(CEvent $event){
		$overwrite = TRUE;
		$removeOld = TRUE;
		$sort = TRUE;

		foreach (self::$message as $lang => $data){
			$dir = Yii::getPathOfAlias('application.messages') . DIRECTORY_SEPARATOR . $lang;
			if (! is_dir($dir)) mkdir($dir, 0777, TRUE);
			foreach ($data as $category => $untranslated){
				// Sort the untranslated messages
				ksort($untranslated);
				// Load the Gettext Message File
				$messageFile=self::$sender->basePath . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $category;
				/*
				 * For Gettext we only work with PO file...
				 */
// 				if(self::$sender->useMoFile)
// 					$messageFile.=CGettextMessageSource::MO_FILE_EXT;
// 				else
					$messageFile.=CGettextMessageSource::PO_FILE_EXT;
				
				$translated = array();
				$file=new CGettextPoFile();
				if (is_file($messageFile))
				{
					$translated=$file->load($messageFile,$category);
					ksort($translated);
				}

				$merged = array_merge($untranslated, $translated);
				
				$file->save($messageFile, $merged);
			}
		}
	}
}