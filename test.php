<?php
include 'vendor/autoload.php';

use Kir\XML\XPath\DomXPath;
use Kir\Streams\Impl\StringStream;

$content = '
<html>
	<body>
		<div title="1">
			<a href="#">a</a>
			<a href="#">b</a>
			<a href="#">c</a>
		</div>
		<div title="2">
			<a href="#">d</a>
			<a href="#">e</a>
			<a href="#">f</a>
		</div>
	</body>
</html>
';

$stream = new StringStream($content);
$xp = DomXPath::createFromHtmlStream($stream);

php_sapi_name() == 'cli' || print('<pre>');

foreach($xp->getNodes('//div') as $node) {
	echo "{$node->getValue('./@title')}\n";
	foreach($node->getNodes('./a') as $subNode) {
		echo "\t{$subNode->getValue('.')}\n";
	}
}