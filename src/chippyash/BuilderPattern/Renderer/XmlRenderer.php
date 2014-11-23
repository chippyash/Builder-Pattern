<?php
/*
 * Builder Pattern Library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Builder_pattern
 */
namespace chippyash\BuilderPattern\Renderer;

use chippyash\BuilderPattern\RendererInterface;
use \chippyash\BuilderPattern\BuilderInterface;

/**
 * Passes back the built data object converted to XML
 */
class XmlRenderer implements RendererInterface
{
    /**
     * Render the built data
     * 
     * @param BuilderInterface $builder
     * @return array
     */
    public function render(BuilderInterface $builder)
    {
        return $this->arrayToDom($builder->getResult())->saveXML();
    }
    
    /**
     * Convert an associative array into a DOMDocument
     * NB - works only for simple item data types
     * A root node of <root> will be set if do not specify an alternative
     *
     * @todo Extend to cater for complex item data types (objects etc)
     * @param array $arr Array to convert
     * @param string $root Root name for DOM
     * @see DOMUtil::domNodeToArray
     *
     * @return \DOMDocument
     */
    protected function arrayToDom(array $arr, $root = 'root')
    {
        $dom = new \DOMDocument();
        $rootEle = $dom->createElement($root);
        $this->arrayToDomNode($dom, $rootEle, $arr);
        $dom->appendChild($rootEle);

        return $dom;
    }

    /**
     * Recursive iteration through array to be converted to DOM
     *
     * @param \DOMDocument $dom
     * @param \DOMElement $node
     * @param array $arr
     */
    protected function arrayToDomNode(\DOMDocument $dom, \DOMElement $node, array $arr)
    {
        foreach ($arr as $key => $item) {
            $ref = null;
            if (is_numeric($key) || empty($key)) {
                if (is_numeric($key)) {
                    $ref = $key;
                }
                $key = 'item';
            }
            if (is_array($item)) {
                $newNode = $dom->createElement($key);
                if (!is_null($ref)) {
                    $attr = $dom->createAttribute('ref');
                    $attr->value = $ref;
                    $newNode->appendChild($attr);
                }
                $this->arrayToDomNode($dom, $newNode, $item);
                $node->appendChild($newNode);
            } elseif (is_object($item)) {
                $this->encodeObject($key, $item, $dom, $node);
            } else {
                $node->appendChild($dom->createElement($key, $item));
            }
        }
    }
    
    protected function encodeObject($key, $value, \DOMDocument $dom, \DOMElement $node) 
    {
        if (method_exists($value, '__toString')) {
            $node->appendChild($dom->createElement($key, (string) $value));
        } elseif (method_exists($value, 'toString')) {
            $node->appendChild($dom->createElement($key, $value->toString()));
        } elseif ($value instanceof \Serializable) {
            $node->appendChild($dom->createElement($key, serialize($value)));
        } else {
            if ($value instanceof IteratorAggregate) {
                $props = $value->getIterator();
                $propCollection = [];
                foreach ($props as $key => $value) {
                    $propCollection[$key] = $value;
                }
            } elseif ($value instanceof Iterator) {
                $props = $value;
                $propCollection = [];
                foreach ($props as $key => $value) {
                    $propCollection[$key] = $value;
                }
            } else {
                $propCollection = get_object_vars($value);
            }
            $newNode = $dom->createElement($key);
            foreach ($propCollection as $k => $v) {
                $newNode->appendChild($dom->createElement($k, $v));
            }
            $node->appendChild($newNode);
        }
    }
}
