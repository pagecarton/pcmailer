<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign_Editor
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Campaign_Editor extends PCMailer_Campaign_Abstract
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
			$this->createForm( 'Save', 'Edit Campaign', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }

            if( $data['status'] != $values['status'] )
            {
                $values['sent'] = array();
                $values['contacts'] = array();
            }
            //	Notify Admin
            $mailInfo = array();
            $mailInfo['subject'] = 'Campaign Updated';
            $mailInfo['body'] = 'An email Campaign "' . $values['subject'] . '" has been updated with the following information : "' . htmlspecialchars_decode( var_export( $values, true ) ) . '". 
            
            ';
            try
            {
                @Ayoola_Application_Notification::mail( $mailInfo );
            }
            catch( Ayoola_Exception $e ){ null; }                //  status changed, reset the sent list

			if( $this->updateDb( $values ) ){ $this->setViewContent( '<div class="goodnews">Data updated successfully</div>', true ); } 

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
