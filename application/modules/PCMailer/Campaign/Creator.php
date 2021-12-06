<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign_Creator
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php Wednesday 20th of December 2017 03:23PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Campaign_Creator extends PCMailer_Campaign_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Create new Campaign'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			$this->createForm( 'Submit...', 'Add new' );
			$this->setViewContent( $this->getForm()->view() );

			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//	Notify Admin
			$mailInfo = array();
			$mailInfo['subject'] = 'Campaign Created';
			$mailInfo['body'] = 'An email Campaign "' . $values['subject'] . '" has been created with the following information : "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
			
			';
			try
			{
				@Ayoola_Application_Notification::mail( $mailInfo );
			}
			catch( Ayoola_Exception $e ){ null; }

            $contacts = $values['contacts'] = PCMailer_Send::getContacts( $values['list_id'] );
            $values['contact_count'] = count( $contacts );

			if( $this->insertDb( $values ) )
			{ 
                $this->setViewContent( '
                <p class="goodnews">Campaign "' . $values['subject'] . '" created successfully to send to ' . count( $contacts ) . ' on the selected lists.</p>
                <p></p>
                ', true ); 
			}
          
		}  
		catch( Exception $e )
        { 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
