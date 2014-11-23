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

use chippyash\BuilderPattern\AbstractBuilder;
use chippyash\BuilderPattern\BuilderInterface;
use chippyash\BuilderPattern\ModifiableInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Abstract collection of Data Builders
 * Each item in the collection must be a builder
 * 
 * To add a builder to the collection use
 *   AbstractCollectionBuilder::addBuilder(BuilderInterface $builder)
 * 
 * To get the Entire collection use
 *   AbstractCollectionBuilder::getCollection()
 * 
 * To set the Entire collection use
 *   AbstractCollectionBuilder::setCollection(array $collectionOfBuilders)
 */
abstract class AbstractCollectionBuilder extends AbstractBuilder
{
    /**
     * Build the data object
     * @override
     * 
     * @return boolean
     */
    public function build()
    {
        $this->dataObject = array();
        foreach ($this->buildItems['collection'] as $builder) {
            if (!$builder->build()) {
                return false;
            } else {
                $this->dataObject[] = $builder->getResult();
            }
        }

        return true;
    }

    /**
     * Add a builder to the collection
     *
     * @param chippyash\BuilderPattern\BuilderInterface $builder Builder to add to collection
     * @return \chippyash\BuilderPattern\DataBuilder\AbstractCollectionBuilder Fluent Interface
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->buildItems['collection'][] = $builder;

        return $this;
    }

    /**
     * Set the entire collection
     * 
     * @param array $collection Array of BuilderInterface
     * 
     * @return \chippyash\BuilderPattern\DataBuilder\AbstractCollectionBuilder Fluent Interface
     */
    public function setCollection(array $collection)
    {
        if (is_array($this->buildItems) 
                && array_key_exists('collection', $this->buildItems) 
                && !empty($this->buildItems['collection'])) {
            unset($this->collection);
        }
        $this->setBuildItems();
        foreach ($collection as $builder) {
            $this->addBuilder($builder);
        }
        
        return $this;
    }
    
    /**
     * Get the collection
     * 
     * @return array Collection of builders
     */
    public function getCollection()
    {
        return $this->buildItems['collection'];
    }
    
    /**
     * Set a modifier for this builder
     * 
     * @param EventManagerAwareInterface $modifier
     * @return \chippyash\BuilderPattern\AbstractModifiableBuilder
     */
    public function setModifier(EventManagerAwareInterface $modifier)
    {
        $this->modifier = $modifier;
        foreach ($this->buildItems['collection'] as $builder) {
            if ($builder instanceof ModifiableInterface) {
                $builder->setModifier($modifier);
            }
        }
        
        return $this;
    }
    
    /**
     * Set up the parameters that this builder will manage
     */
    protected function setBuildItems()
    {
        $this->buildItems = ['collection' => []];
    }    
}
