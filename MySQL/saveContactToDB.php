<?php
/**This is where the magic happens. This page recieved a request, decides what it is,
has the ContactsFactory do some fancy validation, then selects the task to accomplish.
This file also lists plenty of debugging information.*/

require_once '../Contact.php';
require_once '../ContactsFactory.php';

/*
//debugging.
echo "first name: ".$_POST['firstName']."\n";
echo "last name:".$_POST['lastName']."\n";
echo "phone number:".$_POST['phoneNumber']."\n";
echo "email:".$_POST['email']."\n";
echo "company".$_POST['company']."\n";
echo "region:".$_POST['region']."\n";
echo "town name:".$_POST['town']."\n";
echo "country:".$_POST['country']."\n";
echo "url:".$_POST['url']."\n";
echo "birthday:".$_POST['birthday']."\n";
echo "date".$_POST['date']."\n";
echo "building number:".$_POST['buildingNumber']."\n";
echo "street name:".$_POST['streetName']."\n";
echo "postal code:".$_POST['postalCode']."\n";
echo "Transaction Type:".$_POST['transactionType']."\n";
echo "Note:".$_POST['notes']."\n";
*/

if ($_POST['transactionType'] == "update")
{
	$contact = new Contact($_POST['contactId'], $_POST['firstName'], $_POST['lastName'],
           $_POST['company'], $_POST['phoneNumber'], $_POST['email'], $_POST['url'], 
            $_POST['buildingNumber'], $_POST['streetName'], $_POST['town'], 
            $_POST['region'], $_POST['country'], $_POST['postalCode'], $_POST['birthday'], 
            $_POST['date'], $_POST['notes']);
}
else if ($_POST['transactionType'] == "insert")
{
	$contact = new Contact(null, $_POST['firstName'], $_POST['lastName'],
           $_POST['company'], $_POST['phoneNumber'], $_POST['email'], $_POST['url'], 
            $_POST['buildingNumber'], $_POST['streetName'], $_POST['town'], 
            $_POST['region'], $_POST['country'], $_POST['postalCode'], $_POST['birthday'], 
            $_POST['date'], $_POST['notes']);
}
/*
//debugging
echo var_dump($contact);
*/			
			session_start();
			$dsn = $_SESSION["serverName"].";".$_SESSION['database'];
            $username = $_SESSION["userName"];
            $password = $_SESSION['password'];
			$errorMessage = null;
			$error = false;
			$conn;
			try 
			{
				$possibleError = ContactsFactory::validateContact($contact);

	
                $conn = new PDO($dsn, $username, $password, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
				
				
            } 
			catch (PDOException $e) 
			{
				$error = true;
                $errorMessage = $errorMessage."\nFailed to connect to the database for the following reason\n".$e->getMessage();
            }
			

		
if (!$error  && $errorMessage == null && $possibleError == null)
{
				if ($_POST['transactionType'] == "insert")
				{
					$errorMessage = ContactsFactory::saveContact($conn, $contact);
					
				}
				else if ($_POST['transactionType'] == "update")
				{					
					$errorMessage = contactsFactory::updateContact($conn,$contact);
				}
}
else
{
	echo "SERVER: failed to save the contact to the database with the following errors.: ".$errorMessage."\n".$possibleError;
}

?>