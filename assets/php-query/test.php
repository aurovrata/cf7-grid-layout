<?php
$main_form_html='<div id="first"><div id="second"><div id="third"></div></div></div>';
require_once './phpQuery.php';

phpQuery::newDocumentHTML($main_form_html);
echo PHP_EOL;
echo pq('#first')->htmlOuter().PHP_EOL;
$inner = pq('#first')->find('#second')->contents()->remove();
$second = pq('#first')->find('#second')->remove();
echo "Inner: ".$inner.PHP_EOL;
echo "Second: ".$second.PHP_EOL;
echo pq('#first')->append($inner)->htmlOuter().PHP_EOL;
echo $second->append(pq('#first'))->htmlOuter().PHP_EOL;
//echo pq('')->html();
//echo $inner->htmlOuter();
/*echo "Inner: ".$inner.PHP_EOL;
$outer = pq('*')->htmlOuter();
echo "Outer: ".$outer.PHP_EOL;
//phpQuery::newDocumentHTML($inner);
echo "New: ".PHP_EOL;
echo pq('*')->html();*/
echo PHP_EOL;
echo PHP_EOL;
