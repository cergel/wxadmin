<?php

/**
 * Class MovieSelectorWidget
 *
 * 影片选择器，同一个页面内加载多个会出现冲突
 */
class MovieSelectorWidget extends CWidget
{
    public $name = 'movies';
    public $selectedMovies;

    public function init()
    {
        parent::init();
    }
	
	public function run()
	{
		$this->render('MovieSelectorWidget', array(
            'name' => $this->name,
            'selectedMovies' => $this->selectedMovies
        ));
	}
}