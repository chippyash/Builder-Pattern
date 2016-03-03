<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractBuilder;

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
