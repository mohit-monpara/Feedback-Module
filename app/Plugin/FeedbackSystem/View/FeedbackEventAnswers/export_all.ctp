<?php
$date = date("d/m/Y");
$filename = "FeedbackEventAnswer".$date.".xls";
$contents = "Sr.No.\t Time of Feedback\t Question\t Answer\t User\t \n";
echo $contents;
$i=0;

foreach ($feedbackEventAnswers as $FeedbackEventAnswers)
{
  
  $contents = ++$i."\t".$FeedbackEventAnswers['FeedbackEventAnswer']['created']."\t".$FeedbackEventAnswers['FeedbackEventQuestion']['text']."\t".$FeedbackEventAnswers['FeedbackEventAnswer']['answer']."\t".$FeedbackEventAnswers['User']['username']."\t \n";
  echo $contents;
}
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
header("Pragma: no-cache"); 
header("Expires: 0");
//echo $contents;
 ?>