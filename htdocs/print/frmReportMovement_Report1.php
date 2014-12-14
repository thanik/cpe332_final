<?php
	require_once("fpdf/fpdf.php");
	
	$MoveReason	=	$_GET['MoveReason'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	if ($stmt = $mysqli->prepare(" SELECT 	H.assetmoveNo, H.movementDate, L.asset_id,
										    A.asset_name, L.currentLocation, L.newLocation, H.assetmoveReason
								   FROM 	assetmoveheader H
								   Join assetmoveline L on H.assetmoveNo = L.assetmoveNo
								   Join asset_id A on L.asset_id = A.asset_id 
								   WHERE 	H.assetmoveReason =  ?")) {
		/* Execute the prepared Statement */
		$stmt	->	bind_param('s',$MoveReason); 
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6, $result7);
			
		/* fetch values */
		while ($stmt->fetch()) {
			$MoveReason					=	$result7;
		}
				
		if ($stmt = $mysqli->prepare("SELECT 	H.assetmoveNo, H.movementDate, L.asset_id,
										    	A.asset_name, L.currentLocation, L.newLocation
								   FROM 	assetmoveheader H
								   Join assetmoveline L on H.assetmoveNo = L.assetmoveNo
								   Join asset_id A on L.asset_id = A.asset_id 
								   WHERE 	H.assetmoveReason =  ?
								   ORDER by H.assetmoveNo, L.moveList")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$MoveReason); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['assetmoveNo']		= $result1;
				$lineItemData[$i]['movementDate']		= $result2;
				$lineItemData[$i]['asset_id']			= $result3;
				$lineItemData[$i]['asset_name']			= $result4;
				$lineItemData[$i]['currentLocation']	= $result5;
				$lineItemData[$i]['newLocation']		= $result6;
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
	$pdf ->	Cell(30,5,'Move Reason: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$MoveReason,0,0,'L');
	
	/* invoice date */
	/*$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Invoice Date: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$InvoiceDate,0,0,'L');*/
		
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Asset Move No.','Movement Date','Asset ID','Asset Name','Current Location','New Location');
	
	
	/* check data in lineitem */
	if(count($lineItemData)>0)
	{
		/* set width of each column */
		$w	=	array(10,35,45,35,55,45,45);
		
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],8,$header[$i],'T',0,'C');
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
			$pdf ->	Cell($w[3],6,$row['asset_id'],'',0,'C');
			$pdf ->	Cell($w[4],6,$row['asset_name'],'',0,'L');
			$pdf ->	Cell($w[5],6,$row['currentLocation'],'',0,'C');
			$pdf ->	Cell($w[6],6,$row['newLocation'],'',0,'C');
	
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
	$pdf->Output("Reason - ".$MoveReason.".pdf","I");  

	
?>