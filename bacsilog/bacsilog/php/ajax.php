<?php
	session_start();
    include 'conn.php';

    date_default_timezone_set("Asia/Manila");
    $t = time();
	$thisD = date("Y-m-d",$t);
    $thisT = date("h:i A",$t); 

    $data = json_decode(file_get_contents("php://input"));

    $request = $data->request;

    if($request == "saveOrder"){
        $orderNumber = $data->orderNumber;
        $qty = $data->totalQty;
        $price = $data->totalPrice;
        $newsql = "SELECT * FROM `orders` WHERE order_number = '$orderNumber'";
        $newrs = mysqli_query($conn,$newsql);
       
        if(mysqli_num_rows($newrs) > 0){
            echo "already exists."; 
        }else{
            mysqli_query($conn,"INSERT INTO `orders` (`order_date`,`order_time`,`total_qty`,`total_price`,`order_number`) VALUES('$thisD','$thisT','$qty','$price','$orderNumber')");
            echo "Insert successfully";
        }
    }

    if($request == "deleteAll"){
      
		$f_sql = "DELETE FROM orders";
		$f_rs = mysqli_query($conn,$f_sql);
        if ($f_rs == true) {
			echo 'Already Removed';
		}else {
			echo 'Error Removed';
			
		}
    }
?>
