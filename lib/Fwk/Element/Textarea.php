<?php
class Fwk_Element_Textarea extends Fwk_Element
{
	public function render()
	{
		return sprintf('<textarea rows="6" name="%s" class="%s" />%s</textarea>', $this->name, $this->classAttrib, htmlentities($this->value == null ? '' : $this->value, ENT_QUOTES, 'UTF-8'));
	}
}
