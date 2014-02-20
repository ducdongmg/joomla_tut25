<?php
/**
 * @version     $Id: index.php 19770 2012-12-28 08:26:19Z thailv $
 * @package     JSN_Mobilize
 * @subpackage  Template
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
error_reporting(0);
// Include helper class
require_once JPATH_ROOT . '/administrator/components/com_mobilize/mobilize.defines.php';
require_once dirname(__FILE__) . '/helpers/mobilize.php';
require_once JPATH_ROOT . '/administrator/components/com_mobilize/helpers/mobilize.php';

// Initialize variables
$app = JFactory::getApplication();
$jCfg = JFactory::getConfig();
$device = $app->input->getCmd('_device');
$mCfg = JSNMobilizeTemplateHelper::getConfig($device, $app->input->getInt('_preview'));
$switch = $app->input->getVar('_request') . (strpos($app->input->getVar('_request'), '?') === false ? '?' : '&') . 'switch_to_desktop_ui=1';
$preview = $app->input->getInt('_preview');
// Get user selected style
if (!empty($mCfg))
{
	$cssFile = $mCfg->get('css-file') ? $mCfg->get('css-file') : "";
	$cookieStyle = $mCfg->get('profile-style') ? $mCfg->get('profile-style') : "";
	// Load header for HTML document
	require_once dirname(__FILE__) . '/tmpl/header_mobilize.php';
	?>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=10">
	<jdoc:include type="head" />
	<?php
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::root() . '/templates/jsn_mobilize/css/bootstrap.min.css');
	$document->addStyleSheet(JURI::root() . '/templates/jsn_mobilize/css/css/bootstrap-responsive.min.css');
	$document->addStyleSheet(JURI::root() . '/templates/jsn_mobilize/css/template.css');
	if (!$cookieStyle)
	{
		if ($cssFile && JFolder::exists(JPath::clean(JPATH_ROOT . "/templates/jsn_mobilize/css/profiles" . $cssFile)))
		{
			$document->addStyleSheet(JURI::root() . '/templates/jsn_mobilize/css/profiles/' . $cssFile);
		}
		else
		{
			$css = $mCfg->get('css') ? $mCfg->get('css') : "";
			$document->addStyleDeclaration($css);
		}
	}
	else
	{
		$css = JSNMobilizeHelper::generateStyle($cookieStyle);
		$css = str_replace("\n", "", $css);
		$document->addStyleDeclaration($css);
	}
	// Load scripts
	$document->addScript(JURI::root() . '/plugins/system/jsnframework/assets/3rd-party/jquery/jquery-1.8.2.js');
	$document->addScript(JURI::root() . '/templates/jsn_mobilize/js/mobilize.js');
	$logoAlignment = $mCfg->get('logo-alignment');
	?>
</head>
<body id="jsn-master" class="<?php echo $app->input->getInt('_preview') ? ' jsn-mobilize-preview' : ''; ?> jsn-preview-<?php echo $device; ?>">
<div id="jsn-page" class="jsn-mobile-layout">
	<div id="jsn-header">
		<div id="jsn-menu">
			<div class="row-fluid">
				<?php
				if ((int) $mCfg->get('menu'))
				{
					?>
					<ul class="mobilize-menu nav nav-pills jsn-mainmenu">
						<?php
						// Get module ID
						$id = array_keys($mCfg->get('menu'));
						$id = array_pop($id);

						// Get link status
						$status = array_values($mCfg->get('menu'));
						$status = array_pop($status);
						if ($status)
						{
							?>
							<li class="dropdown">
								<span class="jsn-menu-toggle"><i class="icon-th-list"></i></span>
								<?php JSNMobilizeTemplateHelper::renderMenu((int) $id); ?>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}
				if ($mCfg->get('search') || $mCfg->get('login'))
				{
					?>
					<ul class="mobilize-menu nav nav-pills jsn-sidetool">
						<?php
						foreach (array('search', 'login') AS $type)
						{
							// Get module ID
							$configMenu = $mCfg->get($type);
							if (!empty($configMenu))
							{
								$id = array_keys($configMenu);
								$id = array_pop($id);
								// Get link status
								$status = array_values($configMenu);
								$status = array_pop($status);
								$contentMenu = JSNMobilizeTemplateHelper::renderModule((int) $id, array(), true, true);
								if ($status && !empty($contentMenu))
								{
									?>
									<li class="dropdown">
										<?php
										if ($type == "login")
										{
											$type = "user";
										}
										?>
										<span class="jsn-menu-toggle"><i class="icon-<?php echo strtolower($type);?>"></i></span>

										<div class="jsn-tool">
											<?php echo $contentMenu;?>
										</div>
									</li>
									<?php
								}
							}
						}
						?>
					</ul>
					<?php
				}
				?>
				<div class="clearbreak"></div>
			</div>
		</div>
		<?php
	if ($mCfg->get('logo'))
	{
		?>
		<div id="jsn-logo" class="jsn-text-<?php echo $logoAlignment ? $logoAlignment : "left"; ?>">
			<?php
			$urlLogo = JURI::root(true);
				// Get logo link
				$link = array_keys($mCfg->get('logo'));
				$link = array_pop($link);
				// Get logo alternative text
				$alt = array_values($mCfg->get('logo'));
				$alt = array_pop($alt);
				if ($hasLogo = is_readable(JPATH_ROOT . DS . $link))
				{
					?>
					<a href="<?php echo JURI::root(true); ?>" title="<?php echo JText::_($alt); ?>"><img src="<?php echo JURI::root() . $link; ?>" alt="<?php echo JText::_($alt); ?>" /></a>
					<?php
				}
			?>
		</div>
<?php
	}
?>
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('mobile-tool', $device, $preview, 'jsn-mobile-tool'); ?>
	</div>
	<div id="jsn-body">
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('content-top', $device, $preview, 'jsn-content-top'); ?>
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('user-top', $device, $preview, 'jsn-user-top'); ?>
		<div id="jsn-mainbody">
			<jdoc:include type="component" />
		</div>
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('user-bottom', $device, $preview, 'jsn-user-bottom'); ?>
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('side-content', $device, $preview, 'jsn-side-content'); ?>
		<?php JSNMobilizeTemplateHelper::renderHtmlBlock('content-bottom', $device, $preview, 'jsn-content-bottom'); ?>
	</div>
	<?php JSNMobilizeTemplateHelper::renderHtmlBlock('footer', $device, $preview, 'jsn-footer');    ?>
	<?php
	$switcher = $mCfg->get('switcher');
	if (!empty($switcher))
	{
		foreach ($switcher as $key => $value)
		{
			if ($value == 1)
			{
				?>
				<div id="jsn-switcher" class="jsn-mobilize-ui-switcher jsn-text-center">
					<button id="jsn-mobilize-ui-switcher" class="btn btn-primary" onclick="window.location.href='<?php echo $switch;?>'" type="button"><?php echo $key;?></button>
				</div>
				<?php
				break;
			}
			else
			{
				break;
			}
		}
	}
	?>
</div>
	<?php
	$edition = defined('JSN_MOBILIZE_EDITION') ? JSN_MOBILIZE_EDITION : "free";
	if (strtolower($edition) == "free")
	{
		echo "<div class=\"jsn-text-center\"><a href=\"http://www.joomlashine.com/joomla-extensions/jsn-mobilize.html\" target=\"_blank\">Mobile Joomla Display</a> by <a href=\"http://www.joomlashine.com\" target=\"_blank\">JoomlaShine</a></div>";
	}
	?>
</body>
</html>
<?php
}
