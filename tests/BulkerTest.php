<?php

    use \Contactamx\Sms\Bulker;

    class BulkerTest extends \PHPUnit\Framework\TestCase
    {
        public function testInstantiationOfBulker()
        {
            $obj = new Bulker();
            $this->assertInstanceOf('\Contactamx\Sms\Bulker', $obj);
        }
    }
    