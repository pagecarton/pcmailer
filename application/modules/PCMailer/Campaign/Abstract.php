<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Campaign_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 17th of June 2018 02:10PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PCMailer_Campaign_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'campaign_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'campaign_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PCMailer_Campaign';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_availableStatuses = array( 0 => 'Draft', 1 => 'Queue', 2 => 'Done' );


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

        $options = array( 'Plain Text', 'HTML Editor' );
        //$options = array_combine( array_values( $options ), array_values( $options ) );
        $fieldset->addElement( array( 'name' => 'type', 'type' => 'Select', 'value' => @$values['type'] ), $options ); 


        $fieldset->addElement( array( 'name' => 'from', 'placeholder' => 'e.g. email@example.com', 'type' => 'InputText', 'value' => @$values['from'] ) ); 

        $filter = new Ayoola_Filter_SelectListArray( 'list_id', 'list_title');
        $options = PCMailer_List::getInstance();
        $options = $options->select();
        //var_export( $options );
        //var_export( $filter->filter( $options ) );
        $options = array( '' => 'All Contacts' ) + $filter->filter( $options );

        $fieldset->addElement( array( 'name' => 'list_id', 'label' => 'To', 'multiple' => true, 'type' => 'SelectMultiple', 'value' => @$values['list_id'] ), $options ); 
        //$fieldset->addElement( array( 'name' => 'list_id-cx', 'label' => '', 'type' => 'TextArea', 'value' => implode( "\r\n", PCMailer_Send::getContacts( $values['list_id'] ) ) ) ); 

        $fieldset->addElement( array( 'name' => 'subject', 'placeholder' => 'Email Subject', 'type' => 'InputText', 'value' => @$values['subject'] ) );
        if( ! empty( $values['type'] ) )
        {
            Application_Article_Abstract::initHTMLEditor();
        }
        $fieldset->addElement( array( 'name' => 'preview', 'placeholder' => 'Construct a catchy Email Preview text...', 'type' => 'InputText', 'value' => @$values['preview'] ) ); 

        $fieldset->addElement( array( 'name' => 'body', 'placeholder' => 'Enter the body content of your email campaign here...', 'type' => 'TextArea', 'data-document_type' => 'html', 'value' => @$values['body'] ) ); 
        $options = static::$_availableStatuses;
        if( empty( $values['sent'] ) )
        {
            unset( $options[2] );
        }
        elseif( @$values['status'] == 2 )
        {

        }
        $fieldset->addElement( array( 'name' => 'status', 'type' => 'Select', 'value' => @$values['status'] ), $options ); 

        $fieldset->addElement( array( 'name' => 'send_time', 'label' => 'Schedule Send Time', 'type' => 'DateTime', 'value' => @$values['send_time'] ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
