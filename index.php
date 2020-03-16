<?php

require_once("RoubleCurrencyRate.php");


$usd = new RoubleCurrencyRate('usd');
$eur = new RoubleCurrencyRate('eur');

echo "<pre>";
echo "1 USD = {$usd->get_arrow()}{$usd->get_current_rate()} RUB на дату {$usd->get_last_date_info()}";
echo "<br>";
echo "1 EUR = {$usd->get_arrow()}{$eur->get_current_rate()} RUB на дату {$eur->get_last_date_info()}";

echo "<p>P.S. Обнаружил любопытный момент, что cbr.ru почему-то не показывает курс валюты на сегодняшний день - только трёхдневной давности</p>
<p><i>By <u>Yury Litvinenko</u> for <u>US STUDIO</u></i></p>";

?>
