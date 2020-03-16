<?php

class RoubleCurrencyRate {
	private static $CURRENCY_CODES = ['usd' => 'R01235', 'eur' => 'R01239'];
	private static $URL = 'http://www.cbr.ru/scripts/XML_dynamic.asp?';

	private $currency = '';
	private $rate = ['current' => NULL, 'previous' => NULL];
	private $last_date_info = '';


	public function __construct($currency)
	{
		$currency = mb_strtolower($currency);

		if ( !array_key_exists($currency, self::$CURRENCY_CODES) )
			throw new Exception('Неверное наименование валюты!');

		$this->currency = $currency;

		$this->cbank_extract_rates();
	}

	public function get_currency()
	{
		return $this->currency;
	}

	public function get_last_date_info()
	{
	    return $this->last_date_info;
	}

	public function get_current_rate()
	{
		if ($this->is_rates_received())
		    return $this->rate['current'];
	}

	// Возвращает стрелку с направленим текущего курса относительно предыдущего
	public function get_arrow()
	{
		if ( $this->is_rates_received() )
			return ($this->rate['current'] > $this->rate['previous']) ? '↑' : '↓';
	}


	private function is_rates_received()
	{
		return ($this->rate['current'] * $this->rate['previous'] != NULL);
	}

	private function cbank_extract_rates()
	{
		//$xml = file_get_contents($this->prepare_uri());
		//$xml = simplexml_load_file($this->prepare_uri());
		//print_r(json_decode(json_encode($xml)));
		$xml = new SimpleXMLElement($this->prepare_uri(), NULL, TRUE);

		$length = count($xml->Record);
		$this->rate['current'] = sprintf('%01.2F', (float) str_replace(',', '.', $xml->Record[$length-1]->Value));
		$this->rate['previous'] = sprintf('%01.2F', (float) str_replace(',', '.', $xml->Record[$length-2]->Value));

		// Сохраняет дату последнего доступного дня с курсом валюты
		$this->last_date_info = $xml->Record[$length-1]['Date'];
	}

	private function prepare_uri()
	{
		$date_format = 'd/m/Y';
		// Дата сегодняшняя
		$date_now_param = 'date_req2='.date($date_format);
		// Дата 7 дней назад
		$date_before_param = 'date_req1='.date($date_format, time() - (7*24*60*60));

		if ( !array_key_exists($this->currency, self::$CURRENCY_CODES) )
			throw new Exception('Неверное наименование валюты!');

		$currency_code_param = 'VAL_NM_RQ=' . self::$CURRENCY_CODES[$this->currency];

		$query_string = self::$URL .$date_before_param.'&'.$date_now_param.'&'.$currency_code_param;
		return $query_string;
	}
}

?>
