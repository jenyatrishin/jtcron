<?php
declare(strict_types=1);
namespace JtCron\Model;

use JtCron\Entity\Cronjob as CronjobEntity;

/**
 * Class Cronjob
 * @package JtCron\Model
 */
class Cronjob
{
    /**
     * @var array
     */
    private $_data = [];

    /**
     * @var CronjobEntity
     */
    private $_entity;

    /**
     * Cronjob constructor.
     */
    public function __construct()
    {
        $this->_entity = new CronjobEntity();
    }

    /**
     * @return CronjobEntity
     */
    public function getEntityInstance() : CronjobEntity
    {
        return $this->_entity;
    }

    /**
     * @param $property
     * @return mixed|null
     */
    public function __get($property)
    {
        return (isset($this->_data[$property])) ? $this->_data[$property] : null;
    }

    /**
     * @param $property
     * @param $value
     * @return $this
     */
    public function __set($property, $value) : self
    {
        $this->_data[$property] = $value;
        return $this;
    }

    /**
     * @param $id
     * @param string $field
     * @return $this
     */
    public function find($id, $field = 'id') : self
    {
        $data = $this->_entity->load($id, $field);
        if ($data) {
            $this->setData($data);
        }
        return $this;
    }

    /**
     * @param array $condition
     * @return array
     */
    public function getList(array $condition) : array
    {
        $collection =  $this->_entity->getRecords($condition);
        $output = [];
        foreach ($collection as $one) {
            $object = new self();
            $object->setData($one);
            $output[] = $object;
        }
        return $output;
    }

    /**
     * @return bool
     */
    public function save() : bool
    {
        return $this->_entity->save($this->getData());
    }

    /**
     * @return void
     */
    public function delete() : void
    {
        $this->_entity->delete($this->getData('id'));
    }

    /**
     * @param array $condition
     * @param $offset
     * @param $limit
     * @return array
     */
    public function getListLimit(array $condition, $offset, $limit) : array
    {
        return array_slice($this->getList($condition), $offset, $limit);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) : self
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * @param null $key
     * @return array|mixed|null
     */
    public function getData($key = null)
    {
        if (!is_null($key)) {
            return (isset($this->_data[$key])) ? $this->_data[$key] : null;
        }
        return $this->_data;
    }

    /**
     * @param $key
     * @param $value
     * @return Cronjob
     */
    public function addData($key, $value) : self
    {
        $this->_data[$key] = $value;
        return $this;
    }
}