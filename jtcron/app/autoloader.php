<?php
spl_autoload_register(function ($class) {

    if (stripos($class, JTCRON_ROOT_NAMESPACE) !== false) {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $path = str_replace(JTCRON_ROOT_NAMESPACE,JTCRON_FOLDER.DIRECTORY_SEPARATOR.JTCRON_APP_FOLDER.DIRECTORY_SEPARATOR, $path);
        if (file_exists($path.'.php')) {
            include $path.'.php';
        }
    }

}, true, true);