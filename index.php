<!DOCTYPE html>
    <html>
        <head>
            <style type="text/css">
                table.gridtable {
                    font-family: verdana,arial,sans-serif;
                    font-size:11px;
                    color:#333333;
                    border-width: 1px;
                    border-color: #666666;
                    border-collapse: collapse;
                }
                table.gridtable th {
                    border-width: 1px;
                    padding: 8px;
                    border-style: solid;
                    border-color: #666666;
                    background-color: #dedede;
                }
                table.gridtable td {
                    border-width: 1px;
                    padding: 8px;
                    border-style: solid;
                    border-color: #666666;
                    background-color: #ffffff;
                }
                .graph {
                    background-color: #C8C8C8;
                    border: solid 1px black;
                    width: 600px;
                }
      
                .graph td {
                    font-family: verdana, arial, sans serif;
                }
      
                .graph thead th {
                    border-bottom: double 3px black;
                    font-family: verdana, arial, sans serif;
                    padding: 1em;
                }
    
                .graph tfoot td {
                    border-top: solid 1px #999999;
                    font-size: x-small;
                    text-align: center;
                    padding: 0.5em;
                    color: #666666;
                }

                .bar {
                    background-color: white;
                    text-align: right;
                    border-left: solid 1px black;
                    padding-right: 0.5em;
                    width: 400px;
                }
      
                .bar div { 
                    border-top: solid 2px #0077DD;
                    background-color: #004080;
                    border-bottom: solid 2px #002266;
                    text-align: right;
                    color: white;
                    float: left;
                    padding-top: 0;
                    height: 1em;
                }
      
                body {
                    background-color: white;
                }

            </style>
        </head>
        <body>
            <table class="gridtable">
                <tr><th></th><th>Name</th><th>Votes</th></tr>
            <?php
                $votes[0] = 0;
                $total = 0;
                $names[0] = "default";
                require "database.php";
                $stmt = $mysqli->prepare("SELECT COUNT(*) FROM choices");
                $stmt->bind_result($count);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                for ($x=1; $x <= $count; $x++) {
                    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM votes WHERE vote=?");
                    $stmt->bind_param('i', $x);
                    $stmt->bind_result($numVotes);
                    $stmt->execute();
                    $stmt->fetch();
                    $stmt->close();
                    $votes[$x] = $numVotes;
                    $total = $total + $numVotes;
                }
                $stmt = $mysqli->prepare("SELECT id, name FROM choices");
                $stmt->bind_result($id, $name);
                $stmt->execute();
                $i=1;
                while($stmt->fetch()) {
                    $names[$id] = $name;
                    echo "<tr><td>$id</td><td>$name</td><td>$votes[$i]</td></tr>";
                    $i = $i+1;
                }
                echo "</table>";
                $stmt->close();
                echo "<table width=\"530\" class=\"graph\" cellspacing=\"6\" cellpadding=\"0\">";
                echo "<thead>
                    <tr><th colspan=\"3\">Voting Results</th></tr>
                    </thead>
                    <tbody>";
                for ($x=1; $x <= $count; $x++) {
                    $percent = (int)(100*$votes[$x]/$total);
                    $pstring = $percent."%";
                    echo "<tr><td>$names[$x]</td><td class=\"bar\"><div style=\"width: $pstring\"</div>$votes[$x]</td><td>$percent %</td></tr>";
                }
                echo "</tbody>";
            ?>
            </table>
        </body>
    </html>
