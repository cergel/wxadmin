<?php

/**
 * Class CitySelectorWidget
 *
 * 城市选择器，同一个页面内加载多个会出现冲突
 */
class CitySelectorWidget extends CWidget
{
    public $name = 'cities';
    public $selectedCities;

    public function init()
    {
        parent::init();
    }
	
	public function run()
	{
		$this->render('CitySelectorWidget', array(
            'name' => $this->name,
            'selectedCities' => $this->selectedCities
        ));
	}
}