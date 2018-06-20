<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Contact_List
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: List.php Wednesday 20th of December 2017 03:21PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Contact_List extends PCMailer_Contact_Abstract
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
										'Importer' => '<a rel="spotlight;" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Contact_Importer/\', \'page_refresh\' );" title="">Import CSV</a>',    
									) 
							);
		$list->setKey( $this->getIdColumn() );
		$list->setNoRecordMessage( 'No data added to this table yet.' );
		
		$list->createList
		(
			array(
                    'firstname' => array( 'field' => 'firstname', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'lastname' => array( 'field' => 'lastname', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'email' => array( 'field' => 'email', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'enabled' => array( 'field' => 'enabled', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'verified' => array( 'field' => 'verified', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'confirmed' => array( 'field' => 'confirmed', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'list_id' => array( 'field' => 'list_id', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'unsubscribed' => array( 'field' => 'unsubscribed', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'bounced' => array( 'field' => 'bounced', 'value' =>  '%FIELD%', 'filter' =>  '' ), 
                    'Added' => array( 'field' => 'creation_time', 'value' =>  '%FIELD%', 'filter' =>  'Ayoola_Filter_Time' ), 
                    '' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Contact_Editor/?' . $this->getIdColumn() . '=%KEY%">edit</a>', 
                    ' ' => '%FIELD% <a style="font-size:smaller;" rel="shadowbox;changeElementId=' . $this->getObjectName() . '" href="' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/PCMailer_Contact_Delete/?' . $this->getIdColumn() . '=%KEY%">x</a>', 
				)
		);
		return $list;
    } 
	// END OF CLASS
}
