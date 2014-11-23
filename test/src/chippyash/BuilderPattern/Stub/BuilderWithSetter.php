<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractBuilder;

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
