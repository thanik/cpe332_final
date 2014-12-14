<?php
mysql_connect("localhost","root","qwertyu1") or die(mysql_error());
mysql_select_db("asset");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- include file CSS -->
<link rel="stylesheet" href="jquery-ui-1.9.1.custom/css/redmond/jquery-ui-1.9.1.custom.css" />

<!-- include file jQuery -->
<script src="jquery-ui-1.9.1.custom/js/jquery-1.8.2.js"></script>
<script src="jquery-ui-1.9.1.custom/js/jquery-ui-1.9.1.custom.js"></script>

<script type="text/javascript"> 
	
	function printReport1() {
  		var txtTopN = document.getElementById("txtTopN").value;
		/* check current asset id before print */
		
 		if ((txtTopN == '') || (document.getElementById('txtTopN').value < 1)) {
       		alert("Please select Top N rows for print");
		}
   		else{
   			window.open("frmReportPurchases_Report1.php?TopN=" + txtTopN);
		}		
		return false;
  	}

  	function printReport2(){
  		var txtMonth = document.getElementById("txtMonth").value;
      	var txtYear = document.getElementById("txtYear").value;
		/* check current invoice no before print */
 		if (txtMonth == '' || isNaN(txtMonth) || txtMonth <= 0 || txtMonth >=13) {
       		alert("Please enter valid month for print");
		}
		if (txtYear == '' || isNaN(txtYear) || txtYear <= 0 ) {
			  alert("Please enter valid year for print");
		}
   		else {
   				window.open("frmReportPurchases_Report2.php?Month=" + txtMonth + "&Year=" + txtYear);
		}
		return false;
  	}

  	function printReport3(){
		var txtSupplierCode = document.getElementById("dropdownSupplierCode").value;
		/* check current invoice no before print */
		if (txtSupplierCode == '' || txtSupplierCode == "<-- Please Select SupplierCode -->") {
			  alert("Please select Supplier Code for print");
		}
		 else {
			  window.open("frmReportPurchases_Report3.php?SupplierCode=" + txtSupplierCode);
		}
		return false;
  	}

    function selectReport1()
    {
        if (document.getElementById('dropdownReportList').value == "showReport1")
        	{
            document.getElementById('report1').style.display = 'block';
            document.getElementById('report2').style.display = 'none';
            document.getElementById('report3').style.display = 'none';
        	}
        else if (document.getElementById('dropdownReportList').value == "showReport2")
        	{
        	document.getElementById('report1').style.display = 'none';
            document.getElementById('report2').style.display = 'block';
            document.getElementById('report3').style.display = 'none';
        	}
        else
        	{
        	document.getElementById('report1').style.display = 'none';
            document.getElementById('report2').style.display = 'none';
            document.getElementById('report3').style.display = 'block';
        	}
    }
</script>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">

<title>Query</title>
</head>
<body>

<div class="container" role="main">
	<br><br><br>

    <form id="formAsset" name="formAsset" method="post" action="">
    	<legend>Query Form</legend>
    	<label class="col-md-1 control-label">Report</label>  
         	<div class="col-md-4">
    			<select id="dropdownReportList" name="dropdownReportList" onchange="javascript:selectReport1()" class="form-control">
    				<option value="showReport1">Top N Most Purchases</option>
    				<option value="showReport2">Asset Purchases by Date</option>
    				<option valur="showReport3">Asset Purchases by Supplier</option>
				</select>
			</div>
		<br><br>
	<div id="report1" >
		<div class="form-group">
			<label>Top N Most Purchases</label> 
    		<input type="text" class="form-control" id="txtTopN" name="txtTopN">
 		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport1();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report2" style="display:none">
		<div class="form-group">
    		<label>Month</label> 
    	<input type="text" class="form-control" id="txtMonth" name="txtMonth">
 		</div>
    <div class="form-group">
      <label>Year</label> 
      <input type="text" class="form-control" id="txtYear" name="txtYear">
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport2();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report3" style="display:none">
		<div class="form-group">
    		<label>Asset Purchases by Supplier</label> 
        <select id="dropdownSupplierCode" name="dropdownSupplierCode" value="<?=$_SESSION['SupplierCode']?>" class="form-control">
        <option value=""><-- Please Select Supplier Code --></option>
        <?php
        $strSQL = "SELECT * FROM supplier ORDER BY Code ASC";
        $objQuery = mysql_query($strSQL);
        while($objResuut = mysql_fetch_array($objQuery))
        {
        ?>
        <option value="<?php echo $objResuut["Code"];?>"><?php echo $objResuut["Code"]." - ".$objResuut["Name"];?></option>
        <?php
        }
        ?>
      </select>
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport3();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	</form>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>