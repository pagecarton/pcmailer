<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Campaign_List extends PCMailer_Campaign_Abstract
{
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
      $this->setViewContent( $this->getList() );  		
    }
	
    /**
     * Paginate the list with Ayoola_Paginator
     * @see Ayoola_Paginator
     */
    protected function createList()
    {
		require_once 'Ayoola/Paginator.php';
		$list = new Ayoola_Paginator();
		$list->pageName = $this->getObjectName();
		$list->listTitle = self::getObjectTitle();
		$list->setData( $this->getDbData() );
		$list->setListOptions( 
								array( 
										'Creator' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Campaign_Creator/\', \'page_refresh\' );"  href="javascript:">Create a new Campaign</a>',    
										'List' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_List_List/\', \'\' );"  href="javascript:">Contact Category List</a>',    
										'Contact' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Contact_List/\', \'\' );"  href="javascript:">Contacts</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No data added to this table yet.' );
		
		$list->createList
		(
			array(
                    //'from' => array( 'field' => 'from', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'subject' => array( 'field' => 'subject', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    array( 'field' => 'sent_count', 'value' =>  '%FIELD% sent' ), 
                    array( 'field' => 'contact_count', 'value' =>  '%FIELD% contacts' ), 
                    'Status' => array( 'field' => 'status', 'value' =>  '%FIELD%', 'filter' =>  '', 'value_representation' =>  static::$_availableStatuses ), 
                    array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Campaign_Editor/?' . $this->getIdColumn() . '=%KEY%\', \'page_refresh\' );" href="javascript:">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>',
                    '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Campaign_Delete/?' . $this->getIdColumn() . '=%KEY%\', \'page_refresh\' );"  href="javascript:"><i class="fa fa-trash" aria-hidden="true"></i></a>',
				)
		);
		return $list;
    } 
	// END OF CLASS
}
