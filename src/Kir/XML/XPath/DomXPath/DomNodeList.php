<?php
namespace Kir\XML\XPath\DomXPath;

use Kir\XML\XPath\DomXPath;

class DomNodeList implements \Countable, \SeekableIterator {
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
		$node = $this->nodeList->item($this->position);
		if($node instanceof \DOMNode) {
			return new DomXPath($this->domDocument, $this->namespaces, $node);
		}
		return null;
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
}