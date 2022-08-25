<?php

$connect = new PDO("mysql:host=localhost; dbname=consultation", "root", "2v7!wGxrQAv!");

/*function get_total_row($connect)
{
  $query = "
  SELECT * FROM tbl_webslesson_post
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  return $statement->rowCount();
}

$total_record = get_total_row($connect);*/

$limit = '10';
$page = 1;
if($_POST['page'] > 1)
{
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
}
else
{
  $start = 0;
}

$query = "SELECT *
FROM doctor  
";

if($_POST['query'] != '')
{
  $query .= '
  LEFT JOIN symptom ON doctor.doctor_specialisation = symptom.doctor_speciality 
  WHERE symptoms LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR doctor_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR doctor_specialisation LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR doctor_known_language LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR doctor_city LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR doctor_state LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR doctor_country LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR doctor_fees LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY doctor_country="United States" AND doctor_image !="" DESC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $connect->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $connect->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
$search_term=$_POST['query'];
$user_ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER['REMOTE_ADDR'];
// echo $user_ip;
date_default_timezone_set('Asia/Kolkata');
$date_time = date('d-m-y H:i:s');


  

$ipdat = @json_decode(file_get_contents(
    "http://www.geoplugin.net/json.gp?ip=" . $user_ip));
   
$ip_country= $ipdat->geoplugin_countryName ;
$ip_city= $ipdat->geoplugin_city ;
$ip_timezone= $ipdat->geoplugin_timezone;
$ip_gsm_data=$ip_country.' '.$ip_city.' '.$ip_timezone ;

$sql="INSERT INTO searchbar_data (search_term,ip,date_time,location,record_count)Values('$search_term','$ip','$date_time','$ip_gsm_data','$total_data')";
$statement2 = $connect->prepare($sql);
$statement2->execute();
$output = '
<p>No. of Results - '.$total_data.'</p>

<div">
';
if($total_data > 0)
{
  foreach($result as $row)
  {
    $output .= '
    <div class="products products-table">
  <div class="product">
    <div class="product-img">
      <img loading="lazy" src="'.$row['doctor_image'].'">
    </div>
    <div class="product-content">
        <div class="doc-details">
      <h3><span style="color: #000;"><img style="width:12.58px !important; height:16px !important; position: absolute;" src="https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/img/name1.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['doctor_name'].'</span></h3>
      <p class="product-text genre"><span style="color: #7c8f89;font-size: 15px; !important"><img style="width:12.58px !important; height:16px !important; position: absolute;" src="https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/img/qualification.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['doctor_qualification'].'</span></p>
	  <p class="product-text genre"><span style="color: #7c8f89;font-size: 15px;"><img style="width:12.58px !important; height:16px !important; position: absolute;" src="https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/img/speciality.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['doctor_specialisation'].'</span></p>
      <p class="product-text genre"><span style="color: #7c8f89;font-size: 15px;"><img style="width:12.58px !important; height:16px !important; position: absolute;" src="https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/img/location.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['doctor_city'].','.$row['doctor_state'].','.$row['doctor_country'].'</span></p>
      <p class="product-text genre"><span style="color: #7c8f89;font-size: 15px;"><img style="width:12.58px !important; height:16px !important; position: absolute;" src="https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/img/language.png"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['doctor_known_language'].'</span></p>

      <p class="product-text price"><span style="font-size:18px;color: #1ead36;">&#x24; </span><span style="font-size: 18px;color:#1ead36;">'.$row['doctor_fees'].'</span></p>
      <!-- <p class="product-text genre"></p> -->
        </div>
    <div class="doc-btn">
        <a href="https://drgalen.org/profile/?' . $row['galen_url_name'] . '" class="mi" style="color:white !important;text-decoration:none !important;">More Info</a>
        <a href="https://drgalen.org/book-appointment/?' . $row['galen_url_name'] . '" class="consult-btn" style="color:white !important;text-decoration:none !important;">Consult Now</a>
    </div>	
    </div>
  </div>';
  }
}
else
{
  $output .= '
  <div>
    <p align="center">No Data Found</p>
 </div>
  ';
}

$output .= '
</div>
<br />
<div align="center">
  <ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 4)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'"><span>&#8672;</span></a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#"><span>&#8672;</span></a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id >= $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'"><span>&#8674;</span></a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;

?>
<style>
    input, textarea, button {
    height: 25px;
    margin: 0;
    padding: 10px;
    /* font-family: Raleway, sans-serif; */
	font-family: poppins,sans-serif;
    font-weight: normal;
    font-size: 12pt;
    outline: none;
    border-radius: 0;
    background: none;
    border: 1px solid #282B33;
}
button, select {
    height: 45px;
    padding: 0 15px;
    cursor: pointer;
}
button {
    background: none;
    border: 1px solid black;
    margin: 25px 0;
}
button:hover {
    background-color: #282B33;
    color: white;
}


.tools {
  overflow: auto;
  zoom: 1;
}
.search-area {
  float: left;
  width: 60%;
}
.settings {
  display: none;
  float: right;
  width: 40%;
  text-align: right;
}
#view {
  display: none;
  width: auto;
  height: 47px;
}
#searchbutton {
  width: 60px;
  height: 47px;
}
input#search {
    width: 30%;
    width: calc(100% - 90px);
    padding: 10px;
    border: 1px solid #282B33;
}
@media screen and (max-width:400px) {
  .search-area {
    width: 100%;
  }
}

.products {
  width: 100%;
  /* font-family: Raleway; */
  font-family: poppins,sans-serif;
}
.product {
  display: inline-block;
  width: calc(24% - 13px);
  margin: 10px 10px 30px 10px;
  vertical-align: top;
}
.product img {
  display: block;
  margin: 0 auto;
  width: auto;
  height: 100%;
  background-cover: fit;
}
.product-content {
  text-align: center;
  display:flex !important;
  flex-direction:row;
}
.product h3 {
  font-size: 18px;
  font-weight: 600;
  margin: 10px 0 0 0;
  
}
.product h3 small {
  display: block;
  font-size: 16px;
  font-weight: 400;
  font-style: italic;
  margin: 7px 0 0 0;
}
.product .product-text {
  margin: 7px 0 0 0;
  color: #777;
}
.product .price {
  font-size: 18px;
  font-weight: 600;
  color: #000;
}
.product .genre {
  font-size: 18px;
  color: #000;
  font-weight: 600;
}


@media screen and (max-width:1150px) {
  .product {
    width: calc(33% - 23px);
  }
}
@media screen and (max-width:700px) {
  .product {
    width: calc(50% - 43px);
  }
}
@media screen and (max-width:400px) {
  .product {
    width: 100%;
  }
}

@media screen and (min-width:401px) {
  .settings {
    display: block;
  }
  #view {
    display: inline;
  }
  .products-table .product {
    display: flex;
    flex-wrap:wrap;
    width: auto;
    margin: 10px 10px 30px 10px;
    border: solid 1px gray;
    border-radius: 10px;
    height:200px;
    padding:10px 0px 10px 10px;
  }
  
  @media only screen and (min-device-width: 400px) and (max-width: 600px) {
	  .products-table .product {
		  height: 325px !important;
	  }
  }
  
  .products-table .product .product-img {
    display: inline-block;
    margin: 0;
    width: 120px;
    height: 100%;
    vertical-align: middle;
  }
  .products-table .product img {
    width: auto;
    height: 180px;
  }
  
  @media only screen and (min-width: 400px) {
  .products-table .product img {
    width: 100px;
    height: 180px;
  }
  }
  
  .products-table .product-content {
    text-align: left;
    display: inline-block;
    margin-left: 20px;
    vertical-align: middle;
    width: calc(100% - 145px);
    height:100%;
  }
  .doc-details{
    width:100%;
    height:100%;
  }
  .doc-btn{
    width:20%;
    display:flex;
    flex-direction:column;
    justify-content:space-evenly;
	margin-left: auto;
  }
  .products-table .product h3 {
    margin: 0;
  }
}
.consult-btn{
	font-size: 15px;
	width: 150px;
    flex-wrap:nowrap;
    text-decoration:none;
    border-radius:5px;
    background-color:#f37336;
	text-align: center;
    color:white;
    box-shadow:#e5d9d9 1px 1px 5px;
    align-items:center;
    padding:7px;

}

@media only screen and (max-width: 600px) {
	.consult-btn {
		display: flex !important;
		font-size: 15px;
        width: 120px;
		margin-left: -315px !important;
	}
}

.mi{
	font-size: 15px;
    flex-wrap:nowrap;
    text-decoration:none;
    background-color:white;
    color:#1e95b6;
    border-radius:5px;
	text-align: center;
    box-shadow:#e5d9d9 1px 1px 5px;
    align-items:center;
    padding:9px;
}

@media only screen and (max-width: 600px) {
	.mi {
		display: flex !important;
		font-size: 15px;
        width: 90px;
		margin-left: -300px !important;
	}
}

.form-control {
	    padding: 1rem 1.1rem;
		font-size: 18px;
}

@media only screen and (min-width: 968px) {
.products-table .product img {
	width: 160px !important;
	border-radius: 10px;
}
}

@media only screen and (min-width: 968px) {
.doc-details {
	    margin-left: 50px;
}
}
</style>
<script>
    $("#view").click(function() {
    $(".products").toggleClass("products-table");
  });
</script>
