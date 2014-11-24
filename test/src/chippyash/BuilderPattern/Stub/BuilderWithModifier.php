<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractBuilder;
use chippyash\BuilderPattern\Modifier;
use chippyash\BuilderPattern\ModifiableInterface;

/**
 * Builder with modifiable behavior
 */
class BuilderWithModifier extends AbstractBuilder
{
    protected function setBuildItems()
    {
        $this->buildItems = ['foo' => null];
        
        //add modifications
        $this->setModifier(new Modifier);
        $this->modify(['foo'=>'bar'], ModifiableInterface::PHASE_PRE_BUILD);
        $this->modify(['foo'=>'bar'], ModifiableInterface::PHASE_POST_BUILD);
    }
}
