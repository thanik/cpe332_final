<?php
	require_once("fpdf/fpdf.php");
	
	$AssetID	=	$_GET['AssetID'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	if ($stmt = $mysqli->prepare(" SELECT 	H.assetmoveNo, H.movementDate, H.assetmoveReason, L.currentLocation,
										    L.newLocation, A.asset_id, A.asset_name
								   FROM 	movement H
								   Join movementline L on H.assetmoveNo = L.assetmoveNo
								   Join asset_id A on L.asset_id = A.asset_id 
								   WHERE 	A.asset_id =  ?")) {
		/* Execute the prepared Statement */
		$stmt	->	bind_param('s',$AssetID); 
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6, $result7);
			
		/* fetch values */
		while ($stmt->fetch()) {
			$AssetID					=	$result6;
			$AssetName					=	$result7;
		}
				
		if ($stmt = $mysqli->prepare("SELECT 	H.assetmoveNo, H.movementDate, H.assetmoveReason, L.currentLocation,
										   	    L.newLocation
								   FROM 	movement H
								   Join movementline L on H.assetmoveNo = L.assetmoveNo
								   Join asset_id A on L.asset_id = A.asset_id 
								   WHERE 	A.asset_id =  ?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$AssetID); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['assetmoveNo']		= $result1;
				$lineItemData[$i]['movementDate']		= $result2;
				$lineItemData[$i]['assetmoveReason']	= $result3;
				$lineItemData[$i]['currentLocation']	= $result4;
				$lineItemData[$i]['newLocation']		= $result5;
				$i++;
			}
		}	
	}
	
	/* generate pdf file */
	$pdf	=	new FPDF('L');
	$pdf ->	AddPage();
	$pdf ->	SetFont('Arial','',8);

	/* current date */
	$pdf ->	Cell(0,5,date('d/m/Y'),0,0,'R');
	
	/* line */
	$pdf ->	SetLineWidth(0.7);
	$pdf ->	Line(10,15,260,15);
	
	/* new line */
	$pdf ->	Ln(10);
	
	/* invoice no */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Asset ID: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$AssetID,0,0,'L');
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Asset Name: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$AssetName,0,0,'L');
		
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Asset Move No.','Movement Date','Move Reason','Current Location','New Location');
	
	
	/* check data in lineitem */
	if(count($lineItemData)>0)
	{
		/* set width of each column */
		$w	=	array(15,45,45,45,50,50);
		
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],7,$header[$i],'T',0,'C');
		}
			
		$pdf ->	Ln();
		$pdf ->	Cell(array_sum($w),0.3,'','T');
		$pdf ->	Ln();	
			
		/* display data in lineitem */
		$i 		=	1;
		$pdf ->	SetFont('Arial','',8);			
		foreach($lineItemData as $row)
		{
			$pdf ->	Cell($w[0],6,$i,'',0,'C');
			$pdf ->	Cell($w[1],6,$row['assetmoveNo'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['movementDate'],'',0,'C');
			$pdf ->	Cell($w[3],6,$row['assetmoveReason'],'',0,'C');
			$pdf ->	Cell($w[4],6,$row['currentLocation'],'',0,'C');
			$pdf ->	Cell($w[5],6,$row['newLocation'],'',0,'C');
	
			$pdf ->	Ln();
			$i++;
		}
			
		$pdf ->	Cell(array_sum($w),0.3,'','T');
		$pdf ->	Ln();
			
		/* total amount */
		/*$pdf ->	Cell($w[0],6,' ',0,0,'C');
		$pdf ->	Cell($w[1],6,' ',0,0,'C');
		$pdf ->	Cell($w[2],6,' ','',0,'C');
		$pdf ->	Cell($w[3],6,' ','',0,'R');
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Cell($w[4],6,'Total Amount','',0,'R');
		$pdf ->	SetFont('Arial','',8);
		$pdf ->	Cell($w[5],6,number_format($TotalAmount,2),'',0,'R');
		$pdf ->	Ln();*/
			
		/* VAT */
		/*$pdf ->	Cell($w[0],6,' ',0,0,'C');
		$pdf ->	Cell($w[1],6,' ',0,0,'C');
		$pdf ->	Cell($w[2],6,' ','',0,'C');
		$pdf ->	Cell($w[3],6,' ','',0,'R');
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Cell($w[4],6,'VAT','',0,'R');
		$pdf ->	SetFont('Arial','',8);
		$pdf ->	Cell($w[5],6,number_format($VAT,2),'',0,'R');
		$pdf ->	Ln();*/
			
		/* amountdue*/
		/*$pdf ->	Cell($w[0],6,' ',0,0,'C');
		$pdf ->	Cell($w[1],6,' ',0,0,'C');
		$pdf ->	Cell($w[2],6,' ','',0,'C');
		$pdf ->	Cell($w[3],6,' ','',0,'R');
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Cell($w[4],6,'Amount Due','',0,'R');
		$pdf ->	SetFont('Arial','',8);
		$pdf ->	Cell($w[5],6,number_format($AmountDue,2),'',0,'R');
		$pdf ->	Ln();*/
	}
	else{
		/* no data found */
		$pdf ->	Ln(4);
		$pdf ->	SetTextColor(255,0,0);
		$pdf ->	Cell(0,6,'No Data',0,0,'C');	
	}
	
	/* generate PDF output */
	$pdf->Output("AssetID - ".$AssetID.".pdf","I");  

	
?>