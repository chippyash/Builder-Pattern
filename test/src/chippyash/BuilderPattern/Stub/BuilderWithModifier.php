<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractModifiableBuilder;

/**
 * Builder with modifiable behavior
 */
class BuilderWithModifier extends AbstractModifiableBuilder
{
    protected function setBuildItems()
    {
        $this->buildItems = ['foo' => null];
    }
}
