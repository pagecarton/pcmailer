<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign_Delete
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Campaign_Delete extends PCMailer_Campaign_Abstract
{

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createConfirmationForm( 'Delete Campaign', 'Delete' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            
			if( $this->deleteDb() ){ $this->setViewContent( '<div class="goodnews">Data deleted successfully</div>', true ); } 

            //	Notify Admin
            $mailInfo = array();
            $mailInfo['subject'] = 'Campaign Deleted';
            $mailInfo['body'] = 'An email Campaign "' . $data['subject'] . '" has been deleted';
            try
            {
                @Ayoola_Application_Notification::mail( $mailInfo );
            }
            catch( Ayoola_Exception $e ){ null; }                

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
