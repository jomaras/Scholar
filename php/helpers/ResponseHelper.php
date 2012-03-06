<?php
class ResponseHelper
{
	public static function DefineBrowserHeaders()
	{
		ini_set('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11');
		ini_set('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9');
		ini_set('Accept-Encoding', 'gzip,deflate,sdch');
		ini_set('Accept-Language', 'hr-HR,hr;q=0.8,en-US;q=0.6,en;q=0.4');
		ini_set('Accept-Charset', 'windows-1250,utf-8;q=0.7,*;q=0.3');
	}
}