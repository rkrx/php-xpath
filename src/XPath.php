<?php
namespace Kir\XML;

use Kir\XML\XPath\NodeNotFoundException;

interface XPath {
	/**
	 * @param string $xpath
	 * @return bool
	 */
	public function has($xpath);

	/**
	 * @param string $xpath
	 * @return XPath[]
	 */
	public function getNodes($xpath);

	/**
	 * @param string $xpath
	 * @throws NodeNotFoundException
	 * @return XPath
	 */
	public function getFirst($xpath = '.');

	/**
	 * @param string $xpath
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getValue($xpath = '.', $default = null);

	/**
	 * @param string $xpath
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getFirstValue($xpath = '.', $default = null);

	/**
	 * @param array $keyValueArray
	 * @return array
	 */
	public function map($keyValueArray);
}