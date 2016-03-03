<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace Chippyash\BuilderPattern\Example\Builder;

use Chippyash\BuilderPattern\AbstractBuilder;
use Chippyash\BuilderPattern\Example\Builder\PurchaseCollectionBuilder;
use Chippyash\BuilderPattern\ModifiableInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\Event;

/**
 * Builder for a customer account
 */
class AccountBuilder extends AbstractBuilder
{   
    /**
     * Extend setModifier to add our listeners
     * 
     * @param EventManagerAwareInterface $modifier
     */
    public function setModifier(EventManagerAwareInterface $modifier)
    {
        parent::setModifier($modifier);
        $this->modifier->getEventManager()->attach(ModifiableInterface::PHASE_PRE_BUILD, [$this,'preBuildListener']);
    }

    /**
     * Update the account balance
     * 
     * Event params expected:
     *  name: 'updateBalance'
     *  amount : numeric amount to add/subtract from balance
     * 
     * @param Event $e
     */
    public function preBuildListener(Event $e)
    {
        if ($e->getParam('name') == 'updateBalance') {
            $this->balance += $e->getParam('amount');
        }
    }
    
    protected function setBuildItems()
    {
        $this->buildItems = [
            'id' => '',
            'balance' => 0,
            'purchases' => new PurchaseCollectionBuilder()
        ];
    }
}
