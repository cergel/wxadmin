<?php

/**
 * Class CinemaSelectorWidget
 *
 * 影城选择器，同一个页面内加载多个会出现冲突
 */
class CinemaSelectorWidget extends CWidget
{
    public $name = 'cinemas';
    public $selectedCinemas;

    public function init()
    {
        parent::init();
    }
	
	public function run()
	{
		$this->render('CinemaSelectorWidget', array(
            'name' => $this->name,
            'selectedCinemas' => $this->selectedCinemas
        ));
	}
}