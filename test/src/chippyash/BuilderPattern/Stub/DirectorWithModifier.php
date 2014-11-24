<?php
/*
 * Test stubs
 */
namespace chippyash\Test\BuilderPattern\Stub;

use chippyash\BuilderPattern\AbstractDirector;

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
