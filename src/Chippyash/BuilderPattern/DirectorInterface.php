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
use Chippyash\BuilderPattern\RendererInterface;

/**
 * Interface for a Builder Director
 */
interface DirectorInterface
{
    /**
     * Build and render
     *
     * @return mixed Depends on rendering
     * @throws \Chippyash\BuilderPattern\Exceptions\BuilderPatternException
     */
    public function build();
}
