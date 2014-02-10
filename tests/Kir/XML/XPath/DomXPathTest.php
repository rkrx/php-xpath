<?php
namespace Kir\XML\XPath;

use Kir\Streams\Common\PhpStream;

class DomXPathTest extends \PHPUnit_Framework_TestCase {
	/**
	 */
	public function testHas() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$this->assertEquals(true, $xp->has('/html/body/div/a'));
		$this->assertEquals(true, $xp->has('/html/body/div[1]/a[1]'));
		$this->assertEquals(false, $xp->has('/html/body/div[1]/a[4]'));
	}

	/**
	 */
	public function testGetValue() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$this->assertEquals('a', $xp->getValue('/html/body/div[1]/a[1]'));
		$this->assertEquals('ad', $xp->getValue('/html/body/div/a[1]'));
	}

	/**
	 */
	public function testGetFirstValue() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$this->assertEquals('a', $xp->getFirstValue('/html/body/div[1]/a[1]'));
		$this->assertEquals('a', $xp->getFirstValue('/html/body/div/a[1]'));
	}

	/**
	 */
	public function testGetNodes() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$this->assertEquals(0, count($xp->getNodes('/html/body/div/a[4]')));
		$this->assertEquals(3, count($xp->getNodes('/html/body/div[1]/a')));
		$this->assertEquals(6, count($xp->getNodes('/html/body/div/a')));
	}

	/**
	 */
	public function testGetFirst() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$this->assertEquals('a', $xp->getFirst('/html/body/div[1]')->getValue('./a[1]'));
		$this->assertEquals('d', $xp->getFirst('/html/body/div[2]')->getValue('./a[1]'));

		$this->setExpectedException('\\Kir\\XML\\XPath\\NodeNotFoundException');
		$this->assertEquals('d', $xp->getFirst('/html/body/div[3]')->getValue('./a[1]'));
	}

	/**
	 * @param string $filename
	 * @return DomXPath
	 */
	private function createFromXml($filename) {
		$stream = new PhpStream($filename, 'r', true);
		return DomXPath::createFromXmlStream($stream);
	}

	/**
	 * @param string $filename
	 * @return DomXPath
	 */
	private function createFromHtml($filename) {
		$stream = new PhpStream($filename, 'r', true);
		return DomXPath::createFromHtmlStream($stream);
	}
}
 