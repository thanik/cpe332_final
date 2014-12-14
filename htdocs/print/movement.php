<?php
mysql_connect("localhost","root","qwertyu1") or die(mysql_error());
mysql_select_db("asset");
?>
<script type="text/javascript"> 
	function printReport1() {
  		var txtMoveReason = document.getElementById("dropdownReason").value;
		/* check current invoice no before print */
 		if (txtMoveReason == '' || txtMoveReason == "<-- Please Select Move Reason -->") {
       		alert("Please select move reason for print");
		}
   		else {
   				window.open("frmReportMovement_Report1.php?MoveReason=" + txtMoveReason);
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
          window.open("frmReportMovement_Report2.php?AssetID=" + txtAssetID);
    }
    return false;
  	}

  	function printReport3() {
  	  var txtFromDate = document.getElementById("txtFromDate").value;
      var txtToDate = document.getElementById("txtToDate").value;
		/* check current invoice no before print */
 		if (txtFromDate == '') {
       		alert("Please enter valid from date for print");
		}
    if (txtToDate == '') {
          alert("Please enter valid to date for print");
    }
   		else {
   				window.open("frmReportMovement_Report3.php?FromDate=" + txtFromDate + "&ToDate=" + txtToDate);
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
    				<option value="showReport1">All movement with the same reason</option>
    				<option value="showReport2">Movement History of the Asset</option>
    				<option valur="showReport3">Number of assets move by date</option>
				</select>
			</div>
		<br><br>
	<div id="report1" >
		<div class="form-group">
			<label>Move Reason</label> 
    		<select id="dropdownReason" name="dropdownReason" value="<?=$_SESSION['asset_type']?>" class="form-control">
				<option value=""><-- Please Select Move Reason --></option>
				<option value="purchase">Purchase</option>
                <option value="transfer">Transfer</option>
                <option value="sales">Sales</option>
			</select>
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
			<label>From Date</label> 
    	<input type="date" class="form-control" id="txtFromDate" name="txtFromDate">
 		</div>
    <div class="form-group">
      <label>To Date</label> 
      <input type="date" class="form-control" id="txtToDate" name="txtToDate">
    </div>
  		<button type="button" class="btn btn-info navbar-btn" onclick="javascript:return printReport3();">
         <span class="glyphicon glyphicon-print"></span> Print</button>
	</div>

	</form>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>