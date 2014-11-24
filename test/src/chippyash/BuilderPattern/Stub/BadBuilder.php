<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractBuilder;

/**
 * Builder that returns false for a build
 */
class BadBuilder extends AbstractBuilder
{
    
    public function build()
    {
        return false;
    }
    
    protected function setBuildItems()
    {
        return $this;
    }
}
