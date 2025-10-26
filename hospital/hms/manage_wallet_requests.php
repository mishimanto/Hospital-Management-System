<?php

include('include/config.php');
$result = mysqli_query($con, "SELECT * FROM wallet_requests");

while($row = mysqli_fetch_assoc($result))
{
  echo "User: ".$row['user_id']." | Amount: ".$row['amount']." | Status: ".$row['status']." ";

  if($row['status']=='Pending')
  {
    echo "<a href='approve_wallet_request.php?id=".$row['id']."'>Approve</a>";
  }
  echo "<br>";
}

?>
