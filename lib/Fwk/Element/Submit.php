<?php
class Fwk_Element_Submit extends Fwk_Element
{
	public function render()
	{
		return sprintf('<input class="%s" type="submit" value="Submit" />', $this->classAttrib);
	}
}
