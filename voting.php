<?php
    
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
    
    function vote($number, $vote) {
        require "database.php";
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
    
    function send_text($number, $message) {
        require "twilio/Services/Twilio.php";
        $AccountSid = AC4fa1dc13518ebb932ae710ed866ff311;
        $AuthToken = c37897b809d3df500af84c285d07fae4;
        $client = new Services_Twilio($AccountSid, $AuthToken);
        
        $sms = $client->account->sms_messages->create(
            "608-467-1480",
            $number,
            $message
        );
        return true;
    }
    
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