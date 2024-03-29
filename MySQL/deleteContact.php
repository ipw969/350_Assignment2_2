<?php
require_once '../ContactsFactory.php';



	$contactId = $_GET['contactId'];
	
	//Validate the id on the server side to make sure it isn't erroneous. 
	if (! gettype($contactId) == 'integer')
	{
		echo "The given contact id is not of an integer type. This means it may contain characters, or other erroneous code.";
	}
	else
	{
			session_start();
			$dsn = $_SESSION["serverName"].";".$_SESSION['database'];
            $username = $_SESSION["userName"];
            $password = $_SESSION['password'];
			$errorMessage = "";
			$error = false;
			$conn;
			try 
			{
				$conn = new PDO($dsn, $username, $password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                ContactsFactory::deleteContact($conn,$contactId);
            } 
			catch (PDOException $e) 
			{
				$error = true;
                $errorMessage = $errorMessage."\nFailed to connect to the database for the following reason\n".$e->getMessage();
            }
			

		
			if (!$error)
			{
				echo "Successfully Removed The Contact From the Database.";
			}
			else
			{
				echo "failed to save the contact to the database with the following errors.: ".$errorMessage;
			}

			header("Location: ../ContactPage.php"); //redirect the browser to the beginning page.
	}

?>