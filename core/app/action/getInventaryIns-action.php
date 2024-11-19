<?php
$conn = Database::getCon();

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;







$columns = array( 
// datatable column index  => database column name
	0 => 'barcode',
    1 => 'name', 
	2 => 'price_in'
	 
);

// getting total number records without any search
$sql = "SELECT id, name, type,minimum_inventory  FROM product WHERE type='INSUMOS'";

$query=mysqli_query($conn, $sql) or die("./?action=inventory/get-products: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT  id, name, type,minimum_inventory ";
	$sql.=" FROM product";
	$sql.=" WHERE  type='INSUMOS'";
	$sql.=" AND name LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR barcode LIKE '".$requestData['search']['value']."%' ";
	
	$query=mysqli_query($conn, $sql) or die("./?action=inventory/get-products: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("./?action=inventory/get-products: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT id, name, type ,minimum_inventory ";
	$sql.=" FROM product";
	$sql.=" WHERE type='INSUMOS'";
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("./?action=inventory/get-products: get PO");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 
   $q=OperationDetailData::getStockByProduct($row["id"]);

    if($q>=$row["minimum_inventory"]){
	$nestedData[] = '<p style="background-color:#C0FFB8" class="success"> '.$row["name"];
	$nestedData[] = '<p style="background-color:#C0FFB8" class="success"> '.$q;
   	$nestedData[] = '<p style="background-color:#C0FFB8" class="success"> '.$row["type"];
   }else{
   	$nestedData[] = '<p style="background-color:#FFBDBD">'.$row["name"];
   	$nestedData[] = '<p style="background-color:#FFBDBD">'.$q;
  	$nestedData[] = '<p style="background-color:#FFBDBD">'.$row["type"];
   }
    //$nestedData[] =$row["name"];
    
    
	
    $nestedData[] =  '<td >
			   <a href="index.php?view=history&product_id='.$row["id"].'" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-pencil"></i>Historial</a>
		     	     </td>';	
    
	$data[] = $nestedData;
    
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
