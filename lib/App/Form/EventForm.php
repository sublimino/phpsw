<?php
class App_Form_EventForm extends Fwk_Form
{
	public function init()
	{
		$this->addElement('title', 'text', array('required' => true, 'class' => 'xlarge'));
		$this->addElement(
			'start_date', 
			'text', 
			array(
				'required' => true, 
				'validators' => array(
					//new Zend_Validate_Regex(array('pattern' => '/^\d{4}-\d{2}-\d{2}$/'))
					new Zend_Validate_Date(array('format' => 'dd/MM/YYYY H:i:s'))
				),
				'class' => 'medium'
			)
		);

		$this->addElement(
			'description', 
			'textarea', 
			array(
				'required' => true,
				'class' => 'xxlarge'
			)
		);

		$this->addElement(
			'mapText', 
			'text', 
			array(
				'required' => true,
				'class' => 'large'
			)
		);

		$this->addElement(
			'mapLink', 
			'text', 
			array(
				'required' => true,
				'class' => 'large'
			)
		);

		$this->addElement(
			'published',
			'checkbox',
			array()
		);

		$this->addElement('submit', 'submit', array('class' => 'btn primary'));
		$this['submit']->setValue('Submit');
	}
}
