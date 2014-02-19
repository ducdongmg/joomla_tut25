<?php

/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * vi_VN localise class
 *
 * @package		Joomla.Site
 * @since		1.6
 */
abstract class vi_VNLocalise
{

  /**
   * Returns the potential suffixes for a specific number of items
   *
   * @param	int $count  The number of items.
   * @return	array  An array of potential suffixes.
   * @since	1.6
   */
  public static function getPluralSuffixes($count)
  {
    if ($count == 0)
    {
      $return = array('0');
    }
    elseif ($count == 1)
    {
      $return = array('1');
    }
    else
    {
      $return = array('MORE');
    }
    return $return;
  }

  /**
   * Returns the ignored search words
   *
   * @return	array  An array of ignored search words.
   * @since	1.6
   */
  public static function getIgnoredSearchWords()
  {
    $search_ignore = array();
    $search_ignore[] = "and";
    $search_ignore[] = "in";
    $search_ignore[] = "on";
    return $search_ignore;
  }

  /**
   * Returns the lower length limit of search words
   *
   * @return	integer  The lower length limit of search words.
   * @since	1.6
   */
  public static function getLowerLimitSearchWord()
  {
    return 3;
  }

  /**
   * Returns the upper length limit of search words
   *
   * @return	integer  The upper length limit of search words.
   * @since	1.6
   */
  public static function getUpperLimitSearchWord()
  {
    return 20;
  }

  /**
   * Returns the number of chars to display when searching
   *
   * @return	integer  The number of chars to display when searching.
   * @since	1.6
   */
  public static function getSearchDisplayedCharactersNumber()
  {
    return 200;
  }

  /**
   * 
   * @param type $string
   * @return type
   */
  public static function transliterate($string)
  {
    $str = JString::strtolower($string);

    //Specific language transliteration.
    //This one is for latin 1, latin supplement , extended A, Cyrillic, Greek

    $glyph_array = array(
        'a' => 'á,à,ả,ã,ạ,â,ấ,ầ,ẩ,ẫ,ậ,ă,ắ,ằ,ẳ,ẵ,ặ,Á,À,Ả,Ã,Ạ,Â,Ấ,Ầ,Ẩ,Ẫ,Ậ,Ă,Ắ,Ằ,Ẳ,Ẵ,Ặ',
        'd' => 'đ,Đ',
        'e' => 'é,è,ẻ,ẽ,ẹ,ê,ế,ề,ể,ễ,ệ,É,È,Ẻ,Ẽ,Ẹ,Ê,Ế,Ề,Ể,Ễ,Ệ',
        'i' => 'í,ì,ỉ,ĩ,ị,Í,Ì,Ỉ,Ĩ,Ị',
        'o' => 'ó,ò,ỏ,õ,ọ,ô,ố,ồ,ổ,ỗ,ộ,ơ,ớ,ờ,ở,ỡ,ợ,Ó,Ò,Ỏ,Õ,Ọ,Ô,Ố,Ồ,Ổ,Ỗ,Ộ,Ơ,Ớ,Ờ,Ở,Ỡ,Ợ',
        'u' => 'ú,ù,ủ,ũ,ụ,ư,ứ,ừ,ử,ữ,ự,Ú,Ù,Ủ,Ũ,Ụ,Ư,Ứ,Ừ,Ử,Ữ,Ự',
        'y' => 'ý,ỳ,ỷ,ỹ,ỵ,Ý,Ỳ,Ỷ,Ỹ,Ỵ'
    );

    foreach ($glyph_array as $letter => $glyphs)
    {
      $glyphs = explode(',', $glyphs);
      $str = str_replace($glyphs, $letter, $str);
    }

    return $str;
  }

}
