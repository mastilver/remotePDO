<?php
	
	$conn = new PDO('oci:host=spartacus;dbname=info', $_POST['username'], $_POST['password'])
		or die('connection impossible, veuillez vérifier vos identifiants');

	if(isset($_POST['data']))
	{
		$data = json_decode($_POST['data']);
	}
	else
	{
		$data = array();
	}

	$result = $conn->prepare($_POST['query']);
	$result->execute($data);

	echo json_encode($result->fetchAll());
?>