<?php
/**
 * @version     $Id$
 * @package     JSN_Mobilize
 * @subpackage  SystemPlugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
error_reporting(1);
// Load client device detection library
//require_once dirname(__FILE__) . '/libraries/joomlashine/client/device.php';
require_once dirname(__FILE__) . '/libraries/joomlashine/client/mobiledetect.php';
/**
 * System plugin for initializing JSN Mobilize.
 *
 * @package     JSN_Mobilize
 * @subpackage  SystemPlugin
 * @since       1.0.0
 */
class PlgSystemJSNMobilize extends JPlugin
{

	/**
	 * Application object.
	 *
	 * @var  $_app  object  An instance of JApplication class.
	 */
	private static $_app;

	/**
	 * JSN Mobilize global configuration.
	 *
	 * @var  $_cfg  object  An instance of JObject class.
	 */
	private static $_cfg;

	/**
	 * Real request URI after parsing for user preference.
	 *
	 * @var  $_request  string
	 */
	private static $_request;

	/**
	 * Detected user preference.
	 *
	 * @var  $_device  string
	 */
	private static $_device;

	/**
	 * Event handler to get user preference.
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		// Initialize JSN Mobilize
		require_once dirname(__FILE__) . '/define.php';
		if (!class_exists('JSNConfigHelper'))
		{
			return;
		}
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? 'mobilize' : 'desktop');

		// Get parsed request URI object
		$jUri = JURI::getInstance();
		// Get application object
		self::$_app = JFactory::getApplication();
		$config = JFactory::getConfig();
		$rewrite = $config->get("sef_rewrite") ? "/" : "/index.php/";
		// Check if request URI match mobile/tablet URI
		$linkMobilize = JURI::root(true) . $rewrite . "jsn-preview-mobilize-" . md5(JURI::root()) . "/";
		// Get JSN Mobilize configuration
		self::$_cfg = JSNConfigHelper::get('com_mobilize');
		self::$_cfg->set('link_mobilize', $linkMobilize);
		// Check cookie
		$urlRequest = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
		if (strpos($urlRequest, "jsn-preview-mobilize-" . md5(JURI::root())) == false && $deviceType == "desktop")
		{
			self::$_device = "";
			JSNUtilsCookie::set('site', '', array(), 'mobilize');
		}
		// Continue only if not in administrator section
		if (!self::$_app->isAdmin())
		{
			// Does user prefer desktop site?
			if (self::$_app->input->getInt('switch_to_desktop_ui') == 1)
			{
				self::$_device = 'desktop';
			}
			elseif (self::$_app->input->getInt('switch_to_desktop_ui') == 2)
			{
				$device = $deviceType;
				JSNUtilsCookie::set('site', '', array(), 'mobilize');
				self::$_device = $device;
				//die;
			}

			if (!isset(self::$_device))
			{
				// Check if request URI match mobile/tablet URI
				foreach (array('mobilize' => self::$_cfg->get('link_mobilize')) AS $device => $link)
				{
					// Is mobile/tablet URI path-based?
					if (substr($link, 0, 1) == '/')
					{
						// Get request path from request URI
						$path = $jUri->getPath();
						// Check if mobile/tablet UI is requested?
						if (strcasecmp(trim($link, '/'), trim($path, '/')) == 0 OR strpos(strtolower($path), '/' . trim(strtolower($link), '/') . '/') === 0)
						{
							self::$_device = $device;
							// Update parsed request URI object
							$jUri->setPath(preg_replace('#^' . '/' . trim(strtolower($link), '/') . '#i', JURI::root(true), $path));
							break;
						}
					}
					// Mobile/tablet URI is domain-based
					else
					{
						// Get request host from request URI
						$host = $jUri->getHost();

						// Check if mobile/tablet UI is requested?
						if (strcasecmp($link, $host) == 0)
						{
							self::$_device = $device;
							break;
						}
					}
				}
			}

			if (!isset(self::$_device))
			{
				// Does user prefer mobile/tablet site before?
				if (($cookie = JSNUtilsCookie::get('site', '', 'mobilize')) != '')
				{

					self::$_device = $cookie;
				}
				// We don't have user preference, let's detect client device type
				else
				{
					// Auto-detect client device type

					self::$_device = $deviceType;
				}
			}
			require_once JPATH_BASE . '/templates/jsn_mobilize/helpers/mobilize.php';
			if (strpos($urlRequest, "jsn-preview-mobilize-" . md5(JURI::root())))
			{
				$mCfg = JSNMobilizeTemplateHelper::getConfig(self::$_device, true);
			}
			else
			{
				$mCfg = JSNMobilizeTemplateHelper::getConfig(self::$_device);
			}

			if (!empty($device))
			{
				$deviceUi = $device . "_ui_enabled";
			}
			else
			{
				$deviceUi = "";
			}
			// Check if mobile/tablet UI is enabled?
			if (strpos($urlRequest, "jsn-preview-mobilize-" . md5(JURI::root())) == false && $deviceType == "desktop" && $deviceUi)
			{

				if (!self::$_app->input->getInt('_preview') AND !$mCfg->get($deviceUi) AND self::$_device == "mobilize")
				{
					self::$_device = 'desktop';
				}
			}
			// Do some preparation for mobile/tablet site rendering
			if (self::$_device == "mobilize")
			{
				// Reparse request URI
				$jUri->parse($jUri->toString());
			}
			// Store user preference to cookie
			if (($cookie = JSNUtilsCookie::get('site', '', 'mobilize')) == '' OR $cookie != self::$_device)
			{
				JSNUtilsCookie::set('site', self::$_device, array(), 'mobilize');
			}
			// Load language file
			$this->_loadLanguage();
		}
	}

	/**
	 * Event handler to re-parse request URI.
	 *
	 * @return  void
	 */
	public function onAfterRoute()
	{
		if (!class_exists('JSNConfigHelper'))
		{
			return true;
		}

		// Continue only if not in administrator section and in mobile/tablet site
		if (!self::$_app->isAdmin())
		{
			require_once JPATH_BASE . '/templates/jsn_mobilize/helpers/mobilize.php';
			if (self::$_device == "mobilize")
			{
				// Get input object
				$input = self::$_app->input;
				// Set necessary variables to request array
				$urlRequest = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
				if (strpos($urlRequest, "jsn-preview-mobilize-" . md5(JURI::root())))
				{
					$mCfg = JSNMobilizeTemplateHelper::getConfig(self::$_device, true);
				}
				else
				{
					$mCfg = JSNMobilizeTemplateHelper::getConfig(self::$_device);
				}
				if (!empty($mCfg))
				{
					self::$_app->setTemplate('jsn_mobilize');
					$input->set('_device', self::$_device);
				}
			}
			if (JSNMobilizeTemplateHelper::getConfig("mobilize"))
			{
				self::$_app->registerEvent('onAfterRender', 'jsnMobilizeFinalize');
			}
		}
	}

	/**
	 * Alter response body if necessary.
	 *
	 * - Mobile/tablet site: alter URI based on detected client device type.
	 * - Desktop site: inject switcher link if visitor viewing desktop site on either mobile device or tablet PC.
	 *
	 * @return  void
	 */
	public static function onAfterRender()
	{
		$detect = new Mobile_Detect;
		//var_dump($detect->isMobile());
		//var_dump(self::$_device);
		$deviceType = ($detect->isMobile() ? 'mobilize' : 'desktop');
		if (!defined('JSN_MOBILIZE_LAST_EXECUTION'))
		{
			return;
		}
		if (!self::$_app->isAdmin() && self::$_device == "mobilize" && ($device = $deviceType) == 'desktop')
		{
			// Alter and/or optimize response body based on detected client device type
			self::_finalizeResponse();
		}
		elseif (self::$_device == 'desktop' && ($device = $deviceType) != 'desktop')
		{
			// Inject UI switcher link if visitor viewing desktop site on either mobile device or tablet PC
			self::_injectUISwitcher($device);
		}
		if (self::$_device != 'desktop')
		{
			self::Optimize();
		}
	}

	/**
	 * Optimize js,css,images.
	 *
	 * @param   string  $html  Response body generated by Joomla.
	 *
	 * @return  void
	 */
	private static function Optimize($html = '')
	{
		// Initialize response body
		!empty($html) OR $html = JResponse::getBody();

		$session = JFactory::getSession();
		$profile = $session->get('jsn_mobilize_profile');
		$profileMinify = !empty($profile->profile_minify) ? $profile->profile_minify : '';
		$profileOptimizeImages = !empty($profile->profile_optimize_images) ? $profile->profile_optimize_images : '';
		// Minify stylesheets and Javascript files
		if ($profileMinify != '')
		{
			// Load library to minify assets
			require_once dirname(dirname(__FILE__)) . '/jsnmobilize/libraries/joomlashine/compress/helper.php';
			// Minify stylesheets
			if (strpos('css + both', $profileMinify) !== false)
			{
				require_once dirname(dirname(__FILE__)) . '/jsnmobilize/libraries/joomlashine/compress/css.php';
				$html = preg_replace_callback(
					'/(<link([^>]+)rel=["|\']stylesheet["|\']([^>]*)>\s*)+/i', array(
						'JSNMobilizeCompressCss',
						'compress'
					), $html
				);
			}

			// Minify Javascript files
			if (strpos('js + both', $profileMinify) !== false)
			{
				require_once dirname(dirname(__FILE__)) . '/jsnmobilize/libraries/joomlashine/compress/js.php';
				$html = preg_replace_callback(
					'/(<script([^>]+)src=["|\']([^"|\']+)["|\']([^>]*)>\s*<\/script>\s*)+/i', array(
						'JSNMobilizeCompressJs',
						'compress'
					), $html
				);
			}
		}
		$detect = new Mobile_Detect;
		// Optimize image files
		if ($detect->isMobile() && !$detect->isTablet() && $profileOptimizeImages != '')
		{
			// Load library to optimize image files
			require_once dirname(dirname(__FILE__)) . '/jsnmobilize/libraries/joomlashine/response/image.php';

			// Initialize image file optimization
			$html = JSNResponseImage::init(JSN_MOBILIZE_PATH_OPTIMIZED_IMAGE, (int) $profileOptimizeImages, $html, false);
		}
		// Set manipulated HTML code
		JResponse::setBody($html);
	}

	/**
	 * Finalize response body for rendering mobile/tablet UI.
	 *
	 * @param   string  $html  Response body generated by Joomla.
	 *
	 * @return  void
	 */
	private static function _finalizeResponse($html = '')
	{
		// Initialize response body
		!empty($html) OR $html = JResponse::getBody();

		// Build regular expression to parse response
		$regEx = '#<(a|form|img)[^>]*(href|action|src)=("|\')(' . JURI::root() . '|' . JURI::root(true) . ')*([^\s]*)("|\')[^>]*>#i';

		// Get all a and form tag from responce
		if (preg_match_all($regEx, $html, $matches, PREG_SET_ORDER))
		{

			// var_dump($matches);
			foreach ($matches AS $match)
			{
				if (strpos($match[0], ' id="jsn-mobilize-ui-switcher"') === false)
				{
					// Check if this is a direct link
					if (!empty($match[5]) && $match[5] != '/' && strpos($match[5], '/index.php') !== 0 && (is_readable(JPATH_ROOT . $match[5]) || (($pos = strpos($match[5], '?')) !== false AND is_readable(JPATH_ROOT . substr($match[5], 0, $pos)))))
					{
						continue;
					}
					// Build mobile/tablet URI
					if ($match[1] != 'img')
					{
						if (substr($link = self::$_cfg->get('link_' . self::$_device), 0, 1) == '/')
						{
							$uri = str_replace(JURI::root(true), '/' . trim($link, '/'), $match[4]);
						}
						else
						{
							// Get parsed request URI object
							$jUri = JURI::getInstance();

							if (preg_match('/^https?:/i', $match[4]))
							{
								$uri = str_replace($jUri->getHost(), $link, $match[4]);
							}
							elseif (substr($match[4], 0, 1) == '/')
							{
								$uri = ($uri = $jUri->getScheme()) . (empty($uri) ? '' : '://') . $link . $match[4];
							}
						}
						// Finalize link
						$uri .= str_replace('/index.php', '', $match[5]);
					}
					else
					{
						if (!preg_match('/^https?:/', $match[5]))
						{
							$uri = JURI::root(true) . '/' . $match[5];
						}
						else
						{
							$uri = $match[5];
						}
					}
					// Create replacement
					$replace = str_replace($match[4] . $match[5], $uri, $match[0]);

					// Replace original link with link for mobile/tablet site
					$html = str_replace($match[0], $replace, $html);
				}
			}
		}
		// Set manipulated HTML code
		JResponse::setBody($html);
	}

	/**
	 * Inject UI switcher into default desktop template.
	 *
	 * @param   string  $device  Detected client device type.
	 * @param   string  $html    Response body generated by Joomla.
	 *
	 * @return  void
	 */
	private static function _injectUISwitcher($device, $html = '')
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_system_jsnmobilize', JPATH_ADMINISTRATOR);
		$detect = new Mobile_Detect;
		$textSwitcher = '';
		if ($detect->isMobile() && !$detect->isTablet())
		{
			$textSwitcher = 'JSN_MOBILIZE_SWITCH_TO_WEB_UI_FOR_MOBILE';
		}
		if ($detect->isMobile() && $detect->isTablet())
		{
			$textSwitcher = 'JSN_MOBILIZE_SWITCH_TO_WEB_UI_FOR_TABLET';
		}
		// Initialize response body
		!empty($html) OR $html = JResponse::getBody();

		// Get parsed request URI object
		$jUri = JURI::getInstance();

		// Build URI for switching back to mobile/tablet site
		if (substr($link = self::$_cfg->get("link_{$device}"), 0, 1) == '/')
		{
			$switch = str_replace(JURI::root(true), '/' . trim($link, '/'), $jUri->toString());
		}
		else
		{
			$switch = str_replace($jUri->getHost(), $link, $jUri->toString());
		}

		$switch = preg_replace('/(\?|&)switch_to_desktop_ui=1/', '', $switch);

		// Get user selected style
		$style = self::$_cfg->get('style', 'default');
		// Inject UI switcher assets
		$html = str_replace('</head>', "\t" . '<link media="screen" type="text/css" href="' . JURI::root(true) . '/templates/jsn_mobilize/css/switcher.css" rel="stylesheet" />' . "\n</head>", $html);
		// Inject UI switcher link
		$html = str_replace('</body>', "\t" . '<div class="mobilize-ui-switcher"><a id="jsn-mobilize-ui-switcher" class="btn" href="' . JURI::root() . '?switch_to_desktop_ui=2" title="' . JText::_($textSwitcher) . '">' . JText::_($textSwitcher) . '</a></div>' . "\n</body>", $html);

		// Set manipulated HTML code
		JResponse::setBody($html);
	}

	/**
	 * Load plugin language.
	 *
	 * @return  void
	 */
	private function _loadLanguage()
	{
		// Get active language
		$language = JFactory::getLanguage();

		// Check if language file exists for active language
		if (!file_exists(JPATH_ROOT . '/administrator/language/' . $language->getDefault() . '/' . $language->getDefault() . '.plg_system_jsnmobilize.ini'))
		{
			// If requested component has the language file, install then load it
			if (file_exists(JPATH_ROOT . '/administrator/components/' . self::$_app->input->getCmd('option') . '/language/admin/' . $language->getDefault() . '/' . $language->getDefault() . '.plg_system_jsnmobilize.ini'))
			{
				JSNLanguageHelper::install((array) $language->getDefault(), false, true);
				$language->load('plg_system_jsnmobilize', JPATH_BASE, null, true);
			}
			// Otherwise, try to load language file from plugin directory
			else
			{
				$language->load('plg_system_jsnmobilize', dirname(__FILE__), null, true);
			}
		}
		else
		{
			$language->load('plg_system_jsnmobilize', JPATH_BASE, null, true);
		}
	}

}

/**
 * Finalize response body.
 *
 * @return  void
 */
function jsnMobilizeFinalize()
{
	define('JSN_MOBILIZE_LAST_EXECUTION', 1);
	PlgSystemJSNMobilize::onAfterRender();
}
