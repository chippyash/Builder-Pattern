<?php
/*
 * Test stubs
 */
namespace Chippyash\Test\BuilderPattern\Stub;

use Chippyash\BuilderPattern\AbstractDirector;

/**
 * Director with modifiable behavior
 */
class DirectorWithModifier extends AbstractDirector
{

    public function addMod($mod, $eventName)
    {
        $this->addModification($mod, $eventName);
        return $this;
    }
}
