<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Contacts_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 17th of June 2018 12:44PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PCMailer_Contacts_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'contacts_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'contacts_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Contacts';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );


    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = false;       
        $fieldset->addElement( array( 'name' => 'firstname', 'type' => 'InputText', 'value' => @$values['firstname'] ) ); 
        $fieldset->addElement( array( 'name' => 'lastname', 'type' => 'InputText', 'value' => @$values['lastname'] ) ); 
        $fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) ); 
        $fieldset->addElement( array( 'name' => 'enabled', 'type' => 'InputText', 'value' => @$values['enabled'] ) ); 
        $fieldset->addElement( array( 'name' => 'verified', 'type' => 'InputText', 'value' => @$values['verified'] ) ); 
        $fieldset->addElement( array( 'name' => 'confirmed', 'type' => 'InputText', 'value' => @$values['confirmed'] ) ); 
        $fieldset->addElement( array( 'name' => 'lists_id', 'type' => 'InputText', 'value' => @$values['lists_id'] ) ); 
        $fieldset->addElement( array( 'name' => 'unsubscribed', 'type' => 'InputText', 'value' => @$values['unsubscribed'] ) ); 
        $fieldset->addElement( array( 'name' => 'bounced', 'type' => 'InputText', 'value' => @$values['bounced'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
