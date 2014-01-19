<?php
namespace Kir\XML;

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
	 * @param null|bool|int|float|string $default
	 * @return string
	 */
	public function getValue($xpath = '.', $default = null);
}