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

class PCMailer_Send extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Process Send Queue'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
            set_time_limit( 0 );

            //  Output demo content to screen
            $campaigns = PCMailer_Campaign::getInstance()->select( null, array( 'status' => 1 ) );
               //     var_export( $campaigns );
            if( empty( $campaigns ) )
            {
                $this->setViewContent( '<div class="badnews">No campaign is on queue for sending...</div>' ); 
                return false;
            }

      //      $done = array();
            $contactTable = PCMailer_Contact::getInstance();
            $sendingLimitPerRun = Application_Settings_Abstract::getSettings( 'pcmailer', 'send_limit_per_run' ) ? : 100;
	//      $lastRun = Application_Settings_Abstract::getSettings( 'paystack_inline_embed', 'currency' );

		    $storage = self::getObjectStorage( array( 'id' => $postListId, 'device' => 'File', 'time_out' => 44600, ) );
            if( ! $runTimeSettings = $storage->retrieve() )
            {
                $runTimeSettings = array();
            }
            
            $delayBeforeNextRun = Application_Settings_Abstract::getSettings( 'pcmailer', 'send_queue_delay' ) ? : 3600;
            $currentTime = time();
            $timeFilter = new Ayoola_Filter_Time();
            if( ! empty( $runTimeSettings['last_runtime'] ) && ( $currentTime - $runTimeSettings['last_runtime'] < $delayBeforeNextRun ) )
            {
            //    var_export( $delayBeforeNextRun . '<br>' );
            //    var_export( time() - $runTimeSettings['last_runtime'] );
                $timeToGo = ( $delayBeforeNextRun - ( $currentTime - $runTimeSettings['last_runtime'] ) );
                $this->setViewContent( '<div class="badnews">' . ( $timeFilter->filter( $timeToGo + $currentTime ) ) . ' until the next sending...</div>' );
                return false;
            }
            $runCount = 0;
            $runTimeSettings['last_runtime'] = $currentTime;
            $storage->store( $runTimeSettings );
            foreach( $campaigns as $campaign )
            {
              //  $campaign = 
                $contacts = $contactTable->select( null, array( 'list_id' => $campaign['list_id'] ) );
                $campaign['sent'] = is_array( $campaign['sent'] ) ? $campaign['sent'] : array();
        //        $done += $campaign['sent'];
       //         $done = array_unique( $done );
                $campaign['body'] = Ayoola_Page_Editor_Text::addDomainToAbsoluteLinks( $campaign['body'] );
 //       var_export( $campaign['body'] );
                $count = 0;
                //  no of emails per run 
                foreach( $contacts as $contact )
                {
                    if( ! empty( $campaign['sent'] ) && is_array( $campaign['sent'] ) && in_array( $contact['contact_id'], $campaign['sent'] ) )
                    {
                        continue;
                    }
                    $runCount++;
                    $campaign['body'] = self::replacePlaceholders( $campaign['body'], $contact );
                    $campaign['subject'] = self::replacePlaceholders( $campaign['subject'], $contact );
                    $contact['to'] = $contact['email'];
                    if( $name = trim( $contact['firstname'] . ' ' . $contact['lastname'] ) )
                    {
                        $contact['to'] = '"' . $name . '" <' . $contact['email'] . '>';
                    }
                    $campaign['to'] = $contact['to'];
               //     var_export( $campaign['sent'] );
                    $campaign['sent'][] = $contact['contact_id'];
           //         var_export( $contact );
                    if( $runCoun >= $sendingLimitPerRun )
                    {
                        break 2;
                    }
                    sleep( rand( 1, 5 ) );
                    PCMailer_Campaign::getInstance()->update( $campaign, array( 'campaign_id' => $campaign['campaign_id'] ) );
                    self::sendMail( $campaign );
                    $count++;
                }
                if( count( $campaign['sent'] ) === count( $contacts ) )
                {
                    $campaign['status'] = '2';
                    PCMailer_Campaign::getInstance()->update( $campaign, array( 'campaign_id' => $campaign['campaign_id'] ) );
                }
            //    $runCount += $count;
                $this->setViewContent( '<div class="goodnews">"' . $campaign['subject'] . '" sent to ' . $count . ' recipent(s).</div>' );
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
        //    $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	// END OF CLASS
}
