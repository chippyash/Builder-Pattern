<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace Chippyash\BuilderPattern\Example\Director;
include_once './Builder/AccountBuilder.php';
include_once './Builder/CustomerBuilder.php';
include_once './Builder/ItemBuilder.php';
include_once './Builder/PurchaseCollectionBuilder.php';

use Chippyash\BuilderPattern\AbstractDirector;
use Chippyash\BuilderPattern\Example\Builder\CustomerBuilder;
use Chippyash\BuilderPattern\Renderer\XmlRenderer;
use Chippyash\BuilderPattern\ModifiableInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Build Director for a customer
 */
class CustomerDirector extends AbstractDirector
{
    
    public function __construct(EventManagerAwareInterface $modifier)
    {
        $builder = new CustomerBuilder();
        $builder->setModifier($modifier);
        parent::__construct($builder, new XmlRenderer());
        
        //set test account details
        $builder->setName('Mrs Felicia Bailey');
        $builder->account->id = '023197';
        //business rule: customer must have bought at least one
        //item to qualify as a customer
        $this->buyItem('FE0456', 12.65);
    }
    
    public function buyItem($itemId, $amount)
    {
        $this->builder->modify(
                ['name' => 'addPurchase',
                 'id' => $itemId,
                 'date' => new \DateTime],
                ModifiableInterface::PHASE_PRE_BUILD
                );
        $this->builder->modify(
                ['name' => 'updateBalance',
                 'amount' => $amount],
                ModifiableInterface::PHASE_PRE_BUILD
                );
        
        return $this;
    }
}
