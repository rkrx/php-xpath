<?php
namespace Kir\XML\XPath;

use Kir\Streams\InputStream;
use Kir\XML\XPath;
use Kir\XML\XPath\DomXPath\DomNodeList;

class DomXPath implements XPath {
	/**
	 * @var \DOMDocument
	 */
	private $document = null;

	/**
	 * @var \DOMNode
	 */
	private $parent = null;

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
		$this->document = $document;
		$this->namespaces = $namespaces;
		$this->parent = $parent;
		if($this->parent === null) {
			$this->parent = $this->document->documentElement;
		}
		$this->init();
	}

	/**
	 * @param string $xpath
	 * @return bool
	 */
	public function has($xpath) {
		$list = $this->xp->query($xpath, $this->parent);
		return $list->length > 0;
	}

	/**
	 * @param string $xpath
	 * @return DomXPath[]
	 */
	public function getNodes($xpath) {
		$list = $this->xp->query($xpath, $this->parent);
		$nodeList = new DomNodeList($list, $this);
		return $nodeList;
	}

	/**
	 * @param string $xpath
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getValue($xpath = '.', $default = null) {
		$list = $this->xp->query($xpath, $this->parent);
		if($list->length > 0) {
			$node = $list->item(0);
			if($node instanceof \DOMNode) {
				$nodeValue = (string) $node->nodeValue;
				return $nodeValue;
			}
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
		return $this->document;
	}

	/**
	 * @return \DOMNode
	 */
	public function getDomNode() {
		return $this->parent;
	}

	/**
	 */
	private function init() {
		$this->xp = new \DOMXPath($this->document);
		foreach($this->namespaces as $prefix => $uri) {
			$this->xp->registerNamespace($prefix, $uri);
		}
	}
}