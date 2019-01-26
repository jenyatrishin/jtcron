<?php
namespace JtCron\Test;

use JtCron\Entity\AbstractEntity;

class AbstractEntityTest extends \PHPUnit\Framework\TestCase
{

    public $abstractEntity;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->abstractEntity = new AbstractEntity();
        parent::__construct($name, $data, $dataName);
    }

    public function testGetTable()
    {

    }
}
