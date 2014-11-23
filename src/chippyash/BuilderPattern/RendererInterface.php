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

/**
 * Builder renderer interface
 */
interface RendererInterface
{
    /**
     * Render a builder's result in a particular way
     *
     * @param BuilderInterface $builder Builder to be used for rendering
     * @return mixed Dependent on renderer
     */
    public function render(BuilderInterface $builder);
}
