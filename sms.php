<?php

    //Respond to Twilio with proper header
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    
    require "voting.php";
    require "database.php";
    
    //Save user phone number, and message sent to server
    $from = ( $_REQUEST[ 'From' ] );
    $message =  (string)trim(strip_tags( $_REQUEST[ 'Body' ] ));
    
    //Replace {admin} with poll owner's phone number, using format "+5555555555"
    $adminNumber="{admin}";
    
    //Open voting as admin
    if(($from == $adminNumber) && ($message == "Open")) {
        $stmt = $mysqli->prepare("UPDATE open SET isopen=?");
        $stmt->bind_param('s',$message);
        $stmt->execute();
        $stmt->close();
        send_text($from, "Voting is now open");
    }
    
    //Close voting as admin
    elseif(($from == $adminNumber) && ($message == "Close")) {
        $stmt = $mysqli->prepare("UPDATE open SET isopen=?");
        $stmt->bind_param('s',$message);
        $stmt->execute();
        $stmt->close();
        send_text($from, "Voting is now closed");
    }
    
    //If not the admin
    else{
        //Check if voting is allowed
        $stmt = $mysqli->prepare("SELECT isopen FROM open");
        $stmt->bind_result($isOpen);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        if($isOpen == "Open"){
            
            //Vote if the input is a valid vote
            if (isValid($message)){
                vote($from, $message);
            }
            
            //Respond with voting instructions if the user requested it
            else if (($message == "Vote") || ($message == "vote")) {
                send_choices($from);
            }
            
            //Respond with instructions for checking voting options if input is not valid
            else if (!empty($from)){
                send_text($from, "Text \"Vote\" to see options");
            }
            
            //Failing all else, quit
            else {
                exit();
            }
        }
        
        //If admin has closed voting, inform the user and do not change database
        else{
            send_text($from, "We're sorry, but voting is not open at this time");
        }
    }
?>