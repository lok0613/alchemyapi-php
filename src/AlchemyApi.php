<?php

namespace Lok0613\AlchemyApi;

use GuzzleHttp\Client;

class AlchemyApi
{
	const HTTP_BASE_URL = 'http://access.alchemyapi.com/calls';
	const HTTPS_BASE_URL = 'https://access.alchemyapi.com/calls';
	// const

	/**
	 * @var string
	 */
	protected $apiKey;

	/**
	 * @var string
	 */
	protected $baseUrl;

	/**
	 * @var \GuzzleHttp\Client
	 */
	protected $client;

	/**
	 * Class constructor
	 *
	 * @param string $key
	 * @param boolean|optional $isSecure
	 */
	public function __construct($key, $isSecure = false)
	{
		$this->apiKey = $key;
		$this->baseUrl = $isSecure ? self::HTTPS_BASE_URL : self::HTTP_BASE_URL;
		$this->client = new Client();
	}

	/**
	 * Magic function for the api request
	 *
	 * @param string $fnName
	 * @param [string, array] $arguments
	 * @param [string, array] string html|url|text
	 * @param [string, array] array query string
	 * @return String
	 */
	public function __call($fnName, array $arguments)
	{
		if (!is_array($arguments[1])) {
			throw new \Exception($fnName . ' argument 2 must be array.');
		}

		$params = array_merge([
			'outputMode' => 'json',
			'apikey' => $this->apiKey,
		], $arguments[1]);

		$middle = '/' . $arguments[0] . '/' . ucfirst($arguments[0]);

		$url = $this->baseUrl . $middle . ucfirst($fnName) . '?' . urldecode(http_build_query($params));
		$res = $this->client->request('POST', $url);
		return json_decode((string)$res->getBody(), true);
	}
}
