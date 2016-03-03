<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractBuilder;

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
