<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PCMailer_Contact_Importer
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Importer.php Monday 18th of June 2018 01:29AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PCMailer_Contact_Importer extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98, 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Import Contact'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            //  Output demo content to screen
			$this->createForm( 'Import', 'Import Contacts' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
     //       var_export( $values );
            //  https://stackoverflow.com/questions/5813168/how-to-import-csv-file-in-php
    
        $path = Ayoola_Doc_Browser::getDocumentsDirectory() . $values['csv_url'];
        //   var_export( $values );
    //    var_export( $path );
        $row = 1;
        $rows = array();
        if( is_file( $path ) )
        {
            if (($handle = fopen( $path, "r" ) ) !== FALSE) 
            {
                $validator = new Ayoola_Validator_EmailAddress();
                while( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE) 
                {
                    $data = array_combine( $values['row_columns'], $data );
                    $data['email'] = strtolower( $data['email'] );
                    if( ! $validator->validate( trim( $data['email'] ) )  )
                    {
                        $bCounter++;
                        continue;
                    }
                    if( $found = PCMailer_Contact::getInstance()->select( null, array( 'email' => $data['email'] ) ) )
                    {
                        $change = false;
                        foreach( $found as $key => $eachFoundData )
                        {
                            if( empty( $found[$key] ) && ! empty( $data[$key] ) )
                            {
                                $change = true;
                                $found[$key] = $found[$key];
                            }
                        }
                        if( $change )
                        {
                            $uCounter++;
                            PCMailer_Contact::getInstance()->update( $found, array( 'email' => $data['email'] ) );
                        }
                    }
                    else
                    {
                        $nCounter++;
                        PCMailer_Contact::getInstance()->insert( $data );
                    }

                }
                fclose( $handle );
            }
        }
        $this->setViewContent( '<p class="goodnews">Import Complete.</p>', true ); 
        if( $nCounter )
        {
            $this->setViewContent( '<p class="pc-notify-info">' . $nCounter . ' new contacts imported successfully.</p>' ); 
        }
        if( $nCounter )
        {
            $this->setViewContent( '<p class="pc-notify-info">' . $uCounter . ' contacts were updated.</p>' ); 
        }
        if( $nCounter )
        {
            $this->setViewContent( '<p class="badnews">' . $bCounter . ' contacts has errors.</p>' ); 
        }

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}

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
		$form->oneFieldSetAtATime = true;

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = false;       

        $fieldset->addElement( array( 'name' => 'csv_url', 'label' => 'CSV File', 'placeholder' => '/url/to/file.csv', 'type' => 'Document', 'value' => @$values['csv_url'] ) );
        $fieldset->addRequirement( 'csv_url', array( 'NotEmpty' => null ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   

        $file = $this->getGlobalValue( 'csv_url' ); 
    
        $path = Ayoola_Doc_Browser::getDocumentsDirectory() . $file;
        //   var_export( $values );
     //   var_export( $path );
        $row = 1;
        $rows = array();
        if( is_file( $path ) )
        {
        //    var_export( $path );
            if (($handle = fopen( $path, "r" ) ) !== FALSE) {
                while( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE) 
                {
                    $num = count($data);
                    $row++;
                    for( $c=0; $c < $num; $c++ ) 
                    {
                       $rows[$c][] = $data[$c];
                    }
                }
                fclose( $handle );
            }
            $options = array_keys( PCMailer_Contact::getInstance()->getDataTypes() );
            $options = array( '' => 'Select Column' ) + array_combine( $options, $options ) + array( '' => '[Ignore Column]' );
            $fieldsetX = new Ayoola_Form_Element();
            $fieldsetX->addElement( array( 'name' => 'row_columns', 'label' => '', 'multiple' => 'multiple', 'type' => 'Select', 'value' => @$values['row_columns'] ), $options );
            $defaultFieldContent = $fieldsetX->view();
            $noOfEmailsBefore = 0;
            $validator = new Ayoola_Validator_EmailAddress();
            foreach( $rows as $each )
            {
                $fieldsetToUse = $defaultFieldContent;
                $content = implode( "\r\n", $each );
             //   $noOfEmails = count( explode( '@', $content ) );
                $emailY = array_pop( $each );
              //  $emailX = explode( '@', array_pop( $each ) );
            //    if( count( $emailX ) == 2 && array_shift( $emailX ) && count( explode( '.', array_pop( $emailX ) ) ) >= 2  )
                if( $validator->validate( $emailY )  )
                {
                    $fieldsetY = new Ayoola_Form_Element();
                    $fieldsetY->addElement( array( 'name' => 'row_columns', 'label' => '', 'multiple' => 'multiple', 'type' => 'Select', 'value' => 'email' ), $options );
                    $fieldsetToUse = $fieldsetY->view();
                }
                $noOfEmailsBefore = $noOfEmails;
                $html .= '<div style="width:240px; max-width:100%; display:inline;float:left;border:1px #000 solid;margin:0.2em;padding:0.2em;" class="">' . $fieldsetToUse . '<textarea style="min-height:200px; font-size:x-small;" readonly >' . $content . '</textarea></div>';
            }
      //      $html = '<legend style="" class="">' . $fieldsetX->view() . '</div>';
        //    var_export( $data );
        //    var_export( $html );
		    $fieldset = new Ayoola_Form_Element;
            $fieldset->addElement( array( 'name' => 'examples', 'type' => 'Html', 'value' => $html ), array( 'html' => $html, 'fields' => 'row_columns' ) );
      //      $fieldset->addRequirement( 'row_columns', array( 'NotEmpty' => null ) );
     //       $fieldset->addElement( array( 'name' => 'xxx', 'type' => 'InputText', 'value' => '' ) );
     //       $fieldset->addRequirement( 'xxx', array( 'NotEmpty' => null ) );
            $fieldset->addLegend( 'Match imported columns with contact keys' );
            $form->addFieldset( $fieldset );   
        }

		$this->setForm( $form );
    } 
	// END OF CLASS
}
