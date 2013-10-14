<?php

class Theme extends CTheme
{

	public static function hasAdminForm($strThemeName)
	{
		$model = Yii::app()->getComponent('wstheme')->getAdminModel($strThemeName);
		if($model) return true; else return false;

	}

	public function getConfig()
	{
		return new ThemeConfig();
	}

	public function getInfo()
	{
		return new ThemeInfo();
	}


}

class ThemeConfig
{

	/*
	 * Get a key from the module. If it's not defined, use the xlsws_configuration as a backup
	 */
	public function __get($name)
	{

		$arrConfig = Yii::app()->getComponent('wstheme')->getConfigValues(Yii::app()->theme->name);
		if(isset($arrConfig[$name]))
			return $arrConfig[$name];
		else return (_xls_get_conf($name,null));

	}

}
class ThemeInfo
{

	/*
	 * Get a key from the module. If it's not defined, use the xlsws_configuration as a backup
	 */
	public function __get($name)
	{
		$model = Yii::app()->getComponent('wstheme')->getAdminModel(Yii::app()->theme->name);
		if(!$model) return null;
		$form = new $model;
		return $form->$name;

	}

}

