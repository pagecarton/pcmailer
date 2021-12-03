<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Send
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Send.php Sunday 17th of June 2018 07:18PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Preview extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Preview Campaign'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try 
		{ 

            //  Output demo content to screen
            $campaign = PCMailer_Campaign::getInstance()->selectOne( null, array( 'campaign_id' => $_REQUEST['campaign_id'] ) );
            if( empty( $campaign ) )
            {
                $this->setViewContent( '<div class="badnews">Invalid Campaign Selected</div>' ); 
                return false;
            }

            if( strip_tags( $campaign['body'] ) !== $campaign['body'] )
            {
                $campaign['body'] = Ayoola_Page_Editor_Text::embedWidget( $campaign['body'], array() );
            }
            else
            {
                $campaign['body'] = nl2br( $campaign['body'] );
            }
            $this->setViewContent( $campaign['body'] ); 
          

            // end of widget process
          
		}  
		catch( Exception $e )
        { 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	// END OF CLASS
}
