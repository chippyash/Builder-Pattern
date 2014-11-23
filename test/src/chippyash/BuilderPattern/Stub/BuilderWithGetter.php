<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractBuilder;

/**
 * Builder with a getter method
 */
class BuilderWithGetter extends AbstractBuilder
{
    protected function setBuildItems()
    {
        $this->buildItems = ['foo' => null];
    }
    
    protected function getFoo()
    {
        return 'fred';
    }
}
