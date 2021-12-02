<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Table_Sample
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Settings.php Monday 18th of June 2018 02:55PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Table
 */


class PCMailer_Settings extends PageCarton_Settings
{


	
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		if( ! $settings = unserialize( @$values['settings'] ) )
		{
			if( is_array( $values['data'] ) )
			{
				$settings = $values['data'];
			}
			elseif( is_array( $values['settings'] ) )
			{
				$settings = $values['settings'];
			}
			else
			{
				$settings = $values;
			}
		}        
        
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$form->submitValue = $submitValue ;
		$form->oneFieldSetAtATime = true;
		$fieldset = new Ayoola_Form_Element;



        //  Sample Text Field Retrieving E-mail Address
		$fieldset->addElement( array( 'name' => 'send_queue_delay', 'label' => 'Send Queue Delay (secs)', 'placeholder' => 'e.g. 3600', 'value' => @$settings['send_queue_delay'], 'type' => 'InputText' ) );
		$fieldset->addElement( array( 'name' => 'send_limit_per_run', 'label' => 'Send Limit Per Run', 'placeholder' => 'e.g. 100', 'value' => @$settings['send_limit_per_run'], 'type' => 'InputText' ) );
		
		$fieldset->addLegend( 'PCMailer Plugin Settings' ); 
               
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS
}
