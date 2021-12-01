<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Contact_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 17th of June 2018 12:44PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PCMailer_Contact_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'contact_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'contact_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PCMailer_Contact';
	
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

		$fieldset = new Ayoola_Form_Element;
        $fieldset->addElement( array( 'name' => 'title', 'type' => 'InputText', 'value' => @$values['title'] ) );
        $fieldset->addElement( array( 'name' => 'firstname', 'type' => 'InputText', 'value' => @$values['firstname'] ) ); 
        $fieldset->addElement( array( 'name' => 'lastname', 'type' => 'InputText', 'value' => @$values['lastname'] ) ); 
        $fieldset->addElement( array( 'name' => 'email', 'type' => 'InputText', 'value' => @$values['email'] ) );
        $fieldset->addElement( array( 'name' => 'website', 'type' => 'InputText', 'value' => @$values['website'] ) );
        $fieldset->addElement( array( 'name' => 'company_name', 'type' => 'InputText', 'value' => @$values['company_name'] ) );
        $fieldset->addElement( array( 'name' => 'industry', 'type' => 'InputText', 'value' => @$values['industry'] ) );

        $filter = new Ayoola_Filter_SelectListArray( 'list_id', 'list_title');
        $options = PCMailer_List::getInstance();
        $options = $options->select();
        $options = $filter->filter( $options );

        $options ? $fieldset->addElement( array( 'name' => 'list_id', 'type' => 'SelectMultiple', 'value' => @$values['list_id'] ), $options ) : null; 

        if( $values )
        {
            $fieldset->addElement( array( 'name' => 'enabled', 'type' => 'InputText', 'value' => @$values['enabled'] ) ); 
            $fieldset->addElement( array( 'name' => 'verified', 'type' => 'InputText', 'value' => @$values['verified'] ) ); 
            $fieldset->addElement( array( 'name' => 'confirmed', 'type' => 'InputText', 'value' => @$values['confirmed'] ) ); 
            $fieldset->addElement( array( 'name' => 'unsubscribed', 'type' => 'InputText', 'value' => @$values['unsubscribed'] ) ); 
            $fieldset->addElement( array( 'name' => 'bounced', 'type' => 'InputText', 'value' => @$values['bounced'] ) ); 
        }

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
