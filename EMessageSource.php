<?php 
/**
 * EMessageSource
 */
class EMessageSource extends CMessageSource {
	public $sources = array('CPhpMessageSource');
	
	/**
	 * Load all available sources and its configuration
	 * @see CApplicationComponent::init()
	 */
	public function init(){
		foreach ($this->sources as $componentId => $config){
			Yii::app()->setComponent($componentId, $config);
		}
		return parent::init();
	}
	
	protected function loadMessages($category, $language){
	
	}
	
	public function translate($category, $message, $language = NULL){
		foreach ($this->sources as $componentId => $config){
			if (Yii::app()->getComponent($componentId) instanceof CMessageSource){
				$translated = Yii::app()->getComponent($componentId)->translate($category, $message, $language);
				if ($translated != $message) return $translated;
			}
		}
		return $translated;
	}
}

?>