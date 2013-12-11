<?php
    
    //Check if input is a valid vote
    function isValid($input) {
        require "database.php";
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM choices");
        if(!$stmt){
        	printf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
        }
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if ((0<$input) && ($input <= $count)){
            return true;
        }
        else {
            return false;
        }
    }
    
    //Perform database operations to create or update vote
    function vote($number, $vote) {
        require "database.php";
	//Check if phone number has voted before
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM votes WHERE number = ?");
        if(!$stmt){
        	printf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
        }
        $stmt->bind_param('s', $number);
        $stmt->bind_result($voted);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
	
	//If number has voted before, update database entry instead of creating new row
        if((int)$voted != 0) {
            $stmt = $mysqli->prepare("UPDATE votes SET vote = ? WHERE number = ?");
            if(!$stmt){
        	printf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
            }
            $stmt->bind_param('is', $vote, $number);
            $stmt->execute();
            $stmt->close();
            update_vote($number, $vote);
        }
	
	//If number has not voted, create new entry for that number
        else {
            $stmt = $mysqli->prepare("INSERT INTO votes (number, vote) VALUES (?, ?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('si', $number, $vote);
            $stmt->execute();
            $stmt->close();
            
            new_vote($number, $vote);
        }
    }
    
    //Respond to user with result of their updated vote
    function update_vote($number, $vote) {
        require "database.php";
        $stmt = $mysqli->prepare("SELECT name FROM choices WHERE id=?");
        $stmt->bind_param('i',$vote);
        $stmt->bind_result($voteName);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $message = "Your vote has been updated to ".$voteName;
        return send_text($number, $message);
    }
    
    //Respond to user with result of their new vote
    function new_vote($number, $vote) {
        require "database.php";
        $stmt = $mysqli->prepare("SELECT name FROM choices WHERE id=?");
        $stmt->bind_param('i',$vote);
        $stmt->bind_result($voteName);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        $message = "Your vote for ".$voteName." has been saved";
        return send_text($number, $message);
    }
    
    //Handles all messages sent to user
    function send_text($number, $message) {
        require "twilio/Services/Twilio.php";
	
	//Enter proper AccountSid and AuthToken (From account page on Twilio)
        $AccountSid = 'AccountSid';
        $AuthToken = 'AuthToken';
        $client = new Services_Twilio($AccountSid, $AuthToken);
        
        $sms = $client->account->sms_messages->create(
	    //Replace "Twilio number" with the Twilio number you want the reply to come from
            "Twilio Number",
            $number,
            $message
        );
        return true;
    }
    
    //Generate message of all possible voting choices to send to user
    function send_choices($number) {
        require "database.php";
        $stmt = $mysqli->prepare("SELECT id, name FROM choices");
        $stmt->bind_result($id, $name);
        $stmt->execute();
        $message = "";
        while($stmt->fetch()) {
            $message = $message."Text ".$id." to vote for ".$name."\n";
        }
        send_text($number, $message);
        return true;
    }
    
?>