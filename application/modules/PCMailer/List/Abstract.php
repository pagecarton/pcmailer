<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_List_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Sunday 17th of June 2018 12:39PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class PCMailer_List_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'list_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'list_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PCMailer_List';
	
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

        $fieldset->addElement( array( 'name' => 'list_title', 'type' => 'InputText', 'value' => @$values['list_title'] ) ); 

        $filter = new Ayoola_Filter_SelectListArray( 'form_name', 'form_title');
        $options = Ayoola_Form_Table::getInstance();
        $options = $options->select();
        $options = $filter->filter( $options );

        $options ? $fieldset->addElement( array( 'name' => 'forms', 'name' => 'Pick Contacts from these forms', 'type' => 'Checkbox', 'value' => @$values['forms'] ), $options ) : null; 

                
        $v = array();
        if( ! empty( $values['products'] ) )
        {   
            foreach( $values['products'] as $each )
            {
                $record = Application_Article_Table::getInstance()->selectOne( null, array( 'article_url' => $each ) );
                $v[$each] = $record['article_title'];
            }
        }

        $fieldset->addElement( 
            array( 
            'name' => 'products', 
            'label' => 'Include Customers who bought the following products', 
            'config' => array( 
                'ajax' => array( 
                    'url' => '' . Ayoola_Application::getUrlPrefix() . '/widgets/Application_Article_Search?article_type=product',
                    'delay' => 1000
                ),
                'placeholder' => 'e.g. Type Product Title',
                'minimumInputLength' => 2,   
            ), 
            'multiple' => 'multiple', 
            'type' => 'Select2', 
            'value' => $v 
            )
            ,
            $v
        ); 

        $options = array(
            'user-accounts' => 'Registered User Accounts',
            'purchaser' => 'Users Who Bought Something',
            'mailing-list' =>  'Users Who Subscribe to Mailing List',
        );
        $fieldset->addElement( array( 'name' => 'include_options', 'label' => 'Include',  'type' => 'Checkbox', 'multiple' => true, 'value' => @$values['include_options'] ), $options );         


		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
