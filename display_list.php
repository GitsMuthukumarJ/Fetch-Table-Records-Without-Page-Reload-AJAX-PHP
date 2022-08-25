<?php
/* Template Name: Test Doctors View New Link */
?>
<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php include ("test-doctor-header.php"); ?>


	

<style>
.container {
		max-width: 1250px !important;
}
	
@media only screen and(min-width: 1200px) {
.container {
		max-width: 1250px !important;
	}
}
	
.form-group {
    padding: 20px;
    background-color: #e8a4be;
}
	
.card-header {
    background: linear-gradient(-145deg,rgba(219,138,222,1) 0%,rgba(246,191,159,1) 100%)!important;
}
	
.table-responsive {
    padding-left: 20px;
    padding-right: 20px;
}

@media only screen and (min-device-width: 100px) and (max-width: 500px) {
	.table-responsive {
    padding-left: 0px;
    padding-right: 20px;
}
}
	
.doc-btn {
    width: auto !important;
}
	
@media only screen and (min-width: 401px) {
.doc-btn {
	width: auto;
	}
}
	
.table-responsive {
    background-color: #fbf7f7;
}

.mi {
    background-color: #e8a4be !important;
    color: #fff !important;
	padding: 5px !important;
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
	.mi {
	margin: 200px 180px 10px -240px !important;
}
}

@media only screen and (min-device-width: 400px) and (max-width: 500px) {
	.mi {
	margin: 200px 205px -20px -295px !important;
}
}

.form-control {
    height: 100%;
	border-style: none;
    letter-spacing: 1px;
    border-radius: 4px;
}

@media only screen and (max-width: 500px) {
.products-table .product {
	height: 100% !important;
}
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
.consult-btn {
    margin: 0px 160px 0px -240px !important;
}
}

@media only screen and (min-device-width: 400px) and (max-width: 500px) {
.consult-btn {
    margin: 0px 200px -30px -295px !important;
}
}

@media only screen and (min-width: 300px) {
.products-table .product {
    display: flex !important;
    margin: 10px 10px 30px 10px;
    border: solid 1px gray;
    border-radius: 10px;
    padding: 10px 0px 10px 10px;
	height: 100% !important;
}
}

.products {
    /* font-family: Raleway !important; */
	font-family: poppins,sans-serif;
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
.product img {
    max-width: 120px !important;
}
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
.doc-details {
    width: 100%;
    height: 100%;
}
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
.products-table .product-content {
    text-align: left;
    display: inline-block;
    margin-left: 20px;
    vertical-align: middle;
    width: calc(100% - 145px);
    height: 100%;
}
}

@media only screen and (min-device-width: 100px) and (max-width: 400px) {
.products-table .product img {
    width: auto;
    height: 180px;
    max-width: 120px !important;
}
}

@media only screen and (min-device-width: 100px) and (max-width: 500px) {
#search_box {
	font-size: 14px;
}
}
</style>
  </head>
  <body>
    <br />
    <div class="container">
      <h3 align="center">Dr.Galen - Online Doctor Consultation</h3>
      <br />
      <div class="card">
        <h1 class="card-header" style="text-align:justify;font-size:1.2rem;">Online doctor consultation with Top doctors Via Video/Audio/Chat
       126 Specialities. 1800 Best doctors. 100 Countries</h1>
        <div class="card-body" style="padding:0px !important;">
          <div class="form-group">
            <input type="text" name="search_box" id="search_box" class="form-control" placeholder="&#x1F50D; Search by Speciality, Symptoms, City, State, Country,. so on" />
          </div>
          <div class="table-responsive" id="dynamic_content">
            
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<script>
  $(document).ready(function(){

    load_data(1);

    function load_data(page, query = '')
    {
      $.ajax({
        url:"https://drgalen.org/wp-content/themes/pearl-medicalguide/consultation/test_get_doctor_list.php",
        method:"POST",
        data:{page:page, query:query},
        success:function(data)
        {
          $('#dynamic_content').html(data);
        }
      });
    }

    $(document).on('click', '.page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#search_box').val();
      load_data(page, query);
    });

    $('#search_box').keyup(function(){
      var query = $('#search_box').val();
      load_data(1, query);
    });

  });
</script>
<?php include ("test-doctor-footer.php"); ?>
