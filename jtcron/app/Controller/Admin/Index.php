<?php
namespace JtCron\Controller\Admin;

use JtCron\Controller\Controller;

class Index extends Controller
{

    public function execute()
    {
        $url = get_home_url().'/wp-json/v2/jtcron/';
        $this->render('index', ['base' => $url]);
    }

}