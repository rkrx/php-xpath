<?php
namespace Kir\XML\XPath;

use Kir\Streams\InputStream;
use Kir\XML\XPath;
use Kir\XML\XPath\DomXPath\DomNodeList;

class DomXPath implements XPath {
	/**
	 * @var \DOMDocument
	 */
	private $domDocument = null;

	/**
	 * @var \DOMNode
	 */
	private $parentNode = null;

	/**
	 * @var \DOMXPath
	 */
	private $xp = null;

	/**
	 * @var string[]
	 */
	private $namespaces = null;

	/**
	 * @param InputStream $stream
	 * @param array $namespaces
	 * @return static
	 */
	static public function createFromHtmlStream(InputStream $stream, array $namespaces = array()) {
		$doc = new \DOMDocument();
		@$doc->loadHTML($stream->readAll());
		return new static($doc, $namespaces);
	}

	/**
	 * @param InputStream $stream
	 * @param array $namespaces
	 * @return static
	 */
	static public function createFromXmlStream(InputStream $stream, array $namespaces = array()) {
		$doc = new \DOMDocument();
		$doc->loadXML($stream->readAll());
		return new static($doc, $namespaces);
	}

	/**
	 * @param \DOMDocument $document
	 * @param array $namespaces
	 * @param \DOMNode $parent
	 */
	public function __construct(\DOMDocument $document, array $namespaces = array(), \DOMNode $parent = null) {
		$this->domDocument = $document;
		$this->namespaces = $namespaces;
		$this->parentNode = $parent;
		if($this->parentNode === null) {
			$this->parentNode = $this->domDocument->documentElement;
		}
		$this->init();
	}

	/**
	 * @param string $xpath
	 * @return bool
	 */
	public function has($xpath) {
		$list = $this->xp->query($xpath, $this->parentNode);
		return $list->length > 0;
	}

	/**
	 * @param string $xpath
	 * @return DomXPath[]
	 */
	public function getNodes($xpath) {
		$nodes = $this->xp->query($xpath, $this->parentNode);
		$nodeList = new DomNodeList($nodes, $this);
		return $nodeList;
	}

	/**
	 * @param string $xpath
	 * @throws NodeNotFoundException
	 * @return XPath
	 */
	public function getFirst($xpath = '.') {
		$nodes = $this->getNodes($xpath);
		if(count($nodes) > 0) {
			return $nodes[0];
		}
		throw new NodeNotFoundException();
	}

	/**
	 * @param string $xpath
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getValue($xpath = '.', $default = null) {
		$list = $this->xp->query($xpath, $this->parentNode);
		$result = '';
		if($list->length < 1) {
			return $default;
		}
		for($i = 0; $i < $list->length; $i++) {
			$node = $list->item($i);
			if($node instanceof \DOMNode) {
				$nodeValue = (string) $node->nodeValue;
				$result .= $nodeValue;
			}
		}
		return $result;
	}

	/**
	 * @param string $xpath
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getFirstValue($xpath = '.', $default = null) {
		$nodes = $this->getNodes($xpath);
		if(count($nodes) > 0) {
			return $nodes[0]->getValue('.');
		}
		return $default;
	}

	/**
	 * @return string[]
	 */
	public function getNamespaces() {
		return $this->namespaces;
	}

	/**
	 * @return \DOMDocument
	 */
	public function getDomDocument() {
		return $this->domDocument;
	}

	/**
	 * @return \DOMNode
	 */
	public function getDomNode() {
		return $this->parentNode;
	}

	/**
	 */
	private function init() {
		$this->xp = new \DOMXPath($this->domDocument);
		foreach($this->namespaces as $prefix => $uri) {
			$this->xp->registerNamespace($prefix, $uri);
		}
	}
}