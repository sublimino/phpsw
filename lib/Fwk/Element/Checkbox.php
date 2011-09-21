<?php
class Fwk_Element_Checkbox extends Fwk_Element
{
	public function render()
	{
		return sprintf('<input type="checkbox" name="%s" value="1" %s />', $this->name, $this->value == 1 ? 'checked="checked"' : $this->value);
	}
}
