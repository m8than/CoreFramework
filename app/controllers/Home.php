<?php

class Home extends Controller
{
    public function All($page = '')
    {
        $this->setView('home.tpl');
    	$this->setViewData('cur_page', $page);
    	return;
    }
}