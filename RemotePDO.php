<?php

require_once(__DIR__ . '/serverInfo.php');

class RemotePDO
{
	private $username;
	private $password;

	private $statement;


	public function RemotePDO($unusedVar, $username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}

	public function prepare($sqlQuery)
	{
		return new RemotePDOStatement($sqlQuery, $this->username, $this->password);
	}


	public function query($sqlQuery)
	{
		$statement = new RemotePDOStatement($sqlQuery, $username, $password);

		$statement->serverRequest();

		return $statement->fetchAll();
	}



	public function exec()
	{
		echo '<h1> la méthode remotePDO::exec n\'est pour le moment pas implémenté</h1>';
		return 0;
	}

	
}


///////////////////////////////////////////////////////
					/////////////
//////////////////////////////////////////////////////



class RemotePDOStatement
{
	private $username;
	private $password;

	private $query;
	private $requestData;

	private $fetchedData;
	private $indexFetchedData;

	public function RemotePDOStatement($query, $username, $password)
	{
		$this->username = $username;
		$this->password = $password;

		$this->query = $query;

		$this->requestData = array();

		$this->fetchedData = array();
		$this->indexFetchedData = -1;
	}

	public function execute($requestData = array())
	{
		foreach ($requestData as $parameter => $variable)
		{
			$this->bindParam(':' . $parameter, $variable);
		}

		return $this->serverRequest();
	}

	public function bindParam(mixed $parameter, mixed &$variable)
	{
		//delete the first ':'
		$parameter =preg_replace('#^:(.+)$#', '$1', $parameter);

		$requestData[$parameter] = $variable;
	}






	public function fetch()
	{
		$this->indexFetchedData++;

		if($this->indexFetchedData >= count($this->fetchedData))
		{
			return false;
		}

		return $this->fetchedData[$this->indexFetchedData];
	}

	public function fetchAll()
	{
		return $this->fetchedData;
	}




	public function serverRequest()
	{
		$content = array
		(
			'query' => $this->query,
			'data' => $this->requestData,
			'username' => $this->username,
			'password' => $this->password
		);

		$context = $context = stream_context_create(array
		(
			'http' => array
			(
				'header' => "Authorization: Basic "  . base64_encode(USERNAME . ':' . PASSWORD) . "\r\nContent-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($content)
			)
		));

		$result = file_get_contents(URL, false, $context);

		if($result === false)
		{
			return false;
		}



		$this->fetchedData = json_decode($result, true);

		return true;
	}
}

?>