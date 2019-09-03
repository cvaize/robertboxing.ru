<?php

namespace App\Models\Apis;

//use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SmsApi {
	/**
	 * @var
	 */
	protected $guzzleClient;

	protected $cacheName = 'smscRU.balance';

	/**
	 * @return null|string
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getBalance(): ?string {
		$guzzleClient = $this->getClient();
		$balance = null;
		$login = $this->getLogin();
		$password = $this->getPassword();
		$cacheName = $this->getCacheName();

		if (!$this->isCached($cacheName)) {
			try {
				$response = $guzzleClient->request('GET', 'https://smsc.ru/sys/balance.php', [
					'query' => [
						'login' => $login,
						'psw' => $password,
						'fmt' => 3
					]
				]);
				$result = json_decode($response->getBody(), true);
				$balance = $result['balance'] ?? 'Error';

				if ($balance === 'Error') {
					Log::critical('method SmsApi@getBalance failed - ' . $result['error']);
				} else {
					$balance = explode('.', $balance)[0];
				}

				$this->setCache($cacheName, $balance);
			} catch (ClientException $exception) {
				Log::critical('method SmsApi@getBalance failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
			}
		} else {
			$balance = $this->getCache($cacheName);
		}

		return $balance;
	}

	/**
	 * @return array
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function isAccessible(): array {
		$balance = $this->getBalance();
		$result = [];

		if ($balance !== 'Error') {
			$result['OK'] = true;
		} else {
			$result['OK'] = false;
		}

		return $result;
	}

	/**
	 * @return Client
	 */
	private function getClient(): Client {
		if (null === $this->guzzleClient) {
			try {
				$this->guzzleClient = new Client([
					'timeout' => 5,
					'http_errors' => false
				]);
			} catch (ClientException $exception) {
				Log::critical('method SmsApi@getClient failed', ['message' => $exception->getMessage(), 'line' => $exception->getLine(), 'code' => $exception->getCode()]);
			}
		}

		return $this->guzzleClient;
	}

	/**
	 * @return string
	 */
	private function getLogin(): string {
		return env('SMSCRU_LOGIN');
	}

	/**
	 * @return string
	 */
	private function getPassword(): string {
		return env('SMSCRU_SECRET');
	}

	/**
	 * @return int|null
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function getAvailableSmsQuantity(): ?int {
		$balance = intval($this->getBalance());

		return $balance / 1.7;
	}

	/**
	 * @return string
	 */
	private function getCacheName(): string {
		return $this->cacheName;
	}

	/**
	 * @param string $value
	 */
	private function setCache(string $cacheName, string $value) {
		Cache::put($cacheName, $value, Carbon::now()->addMinute());
	}

	/**
	 * @param string $cacheName
	 * @return mixed
	 */
	private function getCache(string $cacheName) {
		return Cache::get($cacheName);
	}

	/**
	 * @param string $cacheName
	 * @return bool
	 */
	private function isCached(string $cacheName): bool {
		$data = Cache::get($cacheName);

		return (null === $data) ? false : true;
	}
}
