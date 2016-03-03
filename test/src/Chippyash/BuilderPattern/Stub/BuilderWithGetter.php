<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractBuilder;

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
