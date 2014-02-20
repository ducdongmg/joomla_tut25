<?php
/**
 * @version    $Id$
 * @package    JSNPoweradmin
 * @subpackage helpers
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

final class JSNLanguageHelper
{
	public static function getSupportedLanguage ($area = 'site')
	{
		$path = (($area == 'site') ? JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/site' : JPATH_COMPONENT_ADMINISTRATOR).DS.'languages/admin';
		$files = glob("{$path}/*.ini");
		$supportedLanguages = array();
		foreach ($files as $file) {
			$name = basename($file);
			if (preg_match('/^([a-z]+)\-([A-Z]+)\./', $name, $matches)) {
				$code = $matches[1].'-'.$matches[2];
				if (!in_array($code, $supportedLanguages)) {
					$supportedLanguages[] = $code;
				}
			}
		}
		return $supportedLanguages;
	}

	public static function getSupportedExtLangs()
	{
		JPluginHelper::importPlugin('jsnpoweradmin');
		$dispatcher 	= JDispatcher::getInstance();
		$plugins 		= $dispatcher->trigger('getSupportedLanguages');
		$languages		= array();
		foreach ($plugins as $plugin)
		{
			foreach ($plugin as $client => $language)
			{
				$languages [$client][$language['files'][0]] = (string) $language['path'][0];
			}
		}
		return $languages;
	}

	public static function installSupportedExtLangs($langs)
	{
		$languages	=	self::getSupportedExtLangs();

		//Install lang for sources and themes
		if (isset($languages['admin']))
		{
			if (count($langs))
			{
				foreach ($langs as $lang)
				{
					foreach ($languages['admin'] as $plugin)
					{
						if($plugin){
							$files = glob($plugin . "/{$lang}/" . "{$lang}.*.ini");
							foreach ($files as $file)
							{
								echo $file."<br>";
								try {
									copy($file, JPATH_ADMINISTRATOR . '/' . 'language' . '/' . $lang . '/' . basename($file));
								} catch (Exception $e) {
									throw $e->getMessage();
								}
							}
						}
					}
				}
			}
		}
	}
}




