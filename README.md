Yii EMessageSource
=================


## Introduction

EMessageSource is an unified Message Sources for Yii.

Default, Yii only use one message source. Like `CPhpMessageSource`, `CDbMessageSource` or `CGettextMessageSource`.

This component help you scan a list of all available message source and find if it has been translated.

## Usage

In your application config file:

~~~
		'messages' => array(
			'class'	=>	'ext.components.EMessageSource',
			'sources' => array(
				// @XXX: The first message source we should check
				'phpMessage' => array(
					'class'	=>	'CPhpMessageSource',
					'onMissingTranslation'	=>	array('CPhpMessageTranslator', 'appendMessage'),
				),
				// @XXX: Another MessageSource
				'dbMessage' => array(
					'class'	=>	'CDbMessageSource',
					'sourceMessageTable'	=>	'sourcemessage',
					'translatedMessageTable'	=>	'message',
					'onMissingTranslation'	=> array('CDbMessageTranslator', 'appendMessage'),
				),   
				// @XXX: Another brick in the wall
				'gettextMessage' => array(
					'class'	=>	'CGettextMessageSource',
					'onMissingTranslation'	=> array('CGettextMessageTranslator', 'appendMessage'),
				),   
			),
		),
~~~

From now on, whenever you call `Yii::t()`, the application will check all the message source, then return the translation if available.

It even can write the `CPhpMessageSource` translation file, and write the `CDbMessageTranslator` message too...

So that's it.



