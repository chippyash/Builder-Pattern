<?php
/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-11-20 at 06:53:40.
 */
namespace chippyash\Test\BuilderPattern;

include_once __DIR__ . '/Stub/BadBuilder.php';
include_once __DIR__ . '/Stub/DirectorWithModifier.php';

use chippyash\BuilderPattern\AbstractDirector;
use chippyash\Test\BuilderPattern\Stub\BadBuilder;
use chippyash\Test\BuilderPattern\Stub\DirectorWithModifier;
use chippyash\BuilderPattern\ModifiableInterface;

class AbstractDirectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Mock
     * @var AbstractDirector
     */
    protected $object;
    /**
     * Mock
     * @var chippyash\BuilderPattern\BuilderInterface 
     */
    protected $builder;
    /**
     * Mock
     * @var chippyash\BuilderPattern\RendererInterface
     */
    protected $renderer;
    
    protected $modifer;
    
    protected function setUp()
    {
        $this->builder = $this->getMockForAbstractClass('chippyash\BuilderPattern\AbstractBuilder');
        $this->renderer = $this->getMock('chippyash\BuilderPattern\RendererInterface');
        $this->object = $this->getMockForAbstractClass(
                'chippyash\BuilderPattern\AbstractDirector',
                [$this->builder, $this->renderer]);
    }

    public function testBuildWillReturnValueIfBuildSucceeds()
    {
        $test = ['foo' => 'bar'];
        $this->renderer->expects($this->once())
                ->method('render')
                ->will($this->returnValue($test));
        
        $this->assertEquals($test, $this->object->build());
    }

    /**
     * @expectedException chippyash\BuilderPattern\Exceptions\BuilderPatternException
     * @expectedExceptionMessage Unable to build
     */
    public function testBuildWillThrowExceptionIfBuildFails()
    {
        $object = $this->getMockForAbstractClass(
                'chippyash\BuilderPattern\AbstractDirector',
                [new BadBuilder(), $this->renderer]);

        $object->build();
    }
    
    public function testSettingAModifierWillSetTheModifier()
    {
        $modifier = $this->getMock('Zend\EventManager\EventManagerAwareInterface');
        $this->object->setModifier($modifier);
        $refl = new \ReflectionObject($this->object);
        $prop = $refl->getProperty('modifier');
        $prop->setAccessible(true);
        
        $this->assertEquals($prop->getValue($this->object), $modifier);
    }
    
    public function testCallingModifyWillReturnAnEmptyZendEventResponseCollectionIfModifierNotSet()
    {
        $test = $this->object->modify();
        $this->assertInstanceOf(
                'Zend\EventManager\ResponseCollection', 
                $test);
        $this->assertEquals(0, $test->count());
    }
    
    public function testCallingModifyWillReturnAZendEventResponseCollectionIfModifierSet()
    {
        $modifier = $this->getMock('Zend\EventManager\EventManagerAwareInterface');
        $modifier->expects($this->once())
                ->method('getEventManager')
                ->willReturn(new \Zend\EventManager\EventManager());
        $this->object->setModifier($modifier);
        $test = $this->object->modify();
        $this->assertInstanceOf(
                'Zend\EventManager\ResponseCollection', 
                $test);
        $this->assertEquals(0, $test->count());
    }
    
    public function testCanAddModifications()
    {
        $object = new DirectorWithModifier($this->builder, $this->renderer);
        $object->addMod(['name' => 'bar'], ModifiableInterface::PHASE_PRE_BUILD);
        $object->addMod('foo', ModifiableInterface::PHASE_POST_BUILD);
        $refl = new \ReflectionObject($object);
        $prop = $refl->getProperty('modifications');
        $prop->setAccessible(true);
        
        $expected = [ModifiableInterface::PHASE_PRE_BUILD => [['name' => 'bar']],
                     ModifiableInterface::PHASE_POST_BUILD => [['name' => 'foo']]];
        $this->assertEquals($expected, $prop->getValue($object));
        
        $this->renderer->expects($this->once())
                ->method('render')
                ->will($this->returnValue(true));
        $this->assertTrue($object->build());
    }
}
