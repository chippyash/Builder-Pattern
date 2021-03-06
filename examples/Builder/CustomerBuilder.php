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
use Chippyash\BuilderPattern\Example\Builder\AccountBuilder;

/**
 * Builder for a customer
 */
class CustomerBuilder extends AbstractBuilder
{
    protected function setBuildItems()
    {
        $this->buildItems = [
            'name' => '',
            'account' => new AccountBuilder(),
            'exportName' => function(){return 'BuilderPattern!';}
        ];
    }

}
