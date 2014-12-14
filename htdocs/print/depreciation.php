<?php
mysql_connect("localhost","root","qwertyu1") or die(mysql_error());
mysql_select_db("asset");
?>

<script type="text/javascript"> 
	function printReport1() {
  		var txtAssetType = document.getElementById("dropdownAssetType").value;
		/* check current invoice no before print */
 		if (txtAssetType == '' || txtAssetType == "<-- Please Select Asset Type -->") {
       		alert("Please select Asset Type for print");
		}
   		else {
   				window.open("frmReportDepreciation_Report1.php?AssetType=" + txtAssetType);
		}
		return false;
  	}

  	function printReport2() {
  		var txtDepreciationYear = document.getElementById("txtDepreciationYear").value;
		/* check current invoice no before print */
 		if (txtDepreciationYear == '' || isNaN(txtDepreciationYear) || txtDepreciationYear <= 0) {
       		alert("Please enter a valid Depreciation Year for print");
		}
   		else {
   				window.open("frmReportDepreciation_Report2.php?DepreciationYear=" + txtDepreciationYear);
		}
		return false;
  	}

  	function printReport3() {
  		var txtDepreciationValue = document.getElementById("txtDepreciationValue").value;
		/* check current invoice no before print */
 		if (txtDepreciationValue == '' || isNaN(txtDepreciationValue) || txtDepreciationValue <= 0) {
       		alert("Please enter a valid Depreciation Value for print");
		}
   		else {
   				window.open("frmReportDepreciation_Report3.php?DepreciationValue=" + txtDepreciationValue);
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
    				<option value="showReport1">Asset Depreciation by Type</option>
    				<option value="showReport2">Asset Depreciation by Depreciation Year</option>
    				<option valur="showReport3">The Number of Asset Depreciation by Depreciation Value</option>
				</select>
			</div>
		<br><br>
	<div id="report1" >
		<div class="form-group">
			<label>Asset Type</label> 
    		<select id="dropdownAssetType" name="dropdownAssetType" value="<?=$_SESSION['asset_type']?>" class="form-control">
				<option value=""><-- Please Select Asset Type --></option>
				<?php
				$strSQL = "SELECT * FROM asset_type ORDER BY asset_type ASC";
				$objQuery = mysql_query($strSQL);
				while($objResuut = mysql_fetch_array($objQuery))
				{
				?>
				<option value="<?php echo $objResuut["asset_type"];?>"><?php echo $objResuut["asset_type"];?></option>
				<?php
				}
				?>
			</select>
 		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport1();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report2" style="display:none">
		<div class="form-group">
    		<label>Depreciation Year</label> 
    		<input type="number" class="form-control" id="txtDepreciationYear" name="txtDepreciationYear">
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport2();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report3" style="display:none">
		<div class="form-group">
    		<label>Depreciation Value more than</label> 
    		<input type="number" step="0.25" placeholder="0.00" class="form-control" id="txtDepreciationValue" name="txtDepreciationValue">
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport3();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	</form>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>