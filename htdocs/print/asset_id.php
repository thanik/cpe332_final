<?php
mysql_connect("localhost","root","qwertyu1") or die(mysql_error());
mysql_select_db("asset");
?>

<script type="text/javascript"> 
	function printReport1() {
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
   				window.open("frmReportAssetID_Report1.php?Month=" + txtMonth + "&Year=" + txtYear);
		}
		return false;
  	}

  	function printReport2() {
  		var txtAssetID = document.getElementById("dropdownAssetID").value;
    /* check current invoice no before print */
    if (txtAssetID == '' || txtAssetID == "<-- Please Select Asset ID -->") {
          alert("Please select Asset ID for print");
    }
      else {
          window.open("frmReportAssetID_Report2.php?AssetID=" + txtAssetID);
    }
    return false;
  	}

  	function printReport3() {
  		var txtNumItem = document.getElementById("txtNumItem").value;
		/* check current invoice no before print */
 		if (txtNumItem == '' || isNaN(txtNumItem) || txtNumItem <= 0) {
       		alert("Please enter a valid Number of Items for print");
		}
   		else {
   				window.open("frmReportAssetID_Report3.php?NumItem=" + txtNumItem);
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
    				<option value="showReport1">Asset List by Month</option>
    				<option value="showReport2">Detailed Data for an Asset</option>
    				<option valur="showReport3">Top total components’ quantities and values for assets</option>
				</select>
			</div>
		<br><br>
	<div id="report1" >
		<div class="form-group">
			<label>Month</label> 
    	<input type="number" class="form-control" id="txtMonth" name="txtMonth">
 		</div>
    <div class="form-group">
      <label>Year</label> 
      <input type="number" class="form-control" id="txtYear" name="txtYear">
    </div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport1();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report2" style="display:none">
		<div class="form-group">
    		<label>Asset ID & Name</label> 
        <select id="dropdownAssetID" name="dropdownAssetID" value="<?=$_SESSION['asset_id']?>" class="form-control">
        <option value=""><-- Please Select Asset ID --></option>
        <?php
        $strSQL = "SELECT * FROM asset_id ORDER BY asset_id ASC";
        $objQuery = mysql_query($strSQL);
        while($objResuut = mysql_fetch_array($objQuery))
        {
        ?>
        <option value="<?php echo $objResuut["asset_id"];?>"><?php echo $objResuut["asset_id"]." - ".$objResuut["asset_name"];?></option>
        <?php
        }
        ?>
      </select>
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport2();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	<div id="report3" style="display:none">
		<div class="form-group">
    		<label>Number of Items</label> 
    		<input type="number" class="form-control" id="txtNumItem" name="txtNumItem">
  		</div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport3();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	</form>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>