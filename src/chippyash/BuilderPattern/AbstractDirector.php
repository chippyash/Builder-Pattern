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

use chippyash\BuilderPattern\BuilderInterface;
use chippyash\BuilderPattern\RendererInterface;
use chippyash\BuilderPattern\DirectorInterface;
use chippyash\BuilderPattern\ModifiableInterface;
use chippyash\BuilderPattern\Exceptions\BuilderPatternException;
use Zend\EventManager\ResponseCollection;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Abstract builder director pattern
 * 
 * Supports building, rendering and modification of the build process
 */
abstract class AbstractDirector implements DirectorInterface, ModifiableInterface
{
    /**
     *
     * @var chippyash\BuilderPattern\BuilderInterface
     */
    protected $builder;
    
    /**
     *
     * @var chippyash\BuilderPattern\RendererInterface
     */
    protected $renderer;

    /**
     *
     * @var chippyash\BuilderPattern\AbstractModifier
     */
    protected $modifier;
    
    /**
     * Modifications to be made
     * 
     * @var array
     */
    protected $modifications = [
        ModifiableInterface::PHASE_PRE_BUILD => [],
        ModifiableInterface::PHASE_POST_BUILD => []
    ];
    
    /**
     * Constructor
     *
     * @param \chippyash\BuilderPattern\BuilderInterface $builder
     * @param \chippyash\BuilderPattern\RendererInterface $renderer
     */
    public function __construct(BuilderInterface $builder, RendererInterface $renderer)
    {
        $this->builder = $builder;
        $this->renderer = $renderer;
    }

    /**
     * Build and render
     *
     * @return mixed Depends on rendering
     * @throws \chippyash\BuilderPattern\Exceptions\BuilderPatternException
     */
    public function build()
    {
        $this->modifyPreBuild();
        if (!$this->builder->build()) {
            throw new BuilderPatternException('Unable to build');
        }
        $this->modifyPostBuild();

        return $this->renderer->render($this->builder);
    }
    
    /**
     * Set the builder modifier if required
     * 
     * @param EventManagerAwareInterface $modifier
     * 
     * @return chippyash\BuilderPattern\AbstractDirector Fluent Interface
     */
    public function setModifier(EventManagerAwareInterface $modifier)
    {
        $this->modifier = $modifier;
        $this->builder->setModifier($modifier);
        
        return $this;
    }
    
    /**
     * Run a modification on the builder being controlled
     * by this director
     *
     * @param array $params - parameters to pass to modication responder
     * @param int $phase - phase we are running
     *
     * @return Zend\EventManager\ResponseCollection Response from trigger
     */
    public function modify(array $params = [], $phase = ModifiableInterface::PHASE_POST_BUILD)
    {
        if (!empty($this->modifier)) {
            return $this->modifier->getEventManager()
                    ->trigger($phase, $this, $params);
        }
        
        //return empty collection
        return new ResponseCollection();
    }
    
    /**
     * Add a modification to be triggered pre or post build
     * It is expected that concrete Directors will call this and/or have
     * methods that the user can call to set modifications via this method.
     * 
     * @param array|mixed $mod  ['name'=>modName,...] or simply the modName
     * @param string $phase Phase name, usually one of ModifiableInterface::PHASE_...
     * 
     * @return \chippyash\BuilderPattern\AbstractDirector Fluent Interface
     */
    protected function addModification($mod, $phase = ModifiableInterface::PHASE_POST_BUILD)
    {
        if (!is_array($mod)) {
            $mod = ['name' => $mod];
        }
        $this->modifications[$phase][] = $mod;
        
        return $this;
    }
    
    /**
     * Fire event triggers for pre build modifications
     */
    protected function modifyPreBuild()
    {
        foreach ($this->modifications[ModifiableInterface::PHASE_PRE_BUILD] as $mod) {
            $this->modify($mod, ModifiableInterface::PHASE_PRE_BUILD);
        }
    }
    
    /**
     * Fire event triggers for post build modifications
     */
    protected function modifyPostBuild()
    {
        foreach ($this->modifications[ModifiableInterface::PHASE_POST_BUILD] as $mod) {
            $this->modify($mod);
        }
    }
}
