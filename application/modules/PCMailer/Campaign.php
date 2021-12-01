<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Campaign.php Sunday 17th of June 2018 02:10PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PCMailer_Campaign extends PageCarton_Table
{

    /**
     * The table version (SVN COMPATIBLE)
     *
     * @param string
     */
    protected $_tableVersion = '0.7';  

    /**
     * Table data types and declaration
     * array( 'fieldname' => 'DATATYPE' )
     *
     * @param array
     */
	protected $_dataTypes = array (
  'from' => 'INPUTTEXT',
  'subject' => 'INPUTTEXT',
  'contacts' => 'JSON',
  'sent' => 'JSON',
  'opened' => 'JSON',
  'clicked' => 'JSON',
  'status' => 'INT',
  'last_runtime' => 'INT',
  'unsubscribes' => 'JSON',
  'body' => 'TEXTAREA',
  'list_id' => 'JSON',
);


	// END OF CLASS
}
