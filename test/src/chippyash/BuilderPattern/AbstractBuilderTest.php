<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-18 at 21:56:19.
 */
namespace chippyash\Test\BuilderPattern;

require_once __DIR__ . '/Stub/BuilderWithSetter.php';
require_once __DIR__ . '/Stub/BuilderWithGetter.php';
require_once __DIR__ . '/Stub/BuilderWithDiscovery.php';

use chippyash\Test\BuilderPattern\Stub\BuilderWithSetter;
use chippyash\Test\BuilderPattern\Stub\BuilderWithGetter;
use chippyash\Test\BuilderPattern\Stub\BuilderWithDiscovery;

class AbstractBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Mock
     * @var \chippyash\BuilderPattern\AbstractBuilder
     */
    protected $object;

    /**
     * Builder parameters
     * @var array
     */
    protected $params = array(
            'foo' => null,
            'bar' => null,
            'baz' => null
        );
    
    /**
     * Use reflection to set the parameters that the builder supports
     * This mimics a call to the abstract method setParams on a concrete
     * builder class that is called during construction
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\chippyash\BuilderPattern\AbstractBuilder');
        $refl = new \ReflectionObject($this->object);
        $prop = $refl->getProperty('buildItems');
        $prop->setAccessible(true);
        $prop->setValue($this->object, $this->params);
    }

    public function testGetDataObjectReturnsArray()
    {
        $this->assertTrue($this->object->build());
        $this->assertInternalType('array', $this->object->getResult());
        $this->assertEquals($this->params, $this->object->getResult());
    }
    
    public function testSettingSimpleValueWillStoreInBuilderParameters()
    {
        $this->object->foo = 'bar';
        $this->assertTrue($this->object->build());
        $test = $this->params;
        $test['foo'] = 'bar';
        $this->assertEquals($test, $this->object->getResult());
    }

    public function testSettingABuilderForAParameterWillBuildNestedArray()
    {
        $newBuilder = $this->getMockForAbstractClass('\chippyash\BuilderPattern\AbstractBuilder');
        $refl = new \ReflectionObject($newBuilder);
        $prop = $refl->getProperty('buildItems');
        $prop->setAccessible(true);
        $prop->setValue($newBuilder, $this->params);
        
        $this->object->foo = $newBuilder;
        $test = $this->params;
        $test['foo'] = $this->params;
        
        $this->assertTrue($this->object->build());
        $this->assertEquals($test, $this->object->getResult());
    }
    
    public function testBuilderWillUseSetMethodIfOneIsAvalaible()
    {
        $object = new BuilderWithSetter();
        $object->setFoo('bar');
        $this->assertEquals('fred', $object->foo);
    }

    public function testBuilderWillProxyToSetIfSetMethodNotFound()
    {
        $this->object->setFoo('bar');
        $this->assertEquals('bar', $this->object->foo);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown parameter: bob
     */
    public function testSetWillThrowExceptionForUnknownParameter()
    {
        $this->object->bob = 'foo';
    }
    
    
    public function testBuilderWillUseGetMethodIfOneIsAvalaible()
    {
        $object = new BuilderWithGetter();
        $object->setFoo('bar');
        $this->assertEquals('fred', $object->foo);
    }
    
    public function testBuilderWillProxyToGetIfGetMethodNotFound()
    {
        $this->object->setFoo('bar');
        $this->assertEquals('bar', $this->object->getFoo());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown parameter: bob
     */
    public function testGetWillThrowExceptionForUnknownParameter()
    {
        $v = $this->object->bob;
    }

    public function testIssetWillReturnTrueForParameterThatHasValue()
    {
        $this->object->foo = 'bar';
        $this->assertTrue(isset($this->object->foo));
    }
    
    public function testIssetWillReturnFalseForParameterThatHasNoValue()
    {
        $this->assertFalse(isset($this->object->foo));
    }
    
    public function testIssetWillReturnFalseForUnknownParameter()
    {
        $this->assertFalse(isset($this->object->bob));
    }
    
    public function testUnsetWillSetParameterToNull()
    {
        $this->object->foo = 'bar';
        $this->assertTrue(isset($this->object->foo));
        unset($this->object->foo);
        $this->assertFalse(isset($this->object->foo));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Unknown parameter: bob
     */
    public function testUnsetWillThrowExceptionForUnknownParameter()
    {
        unset($this->object->bob);
    }

    public function testBuilderWillUseDiscoveryMethodIfOneIsAvalaible()
    {
        $object = new BuilderWithDiscovery();
        $this->assertFalse(isset($object->foo));
        $object->setFoo('bar');
        $this->assertFalse(isset($object->foo));
    }
    
    public function testBuilderWillProxyToIssetIfDiscoveryMethodNotFound()
    {
        $this->assertFalse($this->object->hasFoo());
        $this->object->setFoo('bar');
        $this->assertTrue($this->object->hasFoo());
    }

    
    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Bad method name: DummyMethod
     */
    public function testMethodProxyWillThrowExceptionIfMethodNotSupported()
    {
        $this->object->DummyMethod();
    }
    
    public function testCanSetModifier()
    {
        $modifier = $this->getMock('Zend\EventManager\EventManagerAwareInterface');
        $this->assertEquals($this->object, $this->object->setModifier($modifier));
        
        $refl = new \ReflectionObject($this->object);
        $prop = $refl->getProperty('modifier');
        $prop->setAccessible(true);
        $this->assertEquals($modifier, $prop->getValue($this->object));
    }

    public function testCanCallModifyToTriggerEvents()
    {
        $modifier = $this->getMock('Zend\EventManager\EventManagerAwareInterface');
        $modifier->expects($this->once())
                ->method('getEventManager')
                ->willReturn(new \Zend\EventManager\EventManager());
        $this->object->setModifier($modifier);
        
        $this->assertInstanceOf('Zend\EventManager\ResponseCollection', $this->object->modify());
    }


}
