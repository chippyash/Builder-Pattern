<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractBuilder;

/**
 * Builder with a setter method
 */
class BuilderWithSetter extends AbstractBuilder
{
    protected function setBuildItems()
    {
        $this->buildItems = ['foo' => null];
    }
    
    protected function setFoo($value)
    {
        $this->buildItems['foo'] = 'fred';
        return $this;
    }
}
