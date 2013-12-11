<?php

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    
    require "voting.php";
    require "database.php";
    
    $from = ( $_REQUEST[ 'From' ] );
    $message =  (string)trim(strip_tags( $_REQUEST[ 'Body' ] ));
    
    //Replace {admin} with poll owner's phone number, using format "+5555555555"
    $adminNumber="{admin}";
    if(($from == $adminNumber) && ($message == "Open")) {
        $stmt = $mysqli->prepare("UPDATE open SET isopen=?");
        $stmt->bind_param('s',$message);
        $stmt->execute();
        $stmt->close();
        send_text($from, "Voting is now open");
    }
    elseif(($from == $adminNumber) && ($message == "Close")) {
        $stmt = $mysqli->prepare("UPDATE open SET isopen=?");
        $stmt->bind_param('s',$message);
        $stmt->execute();
        $stmt->close();
        send_text($from, "Voting is now closed");
    }
    else{
        $stmt = $mysqli->prepare("SELECT isopen FROM open");
        $stmt->bind_result($isOpen);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        if($isOpen == "Open"){
            if (isValid($message)){
                vote($from, $message);
            }
            else if (($message == "Vote") || ($message == "vote")) {
                send_choices($from);
            }
            else if (!empty($from)){
                send_text($from, "Text \"Vote\" to see options");
            }
            else {
                exit();
            }
        }
        else{
            send_text($from, "We're sorry, but voting is not open at this time");
        }
    }
?>