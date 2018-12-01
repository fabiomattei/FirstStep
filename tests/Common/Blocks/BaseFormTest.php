<?php 

/**
*  Testing the BaseForm class
*
*  @author Fabio Mattei
*/
class BaseFormTest extends PHPUnit_Framework_TestCase{
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$this->assertTrue(is_object($form));
	unset($form);
  }
  
  /**
  * Checking empty form
  */
  public function testItShowsAnEmpyForm(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$this->assertTrue($form->show() == '<h3></h3><form action="" method="POST" class="form-horizontal"></form>');
	unset($form);
  }
  
  /**
  * Checking a text field is added to the form
  */
  public function testItShowsAFormWithATextField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addTextField( 'myname', 'My label', 'My placeholder', 'My value', '6');
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><div class="form-group col-md-6"><label for="myname">My label</label><input class="form-control" type="text" id="myname" name="myname" value="My value" placeholder="My placeholder"></div></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a textarea field is added to the form
  */
  public function testItShowsAFormWithATextAreaField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addTextAreaField( 'myname', 'My label', 'My value', '6');
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><div class="form-group col-md-6"><label for="myname">My label</label><textarea class="form-control" id="myname" name="myname">My value</textarea></div></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a dropdown field is added to the form
  * the possible options are passed as an associative array where the key is the value of the option and the value 
  * is the description of the option: array( '1' => 'Option 1', '2' => 'Option 2' )
  * The value passed to the method is selected: 2 => Option 2
  */
  public function testItShowsAFormWithADropdownField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addDropdownField( 'myname', 'My label', array( '1' => 'Option 1', '2' => 'Option 2' ), '2', '6');
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><div class="form-group col-md-6"><label for="myname">My label</label><select class="form-control" id="myname" name="myname"><option value="1" >Option 1</option><option value="2" selected="selected">Option 2</option></select></div></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a file upload field is added to the form
  */
  public function testItShowsAFormWithAFileUploadField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addFileUploadField( 'myname', 'My label', '6' );
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><div class="form-group col-md-6"><label for="myname">My label</label><input class="form-control" type="file" id="myname" name="myname"></div></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithAHelpingTextField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addHelpingText( 'Title', 'My description', '6' );
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><div class="form-group col-md-6"><h5>Title</h5><p>My description</p></div></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithAHiddenField(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addHiddenField( 'myfield', 'My Value' );
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><input type="hidden" name="myfield" value="My Value"></form>') !== false);
	unset($form);
  }
  
  /**
  * Checking a helping text is added to the form
  */
  public function testItShowsAFormWithASubmitButton(){
	$form = new Firststep\Common\Blocks\BaseForm;
	$form->addSubmitButton( 'myfield', 'My Value' );
	$this->assertTrue(strpos($form->show(), '<h3></h3><form action="" method="POST" class="form-horizontal"><input class="form-control" type="submit" name="myfield" value="My Value"/></form>') !== false);
	unset($form);
  }
  
}
