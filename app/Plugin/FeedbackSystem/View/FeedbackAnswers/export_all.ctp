<?php
$date = date("d/m/Y");
$filename = "FeedbackAnswer".$date.".xls";
$contents = "Sr.No.\t Time of Feedback\t Question\t Answer\t User\t \n";
echo $contents;
$i=0;

foreach ($feedbackAnswers as $FeedbackAnswers)
{
  
  $contents = ++$i."\t".$FeedbackAnswers['FeedbackAnswer']['created']."\t".$FeedbackAnswers['FeedbackQuestion']['text']."\t".$FeedbackAnswers['FeedbackAnswer']['answer']."\t".$FeedbackAnswers['User']['username']."\t \n";
  echo $contents;
}
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
header("Pragma: no-cache"); 
header("Expires: 0");
//echo $contents;
 ?>