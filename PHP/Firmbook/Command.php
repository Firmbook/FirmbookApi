<?php
/**
 * Description of Firmbook_Query
 *
 * @author Stas
 */
class Firmbook_Command {
	protected $host;
	protected $commandName;
	protected $serializer;
	protected $publicKey;
	protected $privateKey;

	public function  __construct($host, $commandName, $privateKey, $publicKey, Firmbook_Serializer_Abstract $serializer) {
		$this->host = $host;
		$this->commandName = $commandName;
		$this->serializer = $serializer;
		$this->privateKey = $privateKey;
		$this->publicKey = $publicKey;
	}	
	
	protected function createRequestParams(array $data) {
		$body = http_build_query($this->serializer->getContent());
		$signature = sha1($body.$this->privateKey);
		return array(
			'body' => $body,
			'signature' => $signature
		);
	}

	public function getResult() {
		try {
			$params = $this->createRequestParams($this->serializer->getContent());
			$url = "http://".$this->host.$this->commandName;
			$headers = array(
				"Content-type" => $this->serializer->getMimeType(),
				"Host" => $this->host
			);
			
			$request = new HTTP_Request2($url, HTTP_Request2::METHOD_POST);
			$request->setAuth($this->publicKey, $params['signature'], HTTP_Request2::AUTH_BASIC);
			$request->setHeader($headers);
			$request->setBody($params['body']);
			$response = $request->send();
//			if ($response->getStatus() != 200)
//				throw new Exception($response->getReasonPhrase(), $response->getStatus());
			return new Firmbook_Command_Result(
					$response->getStatus(), $response->getReasonPhrase(),$response->getBody());
		} catch (Exception $e) {
			return new Firmbook_Command_Result($e->getCode(), $e->getMessage());
		}
	}

}
?>