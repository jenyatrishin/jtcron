<?php
declare(strict_types=1);
namespace JtCron\Entity;

use JtCron\Model\{Config,App};

/**
 * Class AbstractEntity
 * @package JtCron\Entity
 */
class AbstractEntity
{
    /**
     * @var wpdb
     */
    protected $_db;

    /**
     * @var Config
     */
    protected $_config;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->_db = $wpdb;
        $this->_config = new Config();
    }

    /**
     * @return string
     */
    public function getTable() : string
    {
        return $this->_db->prefix.$this->_table;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data) : bool
    {
        try {
            if (isset($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
                $this->_db->update($this->getTable(), $data, ['id' => $id]);
            } else {
                $this->_db->insert($this->getTable(), $data);
            }
            return true;
        } catch (\Exception $e) {
            $this->_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $value
     * @param string $column
     * @return array|null|object|void
     */
    public function load($value, $column = 'id')
    {
        return $this->_db->get_row('select * from '.$this->getTable().' where '.$column.' = '.$value, ARRAY_A);
    }

    /**
     * @param string|int $value
     * @param string $column
     * @return false|int
     */
    public function delete($value, $column = 'id')
    {
		$sql = "delete from {$this->getTable()} where {$column} in ({$value})";
        return $this->_db->query($sql);
    }

    /**
     * @param $conditions
     * @return array|null|object
     */
    public function getRecords($conditions)
    {
        $sql = '';
        $firstCondition = array_shift($conditions);
        if ($firstCondition) {
            $sql = implode(' ', $firstCondition);
        }
        if (count($conditions)) {
            foreach ($conditions as $condition) {
                $sql .= 'and '.implode(' ', $condition);
            }
        }
        $query = 'select * from '.$this->getTable();
        if ($sql) {
            $query .= ' where '.$sql;
        }
        return $this->_db->get_results($query,ARRAY_A);
    }

    /**
     * @param $message
     * @return void
     */
    protected function _log($message) : void
    {
        App::create()->log($message);
    }
}