<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractBuilder;

/**
 * Builder with a discovery (has) method
 */
class BuilderWithDiscovery extends AbstractBuilder
{
    protected function hasFoo()
    {
        return false;
    }

    protected function setBuildItems()
    {
        $this->buildItems = ['foo' => null];      
    }

}
