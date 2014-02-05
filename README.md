php-xpath
=========

Human-friendly xpath handling

Example 1
---------

```PHP
<?php
use Kir\XML\XPath\DomXPath;
use Kir\Streams\Impl\StringStream;

$stream = new PhpStream('tests/assets/test.html', 'r');
$xp = DomXPath::createFromHtmlStream($stream);

php_sapi_name() == 'cli' || print('<pre>');

foreach($xp->getNodes('//div') as $node) {
	echo "{$node->getValue('./@title')}\n";
	foreach($node->getNodes('./a') as $subNode) {
		echo "\t{$subNode->getValue('.')}\n";
	}
}
```

Example 2
---------

```PHP
<?php
use Kir\XML\XPath\DomXPath;
use Kir\Streams\Impl\PhpStream;

$stream = new PhpStream('tests/assets/test.xml', 'r');
$xp = DomXPath::createFromXmlString($stream, ['a' => 'https://github.com/rkrx/php-xpath/products', 'b' => 'https://github.com/rkrx/php-xpath/product']);

php_sapi_name() == 'cli' || print('<pre>');

foreach($xp->getNodes('/a:products/b:product') as $no => $node) {
	echo "{$no}\n";
	echo "\t{$node->getValue('./b:name')}\n";
	echo "\t{$node->getValue('./b:ref')}\n";
	echo "\t{$node->getValue('./b:price')}\n";
	echo "\t{$node->getValue('./b:stock')}\n";
}
```