<?php
class Fwk_Element_Text extends Fwk_Element
{
	public function render()
	{
		return sprintf('<input type="text" name="%s" value="%s" class="%s" />', $this->name, htmlentities($this->value == null ? '' : $this->value), $this->classAttrib);
	}
}
