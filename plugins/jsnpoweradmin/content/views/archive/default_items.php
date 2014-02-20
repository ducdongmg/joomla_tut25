<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

?>

<ul id="archive-items">
<?php foreach ($data->items as $i => $item) : ?>
	<li class="row<?php echo $i % 2; ?>">

		<?php $linkTitle = $data->params->get('link_titles') ? 'show-link' : 'hide-link'; ?>
		<div id="intro-item-title-<?php echo $item->id?>" linkparam="link_titles"
			class="switchlink intro-item-title contextmenu-approved display-default display-item <?php echo $linkTitle?>">
			<?php if($data->params->get('link_titles')) {?>
			<h2><a href="javascript:void(0)"><?php echo htmlspecialchars(htmlentities($item->title)); ?></a></h2>
			<?php }else{?>
			<h2><?php echo htmlspecialchars(htmlentities($item->title)); ?></h2>
			<?php }?>
		</div>

		<dl class="article-info">
		<dt class="article-info-term">
			<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
		</dt>
		<?php $showParentCategory = $data->params->get('show_parent_category') ? 'display-default display-item' : 'hide-item'; ?>
		<?php $_canSwitchClass =  $item->parent_slug ? 'element-switch contextmenu-approved ' : ' ';  ?>
		<dd>
			<div id="parent-category-name-<?php echo $item->id?>" parname="show_parent_category"
				link="<?php echo $data->params->get('link_parent_category')?>" linkparam="link_parent_category"
				class="switchlink parent-category-name  <?php echo $_canSwitchClass . $showParentCategory?>" >
				<?php	$title = htmlspecialchars(htmlentities($item->parent_title));
						$url = '<a href="javascript:void(0)">'.$title.'</a>';?>
				<?php if($data->params->get('link_parent_category')) {?>
					<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php }else{?>
					<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
				<?php }?>
			</div>
		</dd>

		<?php $showCategory = $data->params->get('show_category') ? 'display-default display-item' : 'hide-item'; ?>
		<?php $_canSwitchClass =  $item->catslug ? 'element-switch contextmenu-approved ' : ' ';  ?>
		<dd>
			<div id="category-name-<?php echo $item->id?>"  parname="show_category" link="<?php echo $data->params->get('link_category')?>"
				linkparam="link_category"
				class="switchlink category-name <?php echo $_canSwitchClass . $showCategory?>" >

				<?php	$title = htmlspecialchars(htmlentities($item->category_title));
						$url = '<a href="javascript:void(0)">' . $title . '</a>'; ?>
				<?php if($data->params->get('link_category')) {?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php }else{?>
					<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
				<?php }?>
			</div>
		</dd>

		<?php $showCreateDate = $data->params->get('show_create_date') ? 'display-default display-item' : 'hide-item'; ?>
		<dd>
			<div id="created-date-<?php echo $item->id?>" parname="show_create_date" class="created element-switch contextmenu-approved  <?php echo $showCreateDate?>" >
			<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2'))); ?>
			</div>
		</dd>

		<?php $showModifyDate = $data->params->get('show_modify_date') ? 'display-default display-item' : 'hide-item'; ?>
		<dd>
			<div id="updated-date-<?php echo $item->id?>"  parname="show_modify_date" class="modified element-switch contextmenu-approved  <?php echo $showModifyDate?>" >
			<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
			</div>
		</dd>

		<?php $showPublishDate = $data->params->get('show_publish_date') ? 'display-default display-item' : 'hide-item'; ?>
		<dd>
			<div id="published-date-<?php echo $item->id?>"  parname="show_publish_date"
				class="published element-switch contextmenu-approved  <?php echo $showPublishDate?>" >
			<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
			</div>
		</dd>
	<?php if (!empty($item->author )) : ?>
		<?php $showAuthor = $data->params->get('show_author') ? 'display-default display-item' : 'hide-item'; ?>
		<?php $_canSwitchClass =  !empty($item->contactid ) ? 'element-switch contextmenu-approved ' : ' ';  ?>
		<dd>
			<div id="created-by-<?php echo $item->id?>"   parname="show_author"
				link="<?php echo $params->get('link_author')?>"
				linkparam="link_author"
				class="createdby switchlink  <?php echo $_canSwitchClass . $showAuthor?>" >
				<?php $author =  $item->author; ?>
				<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author);?>

					<?php if ($params->get('link_author') == true && !empty($item->contactid )):?>
						<?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
						 JHtml::_('link', 'javascript:void(0)', $author)); ?>
					<?php else :?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
					<?php endif; ?>
			</div>
		</dd>
	<?php endif; ?>

		<?php $showHits = $data->params->get('show_hits') ? 'display-default display-item' : 'hide-item'; ?>
		<dd>
		<div id="hits-<?php echo $item->id?>"  parname="show_hits" class="hits element-switch contextmenu-approved <?php echo $showHits?>">
		<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?>
		</div>
		</dd>
</dl>
	<div class="clearbreak">
		<?php if ($item->introtext) :?>
		<?php $showIntro = $data->params->get('show_intro') ? 'display-default display-item' : 'hide-item'; ?>
		<div id="description-<?php echo $item->id?>"  parname="show_intro" class="intro element-switch contextmenu-approved <?php echo $showIntro?>">
			<?php echo JHtml::_('string.truncate', $item->introtext, $params->get('introtext_limit')); ?>
		</div>
		<?php endif;?>
	</div>

</li>
<?php endforeach; ?>
</ul>

<div class="pagination">
	<p class="counter">
		<?php echo $data->pagination->getPagesCounter(); ?>
	</p>
	<?php echo $data->pagination->getPagesLinks(); ?>
</div>
