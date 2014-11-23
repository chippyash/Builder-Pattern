<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace chippyash\BuilderPattern;

use Zend\EventManager\EventManagerAwareInterface;

/**
 * Interface for builder that can be modified
 */
interface ModifiableInterface
{
    const PHASE_PRE_BUILD = 'prebuild';
    const PHASE_POST_BUILD = 'postbuild';
    
    /**
     * Set the modifier on the builder
     * 
     * @param EventManagerAwareInterface $modifier
     * 
     * @return chippyash\BuilderPattern\ModifiableInterface modified Builder
     */
    public function setModifier(EventManagerAwareInterface $modifier);
    
    /**
     * Run a modification on the builder
     *
     * @param array $params - parameters to pass to modication responder
     * @param int $phase - phase we are running
     * 
     * @return Zend\EventManager\ResponseCollection Response from trigger
     */
    public function modify(array $params = [], $phase = self::PHASE_POST_BUILD);

}
