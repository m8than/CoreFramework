<?php
namespace App\Controllers;

use Core\Controller\Controller;
//use App\Models\User;

class Home extends Controller
{
    public function All($page = '')
    {
        $this->setView('home.ctpl');
    	$this->setViewData('cur_page', $page);
        $this->setViewData('items', array('item1', 'item2', 'item3'));
    	return;
    }
}
