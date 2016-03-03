<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace Chippyash\BuilderPattern\Renderer;

use Chippyash\BuilderPattern\RendererInterface;
use \Chippyash\BuilderPattern\BuilderInterface;
use Zend\Json\Json;

/**
 * Passes back the built data object converted to Json
 */
class JsonRenderer implements RendererInterface
{
    /**
     * Render the built data
     * 
     * @param BuilderInterface $builder
     * @return string
     */
    public function render(BuilderInterface $builder)
    {
        return Json::encode($builder->getResult());
    }
}
