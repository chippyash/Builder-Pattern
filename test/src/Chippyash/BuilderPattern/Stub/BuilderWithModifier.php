<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractBuilder;
use Chippyash\BuilderPattern\Modifier;
use Chippyash\BuilderPattern\ModifiableInterface;

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
