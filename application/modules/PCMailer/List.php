<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_List
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Sunday 17th of June 2018 12:39PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PCMailer_List extends PageCarton_Table_Private
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.2';    

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'list_title' => 'INPUTTEXT',
  'products' => 'JSON',
  'forms' => 'JSON',
  'include_options' => 'JSON',
);


	// END OF CLASS
}
