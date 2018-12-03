<?php
	// YoBit php api wrapper created by pop999		
	// Code is testeted
	//BTC: 17HV8gPV9atBi5KjFCe6r2NDtwqLHaq2Ji
	//LTC: LXPTJW8eJMHQVzwnJbY7ukfQ6sM6pJpJe1
	//ETH: 0x0921eeed3f8dbf8da2e587e8c648a6f546c4ffe4

	class API {
		protected $api_key;
		protected $api_secret;
		protected $trading_url = "https://yobit.net/tapi/";
		protected $public_url = "https://yobit.net/api/3/";
		
		public function __construct($api_key, $api_secret) {
			$this->api_key = $api_key;
			$this->api_secret = $api_secret;
		}
			
		private function query(array $req = array()) {
			// API settings
			$key = $this->api_key;
			$secret = $this->api_secret;
		 
			// generate a nonce file if not exist
			if (!file_exists('nonce')) {
			    file_put_contents('nonce', 1);
			} 
			
			// when nonce over maximum 2147483646 you must make new api key and secret and edit nonce file manualy to 1
			
			$req['nonce'] = file_get_contents('nonce');
			if ($req['nonce'] > 2146083646) {
				echo "Nonce is approaching the maximum.You need to make new api key soon as posibile!!!\n";
			}elseif ($req['nonce'] > 2147483646) {
				echo "Your nonce has passed the maximum.You need to make new api key and api secret.\n";
				echo "When you add new api key and secret, edit 'nonce' file manualy to 1\n";
			}
			$noncePlus = $req['nonce']+1;
			file_put_contents('nonce', $noncePlus);
		 
			// generate the POST data string
			$post_data = http_build_query($req, '', '&');
			$sign = hash_hmac('sha512', $post_data, $secret);
		 
			// generate the extra headers
			$headers = array(
				'Key: '.$key,
				'Sign: '.$sign,
			);

			// curl handle (initialize if required)
			static $ch = null;
			if (is_null($ch)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, 
					'Mozilla/4.0 (compatible; yoBit PHP bot; '.php_uname('a').'; PHP/'.phpversion().')'
				);
			}
			curl_setopt($ch, CURLOPT_URL, $this->trading_url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			// run the query
			$res = curl_exec($ch);

			if ($res === false) throw new Exception('Curl error: '.curl_error($ch));
			//echo $res;
			$dec = json_decode($res, true);
			if (!$dec){
				//throw new Exception('Invalid data: '.$res);
				return false;
			}else{
				return $dec;
			}
		}
		
		protected function retrieveJSON($URL) {
			$opts = array('http' =>
				array(
					'method'  => 'GET',
					'timeout' => 10 
				)
			);
			$context = stream_context_create($opts);
			$feed = file_get_contents($URL, false, $context);
			$json = json_decode($feed, true);
			return $json;
		}
		// next functions are for trading api
		public function getInfo() {
			return $this->query( 
				array(
					'method' => 'getInfo'
				)
			);
		}
		public function balance() {
			$balance = $this->getInfo();
			return $balance['return']['funds_incl_orders'];
			
		}
		public function avilableBalance() {
			$balance = $this->getInfo();
			return $balance['return']['funds'];
			
		}		
		public function openOrders($pair) {		
			return $this->query( 
				array(
					'method' => 'ActiveOrders',
					'pair' => $pair
				)
			);
		}
		
		public function tradeHistory($pair) {
			return $this->query(
				array(
					'method' => 'TradeHistory',
					'pair' => $pair
				)
			);
		}
		
		public function buy($pair, $amount, $rate) {
			return $this->query( 
				array(
					'method' => 'Trade',
					'type' => 'buy',	
					'pair' => $pair,
					'rate' => $rate,
					'amount' => $amount
				)
			);
		}
		
		public function sell($pair, $amount, $rate) {
			return $this->query( 
				array(
					'method' => 'Trade',
					'type' => 'sell',	
					'pair' => $pair,
					'rate' => $rate,
					'amount' => $amount
				)
			);
		}
		
		public function cancelOrder($order_id) {
			return $this->query( 
				array(
					'method' => 'CancelOrder',						
					'order_id' => $order_id
				)
			);
		}
		public function orderInfo($order_id) {
			return $this->query( 
				array(
					'method' => 'OrderInfo',						
					'order_id' => $order_id
				)
			);
		}
		public function getDepositAddress($coin, $need_new=0) {
			return $this->query( 
				array(
					'method' => 'GetDepositAddress',						
					'coinName' => $coin, // like BTC
					'need_new'=>$need_new // if you need new deposit adress send $need_new =1
				)
			);
		}
		public function withdraw($coin, $amount,$adress) {
			return $this->query( 
				array(
					'method' => 'WithdrawCoinsToAddress',						
					'coinName' => $coin, // like BTC
					'amount'=>$amount, 
					'address' => $adress

				)
			);
		}
		
		
		// next functions are for public api
		public function info() {
			$trades = $this->retrieveJSON($this->public_url.'/info');
			return $trades;
		}
		
		public function depth($pair) {
			// exemples of pair "ltc_btc"
			// you can also send multiple pairs separate with "-". For exemple "ltc_btc-eth_btc"
			$orders = $this->retrieveJSON($this->public_url.'/depth/'.$pair);
			return $orders;
		}
		
		public function ticker($pair) {
			// exemples of pair "ltc_btc"
			// you can also send multiple pairs separate with "-". For exemple "ltc_btc-eth_btc"
			$volume = $this->retrieveJSON($this->public_url.'/ticker/'.$pair);
			return $volume;
		}
	
		
		public function trades($pair) {
			// exemples of pair "ltc_btc"
			// you can also send multiple pairs separate with "-". For exemple "ltc_btc-eth_btc"
			$tickers = $this->retrieveJSON($this->public_url.'/trades/'.$pair);
			return array_keys($tickers);
		}
		
		
	}
	
?>
