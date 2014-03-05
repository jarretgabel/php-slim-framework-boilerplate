<?php

class SaltShaker {

	public static function shake($password) {
		
		$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); //get 256 random bits in hex
		$hash = hash("sha256", $salt . $password); //prepend the salt, then hash
		//store the salt and hash in the same string, so only 1 DB column is needed
		$final = $salt . $hash; 
		
		return $final;
		
	}
	
	//Validates a password
	//returns true if hash is the correct hash for that password
	//$hash - the hash created by HashPassword (stored in your DB)
	//$password - the password to verify
	//returns - true if the password is valid, false otherwise.
	public static function validate($password, $correctHash) {
	
	    $salt = substr($correctHash, 0, 64); //get the salt from the front of the hash
	    $validHash = substr($correctHash, 64, 64); //the SHA256
	    $testHash = hash("sha256", $salt . $password); //hash the password being tested
	
	    //if the hashes are exactly the same, the password is valid
		$isCorrect = 0;
		if ($testHash === $validHash) {
			$isCorrect = 1;
		}
	
	    return $isCorrect;
	    
	}

}

?>