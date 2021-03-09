<?php
/*
    By xr4zz3rs - realitycheats.com
*/
 
class CSRF {
	public function __construct(){
		$this->getToken = "CSRF-Token";
		$this->getExpire = "CSRF-Expire";
		$this->getCookie = "X-CSRF-TOKEN";
		$this->tokenTime = 600; /* Expire in seconds */
		@ob_start();
		@session_start();
	}
	private function isValid($element){
		return array_key_exists($element, $_SESSION) ? true : false;
	}
	private function getElement($element){
		if($this->isValid($element))
			return $_SESSION[$element];
	}
	private function create(array $parameters = []){
		if(!empty($parameters)){
			foreach($parameters as $element => $value){
				$_SESSION[$element] = $value;
			}
		}
	}
	private function checkExpire(){
		if($this->isValid($this->getExpire)){
			$timeout = time() - $this->getElement($this->getExpire);
			return $timeout < $this->tokenTime ? true : false;
		}
		return false;
	}
	private function checkClient(){
		if(isset($_COOKIE[$this->getCookie])){
			$client = explode('refCS', $this->getElement($this->getToken));
			$ipAddress = $client[1];
			$userAgent = $client[2];
			return password_verify($this->getElement($this->getToken), $_COOKIE[$this->getCookie]) && $this->getIP() == $ipAddress && $this->getUA() == $userAgent ? true : false;
		}
		return false;
	}
	private function getIP(){
		$headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR'];
		foreach($headers as $header){
			$IPHeader = !empty($_SERVER[$header]) ? $header : 'REMOTE_ADDR';
			if(filter_var($_SERVER[$IPHeader], FILTER_VALIDATE_IP)) {
				$IP = str_replace(array('.',':','::'), array('','',''), $_SERVER[$IPHeader]);
					return $IP;
			}
		}
		return "127001";
	}
	private function getUA(){
		$UA = isset($_SERVER['HTTP_USER_AGENT']) ? md5(preg_replace('/[^A-Za-z0-9\-]/', '', $_SERVER['HTTP_USER_AGENT'])) : md5(rand());
		return $UA;
	}
	private function newToken(){
		$token = md5(uniqid(rand(), true)) . session_id() . count($_SESSION) . count($_SERVER) . "refCS" . $this->getIP() . "refCS" . $this->getUA();
		return $token;
	}
	private function endToken(){
		foreach([$this->getToken, $this->getExpire] as $element){
			unset($_SESSION[$element]);
		}
	}
	public function getToken(){
		switch(true){
			case !$this->isValid($this->getToken):
			case !$this->checkExpire():
				$this->create([
					$this->getToken => $this->newToken(),
					$this->getExpire => time()
				]);
				setcookie($this->getCookie, password_hash($this->getElement($this->getToken), PASSWORD_DEFAULT), time()+$this->tokenTime, "/");
			default:
				return $this->getElement($this->getToken);	
			break;
		}
	}
	public function checkToken($value){
		switch(true){
			case !$this->isValid($this->getToken):
			case !$this->checkExpire():
			case !$this->checkClient():
			case $value != $this->getElement($this->getToken):
				$this->endToken();
				return false;
			break;
			default:
				$this->endToken();
				return true;
			break;
		}
	}
}
?>