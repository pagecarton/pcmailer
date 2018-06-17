<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Contacts
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Contacts.php Sunday 17th of June 2018 12:44PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PCMailer_Contacts extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.0';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'firstname' => 'INPUTTEXT',
  'lastname' => 'INPUTTEXT',
  'email' => 'INPUTTEXT',
  'enabled' => 'INT',
  'verified' => 'INT',
  'confirmed' => 'INPUTTEXT',
  'lists_id' => 'JSON',
  'unsubscribed' => 'INT',
  'bounced' => 'INT',
);


	// END OF CLASS
}
