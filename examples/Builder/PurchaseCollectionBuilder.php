<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace chippyash\BuilderPattern\Example\Builder;

use chippyash\BuilderPattern\AbstractCollectionBuilder;
use chippyash\BuilderPattern\ModifiableInterface;
use chippyash\BuilderPattern\Example\Builder\ItemBuilder;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Builder for a collection of purchaseable items
 */
class PurchaseCollectionBuilder extends AbstractCollectionBuilder 
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
     * Add a purchase
     * Event params expected:
     *  name: 'addPurchase'
     *  id : string item id
     *  date: DateTime date of purchase
     * 
     * @param Event $e
     */
    public function preBuildListener(Event $e)
    {
        if ($e->getParam('name') == 'addPurchase') {
            $item = new ItemBuilder;
            $item->setId($e->getParam('id'))
                 ->setDate($e->getParam('date'));
            $this->addBuilder($item);
        }
    }
}
