<?php
abstract class Fwk_Element
{
	protected $name;
	protected $value;
	protected $validators = array();
	protected $errors = array();
	protected $classAttrib;

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function addValidator($validator)
	{
		$this->validators[] = $validator;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function isValid()
	{
		$valid = true;
		$count = 0;

		while ($valid && $count < count($this->validators)) {
			$validator = $this->validators[$count++];

			if (!$validator->isValid($this->getValue())) {
				$valid = false;
				$this->errors = array_merge($this->errors, $validator->getMessages());
			}
		}

		return (count($this->errors) == 0);
	}

	abstract public function render();

	public function setClassAttrib($classAttrib)
	{
		$this->classAttrib = $classAttrib;
	}
}
