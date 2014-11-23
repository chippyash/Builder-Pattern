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

/**
 * Interface for a Builder
 */
interface BuilderInterface
{
    /**
     * Build the required result
     *
     * @return boolean
     */
    public function build();

    /**
     * Return the result of the build
     *
     * @return mixed
     */
    public function getResult();
}
