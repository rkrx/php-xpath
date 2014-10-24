<?php
namespace Kir\XML\XPath\DomXPath;

use Kir\XML\XPath\DomXPath;

class DomNodeList implements \Countable, \SeekableIterator, \ArrayAccess {
	/**
	 * @var \DOMNodeList
	 */
	private $nodeList = null;

	/**
	 * @var DomXPath
	 */
	private $parent = null;

	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @var
	 */
	private $namespaces = array();

	/**
	 * @var \DOMDocument
	 */
	private $domDocument = null;

	/**
	 * @var \DOMNode
	 */
	private $domNode = null;

	/**
	 * @param \DOMNodeList $nodeList
	 * @param \Kir\XML\XPath\DomXPath $parent
	 */
	public function __construct(\DOMNodeList $nodeList, DomXPath $parent) {
		$this->nodeList = $nodeList;
		$this->parent = $parent;
		$this->namespaces = $this->parent->getNamespaces();
		$this->domDocument = $this->parent->getDomDocument();
		$this->domNode = $this->parent->getDomNode();
	}

	/**
	 * @return DomXPath
	 */
	public function current() {
		return $this->offsetGet($this->position);
	}

	/**
	 */
	public function next() {
		$this->position++;
	}

	/**
	 * @return int
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->position >= 0 && $this->position < $this->count();
	}

	/**
	 */
	public function rewind() {
		$this->seek(0);
	}

	/**
	 * @return int
	 */
	public function count() {
		return $this->nodeList->length;
	}

	/**
	 * @param int $position
	 */
	public function seek($position) {
		$this->position = $position;
	}

	/**
	 * @param int $offset
	 * @return boolean true on success or false on failure.
	 */
	public function offsetExists($offset) {
		return $offset >= 0 && $offset < $this->nodeList->length;
	}

	/**
	 * @param int $offset
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset) {
		$node = $this->nodeList->item($offset);
		if($node instanceof \DOMNode) {
			return new DomXPath($this->domDocument, $this->namespaces, $node);
		}
		return null;
	}

	/**
	 * @param int $offset
	 * @param mixed $value
	 * @throws \BadMethodCallException
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		throw new \BadMethodCallException("Not implemented");
	}

	/**
	 * @param int $offset
	 * @throws \BadMethodCallException
	 * @return void
	 */
	public function offsetUnset($offset) {
		throw new \BadMethodCallException("Not implemented");
	}
}