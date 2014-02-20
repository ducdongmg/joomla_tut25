<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if (count($children[$category->id]) > 0 && $data->maxLevel != 0) :
?>
<ul>
<?php foreach($children[$category->id] as $id => $child) : ?>
	<?php
	if($child->numitems || count($child->getChildren())) :
	if(!isset($children[$category->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	?>
	<li>
		<?php $class = ''; ?>
			<span class="item-title"><a href="javsacript:void(0)">
				<?php echo htmlspecialchars($child->title); ?></a>
			</span>


			<?php $showSubcatDesc = $params->get('show_subcat_desc') == 1 ? 'display-default display-item' : 'hide-item'; ?>
			<?php if ($child->description) : ?>
				<div parname="show_subcat_desc" id="show_subcat_desc" class="item-position element-switch contextmenu-approved <?php echo $showSubcatDesc;?>" >
					<?php echo JSNLayoutHelper::fixImageLinks(htmlspecialchars($child->description)); ?>
				</div>
			<?php endif; ?>



            <?php $showCatItems = $params->get('show_cat_items') == 1 ? 'display-default display-item' : 'hide-item'; ?>
			<div parname="show_cat_items" id="show_cat_items" class="item-position element-switch contextmenu-approved <?php echo $showCatItems;?>" >
				<?php echo JText::_('COM_CONTACT_CAT_NUM'); ?>
				<?php echo $child->numitems; ?>
			</div>

            <?php if(count($child->getChildren()) > 0 ) :
				$children[$child->id] = $child->getChildren();
				$category = $child;
				$data->maxLevel--;
				include JPATH_ROOT . '/plugins/jsnpoweradmin/contact/views/category/default_children.php';
				$category = $child->getParent();
				$data->maxLevel++;
			endif; ?>
	</li>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endif;
