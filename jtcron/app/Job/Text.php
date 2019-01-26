<?php
declare(strict_types=1);
namespace JtCron\Job;

/**
 * Class Text
 * @package JtCron\Job
 */
class Text
{
    /**
     * @return void
     */
    public function execute() : void
    {
        file_put_contents(JTCRON_FOLDER.'/pub/test.log', 'test'.PHP_EOL, FILE_APPEND);
    }
}