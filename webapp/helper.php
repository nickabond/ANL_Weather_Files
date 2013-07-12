<?php
if(!$_GET['Date']) {
     $output = "alert('Input pattern incorrect');\n";
}
//error if cannot connect to db server
elseif(!$link = mysql_connect('localhost', 'anluser', 'tdata97')) {
     $output = "alert('Could not connect to database');\n";
}
//error if cannot select database
elseif(!mysql_select_db('ANLTower1')) {
     $output = "alert('Could not select database');\n";
}
else {
     //error if query for child records cannot be parsed
     if(!$rs = mysql_query("SELECT DISTINCT Time FROM ANL4 WHERE Date = '$_GET[Date]'")) {
          $output = "alert('Error getting Time list from database');\n";
     }
     //error if no matching records found for state code
     elseif(mysql_num_rows($rs) == 0) {
          $output = "alert('No records found');\n";
     }
     else {
          //if child records found, output results to JavaScript array of Option objects
          $i = 0;
          while($row = mysql_fetch_array($rs)) {
               //create a non-selectable separator every five entries
               if($i % 5 == 0) {
                    $output .= "TimeOptions.push(new Option('--------------------', ''));\n";
               }
               $output .= "TimeOptions.push(new Option('$row[Time]', '$row[Time]'));\n";
               $i++;
          }
     }
}
//return results
header('Content-type: text/plain');
echo $output;
?>
