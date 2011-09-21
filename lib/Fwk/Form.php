<?php
class Fwk_Form implements ArrayAccess
{
	protected $fields = array();
	protected $values = array();

	public function __construct()
	{
		$this->init();
	}

	public function init()
	{
	}

	public function addElement($name, $type, $options = array())
	{
		$class = sprintf('Fwk_Element_%s', ucwords($type));
		$element = new $class($name);

		if (isset($options['required']) && $options['required']) {
			$validator = new Zend_Validate_NotEmpty();
			$element->addValidator($validator);
		}

		if (isset($options['validators']) && is_array($options['validators'])) {
			foreach ($options['validators'] as $validator) {
				$element->addValidator($validator);
			}
		}

		if (isset($options['class'])) {
			$element->setClassAttrib($options['class']);
		}

		$this->fields[$name] = $element;
	}

	public function isValid($data)
	{
		$this->setValues($data);

		$valid = true;

		foreach ($this->fields as $field)
		{
			if (!$field->isValid()) {
				$valid = false;
			}
		}

		return $valid;
	}

	public function setValues($data)
	{
		$this->values = $data;

		foreach ($this->fields as $field => $element)
		{
			if (isset($data[$field])) {
				$this->fields[$field]->setValue($data[$field]);
			} else {
				$this->fields[$field]->setValue(null);
			}
		}
	}

	public function getValues()
	{
		return $this->values;
	}

	public function getValue($name)
	{
		if (isset($this->values[$name])) {
			return $this->values[$name];
		}

		return null;
	}

	public function offsetGet($offset)
	{
		return $this->fields[$offset];
	}

	public function offsetExists($offset)
	{
		return isset($this->fields[$offset]);
	}

	public function offsetSet($offset, $value)
	{
		$this->fields[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->fields[$offset]);
	}
}
