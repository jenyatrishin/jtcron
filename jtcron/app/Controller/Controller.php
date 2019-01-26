<?php
namespace JtCron\Controller;

abstract class Controller
{

    abstract function execute();

    public function render($file, $data = [])
    {
        extract($data);
        include JTCRON_FOLDER.DIRECTORY_SEPARATOR.JTCRON_APP_FOLDER.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$file.'.phtml';
    }
}