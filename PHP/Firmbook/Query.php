<?php
/**
 * Description of Query
 *
 * @author Stas
 */
class Firmbook_Query {
	protected $host;
	protected $query;
	protected $publicKey;
	protected $privateKey;
	protected $top;
	protected $skip;
		
	public function __construct($host, $query, $privateKey, $publicKey, $top, $skip) {
		$this->host = $host;
		$this->query = $query;
		$this->privateKey = $privateKey;
		$this->publicKey = $publicKey;
		$this->top = $top;
		$this->skip = $skip;		
	}
	
	public function getSignature($url) {
		return sha1($url.$this->privateKey);
	}
	
	public function getResult() {
		try {
			$headers = array(
				"Accept" => 'application/json',
				"Host" => $this->host
			);
			$url = "http://".$this->host.$this->query.'?$top='.$this->top.'&$skip='.$this->skip;
			$request = new HTTP_Request2($url, HTTP_Request2::METHOD_GET);
			$request->setAuth($this->publicKey, $this->getSignature($this->query.'?$top='.$this->top.'&$skip='.$this->skip), HTTP_Request2::AUTH_BASIC);
			$request->setHeader($headers);
			$response = $request->send();
			return new Firmbook_Command_Result(
					$response->getStatus(), $response->getReasonPhrase(), $response->getBody());
		} catch (Exception $e) {
			return new Firmbook_Command_Result($e->getCode(), $e->getMessage());			
		}
	}
}
?>