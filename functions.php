<?php

function line_to_scr($line) {
  global $userFile;
  $line_arr=explode("✕",$line);
  $proFile=file("$userFile");
  $color='#000000';
  if (is_array($proFile)){
    foreach ($proFile as $value) {
      if (strpos($value,$line_arr[0])===false){

      }
      else {
        $proLine=explode("✕",$value);
        $color=$proLine[1];
      }
    }
  }
  echo $line_arr[1];
  echo ("<font color='$color'>$line_arr[2]</font>"."<br>");
}


function log_to_screen($chatFile) {
  $scrFile=file($chatFile);
  $lines=count($scrFile);
  if ($lines>10) {
    $startlines=$lines-10;
  }
  else {
    $startlines=0;
  }
  for ($i=$startlines; $i < $lines; $i++) {
    line_to_scr($scrFile[$i]);
  }
}
