<?php
declare(strict_types=1);
namespace JtCron\Model;

use JtCron\Model\{Config,Cronjob};
use Cron\CronExpression;

/**
 * Class Cron
 * @package JtCron\Model
 */
class Cron
{
    const STATUS_SUCCESS = 'success';

    const STATUS__FAILED = 'failed';

    const EXECUTE_SUCCESS = 'Success execution';

    /**
     * @var \JtCron\Model\Config
     */
    protected $_config;

    /**
     * Cron constructor.
     */
    public function __construct()
    {
        $this->_config = new Config();
    }

    /**
     * @return void
     */
    public function run() : void
    {
        $publicConfig = $this->_config->getPublicConfig();
		
        if (!empty($publicConfig['jobs'])) {
            foreach ($publicConfig['jobs'] as $name => $job) {
                $this->executeJob($name, $job);
            }
        }
    }

    /**
     * @param string $name
     * @param array $job
     */
    private function executeJob(string $name, array $job) : void
    {
        $time = CronExpression::factory($job['schedule']);

        if ($time->isDue()) {
            if (isset($job['instance']) && isset($job['method'])) {
                try {
                    $subject = new $job['instance'];
                    $subject->{$job['method']}();
                    $this->_writeSuccess($name);
                } catch (\Exception $e) {
                    $this->_writeError($name, $e->getMessage());
                }
            } elseif (isset($job['function'])) {
                try {
                    call_user_func($job['function']);
                    $this->_writeSuccess($name);
                } catch (\Exception $e) {
                    $this->_writeError($name, $e->getMessage());
                }
            }
        }
    }

    /**
     * @param string $name
     */
    protected function _writeSuccess(string $name) : void
    {
        $job = new Cronjob();
        $job->setData([
            'time' => (new \DateTime())->format('Y-m-d H:i:s'),
            'name' => $name,
            'message' => self::EXECUTE_SUCCESS,
            'status' => self::STATUS_SUCCESS
        ])->save();
    }

    /**
     * @param string $name
     * @param string $message
     */
    protected function _writeError(string $name, string $message) : void
    {
        $job = new Cronjob();
        $job->setData([
            'time' => (new \DateTime())->format('Y-m-d H:i:s'),
            'name' => $name,
            'message' => $message,
            'status' => self::STATUS__FAILED
        ])->save();
    }
}