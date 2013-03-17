<?php
class FrontendAction extends LtAction
{
	public function beforeExecute()
	{
		$this->layout = "frontpage";
	}
}