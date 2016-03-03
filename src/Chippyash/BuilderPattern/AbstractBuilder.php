<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace Chippyash\BuilderPattern;

use Chippyash\BuilderPattern\BuilderInterface;
use Chippyash\BuilderPattern\ModifiableInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\ResponseCollection;

/**
 * Abstract builder pattern
 */
abstract class AbstractBuilder implements BuilderInterface, ModifiableInterface
{
    /**
     * Build items
     *
     * @var array
     */
    protected $buildItems = [];

    /**
     *
     * @var array
     */
    protected $dataObject;

    /**
     * Modification event manager
     * @var Zend\EventManager\EventManagerAwareInterface 
     */
    protected $modifier;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setBuildItems();
    }

    /**
     * Build the data object
     *
     * @return boolean
     */
    public function build()
    {
        $this->dataObject = [];
        foreach ($this->buildItems as $name => $value) {
            if ($value instanceof BuilderInterface) {
                if (!$this->buildItems[$name]->build()) {
                    return false;
                }
                $this->dataObject[$name] = $this->buildItems[$name]->getResult();
            } elseif (is_callable($value)) {
                $this->dataObject[$name] = $value();
            } else {
                $this->dataObject[$name] = $this->buildItems[$name];
            }
        }

        return true;
    }

    /**
     * Return the generated data object
     *
     * @return array
     */
    public function getResult()
    {
        return $this->dataObject;
    }

    /**
     * Set a modifier for this builder
     * 
     * @param EventManagerAwareInterface $modifier
     * @return \Chippyash\BuilderPattern\AbstractModifiableBuilder
     */
    public function setModifier(EventManagerAwareInterface $modifier)
    {
        $this->modifier = $modifier;
        foreach ($this->buildItems as $buildItem) {
            if ($buildItem instanceof ModifiableInterface) {
                $buildItem->setModifier($modifier);
            }
        }
        
        return $this;
    }
    
    /**
     * Add a modification trigger for this builder
     * 
     * @param array $params
     * @param type $phase
     * @return ResponseCollection
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
     * Get object value.  Throw exception of value does not exist
     *
     * @param string $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($key)
    {
        $k = strtolower($key);
        //check for specialised getter
        $methodName = 'get' . ucfirst($k);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } elseif (array_key_exists($k, $this->buildItems)) {
            //return raw value
            return $this->buildItems[$k];
        } else {
            throw new \InvalidArgumentException("Unknown parameter: {$key}");
        }
    }

    /**
     * Set an object value.  Throws exception if key does not exist
     *
     * @param string $key
     * @param mixed $value
     * @return Chippyash\BuilderPattern\SDO\SDOInterface Fluent Interface
     * @throws \InvalidParameterException
     */
    public function __set($key, $value)
    {
        $k = strtolower($key);
        //check for specialised setter
        $methodName = 'set' . ucfirst($k);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($value);
        } elseif (array_key_exists($k, $this->buildItems)) {
            //set the raw value if parameter exists
            $this->buildItems[$k] = $value;
            return $this;
        } else {
            throw new \InvalidArgumentException("Unknown parameter: {$key}");
        }
    }

    /**
     * Tests if an object parameter is set and contains a non null value.
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        $k = strtolower($key);
        //check for specialised discovery method
        $methodName = 'has' . ucfirst($k);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } elseif (array_key_exists($k, $this->buildItems) && !is_null($this->buildItems[$k])) {
            return true;
        }
        
        return false;
    }

    /**
     * Sets key value to null.
     *
     * @param string key
     * @return \Chippyash\BuilderPattern\DataBuilder\Builder\AbstractBuilder Fluent Interface
     * @throws InvalidArgumentException
     */
    public function __unset($key)
    {
        $k = strtolower($key);
        if (array_key_exists($k, $this->buildItems)) {
            $this->buildItems[$k] = null;
        } else {
            throw new \InvalidArgumentException("Unknown parameter: {$key}");
        }
        
        return $this;
    }
    
    /**
     * Proxy method calls
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        $prefix = strtolower(substr($name, 0, 3));
        $key = substr($name, 3);
        switch ($prefix) {
            case 'get':
                return $this->__get($key);
            case 'set':
                return $this->__set($key, $arguments[0]);
            case 'has':
                return $this->__isset($key);
            default:
                throw new \BadMethodCallException("Bad method name: {$name}");
        }
    }

    /**
     * Set up the build items that this builder will manage
     */
    abstract protected function setBuildItems();
}
