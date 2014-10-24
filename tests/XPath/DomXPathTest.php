<?php
namespace Kir\XML\XPath;

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
	 */
	public function testMap() {
		$xp = $this->createFromHtml('tests/assets/test.html');
		$map = array(
			'a' => '//body/div[@title="1"]/a[1]/text()',
			'b' => '//body/div[@title="1"]/a[2]/text()',
			'c' => '//body/div[@title="1"]/a[3]/text()',
			'd' => '//body/div[@title="2"]/a[1]/text()',
		);
		$values = $xp->map($map);

		$this->assertEquals('a', $values['a']);
		$this->assertEquals('b', $values['b']);
		$this->assertEquals('c', $values['c']);
		$this->assertEquals('d', $values['d']);
	}

	/**
	 * @param string $filename
	 * @return DomXPath
	 */
	private function createFromXml($filename) {
		$stream = file_get_contents($filename);
		return DomXPath::createFromXml($stream);
	}

	/**
	 * @param string $filename
	 * @return DomXPath
	 */
	private function createFromHtml($filename) {
		$stream = file_get_contents($filename);
		return DomXPath::createFromHtml($stream);
	}
}
 