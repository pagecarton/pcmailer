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
	public static function getContacts( $listId )
    {
        $contacts = PCMailer_Contact::getInstance()->select( null, array( 'list_id' => $listId ) );

        $emails = array();

        $lists = PCMailer_List::getInstance()->select( null, array( 'list_id' => $listId ) );
        foreach( $lists as $listInfo )
        {
            if( ! empty( $listInfo['products'] ) )
            {
                $emailX = Application_Subscription_Checkout_Order::getInstance()->select( 'email', array( 'article_url' => $listInfo['products'] ) );
                $emails = array_merge( $emails, $emailX );
    
            }
            if( ! empty( $listInfo['include_options'] ) )
            {
                if( in_array( 'purchaser', $listInfo['include_options'] ) )
                {
                    $emailX = Application_Subscription_Checkout_Order::getInstance()->select( 'email' );
                    $emails = array_merge( $emails, $emailX );
    
                }
                if( in_array( 'mailing-list', $listInfo['include_options'] ) )
                {
                    $emailX = Application_User_UserEmail_MailingList::getInstance()->select( 'email' );
                    $emails = array_merge( $emails, $emailX );
    
                }
                if( in_array( 'user-accounts', $listInfo['include_options'] ) )
                {
                    $emailX = Ayoola_Access_LocalUser::getInstance()->select( 'email' );
                    $emails = array_merge( $emails, $emailX );
    
                }
            }
            if( ! empty( $listInfo['forms'] ) )
            {
                if( $data = Ayoola_Form_Table_Data::getInstance()->select( null, array( 'form_name' => $listInfo['forms'] ) ) )
                {
                    foreach( $data as $each )
                    {
                        $email = $each['email'] ? : $each['email_address'];
                        if( ! empty( $email ) )
                        {
                            $emails[] = $email; 
                        }
                    }
                }
            }
        }

        $emails = array_unique( array_map( 'strtolower', $emails ) );
        $contacts = array_merge( $emails, $contacts );

        return $contacts;
    }

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
            if( empty( $campaigns ) )
            {
                $this->setViewContent( '<div class="badnews">No campaign is on queue for sending...</div>' ); 
                return false;
            }

            $sendingLimitPerRun = Application_Settings_Abstract::getSettings( 'pcmailer', 'send_limit_per_run' ) ? : 100;

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
                $timeToGo = ( $delayBeforeNextRun - ( $currentTime - $runTimeSettings['last_runtime'] ) );
                $this->setViewContent( '<div class="badnews">' . ( $timeFilter->filter( $timeToGo + $currentTime ) ) . ' until the next sending...</div>' );
                return false;
            }
            $runCount = 0;
            $runTimeSettings['last_runtime'] = $currentTime;
            $storage->store( $runTimeSettings );
            foreach( $campaigns as $campaign )
            {
                if( $campaign['send_time'] > time() )
                {
                    continue;
                }
                $contacts = $campaign['contacts'] = $campaign['contacts'] ? : self::getContacts( $campaign['list_id'] );

                $campaign['sent'] = is_array( $campaign['sent'] ) ? $campaign['sent'] : array();
                $campaign['body'] = Ayoola_Page_Editor_Text::addDomainToAbsoluteLinks( $campaign['body'] );
                $count = 0;

                //  no of emails per run 
                foreach( $contacts as $contact )
                {
                    if( $runCount >= $sendingLimitPerRun )
                    {
                        break;
                    }

                    if( is_string( $contact ) )
                    {
                        $contact = array( 'email' => $contact, 'contact_id' => $contact );
                    }

                    if( is_array( $contact ) )
                    {
                        $campaign['body'] = self::replacePlaceholders( $campaign['body'], $contact );
                        $campaign['subject'] = self::replacePlaceholders( $campaign['subject'], $contact );
                    }
                    $contact['to'] = $contact['email'];
                    if( ! empty( $campaign['sent'] ) && is_array( $campaign['sent'] ) && in_array( $contact['email'], $campaign['sent'] ) )
                    {
                        continue;
                    }

                    if( $name = @trim( $contact['firstname'] . ' ' . $contact['lastname'] ) )
                    {
                        $contact['to'] = '"' . $name . '" <' . $contact['email'] . '>';
                    }
                    $campaign['to'] = $contact['to'];
                    //sleep( rand( 1, 5 ) );
                    if( ! in_array( $contact['email'], $campaign['sent'] ) )
                    {
                        $runCount++;
                        $count++;
                        self::sendMail( $campaign );    
                    }
                    if( empty( $campaign['sent'] ) )
                    {
                        //	Notify Admin
                        $mailInfo = array();
                        $mailInfo['subject'] = 'Campaign Started';
                        $mailInfo['body'] = 'An email campaign "' . $campaign['subject'] . '" has started and would be sent to ' . count( $contacts ) . ' contacts';
                        try
                        {
                            @Ayoola_Application_Notification::mail( $mailInfo );
                        }
                        catch( Ayoola_Exception $e ){ null; }                

                    }

                    $campaign['sent'][] = $contact['email'];

                    if( count( $campaign['sent'] ) === count( $contacts ) )
                    {
                        $campaign['status'] = '2';
                        
                        //	Notify Admin
                        $mailInfo = array();
                        $mailInfo['subject'] = 'Campaign Completed';
                        $mailInfo['body'] = 'An email Campaign "' . $campaign['subject'] . '" has been sent successfully to ' . count( $campaign['sent'] ) . ' contacts';
                        try
                        {
                            @Ayoola_Application_Notification::mail( $mailInfo );
                        }
                        catch( Ayoola_Exception $e ){ null; }                

                    }
                    PCMailer_Campaign::getInstance()->update( $campaign, array( 'campaign_id' => $campaign['campaign_id'] ) );
                }

                $this->setViewContent( '<div class="goodnews">"' . $campaign['subject'] . '" sent to ' . $count . ' recipent(s).</div>' );
            }
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
