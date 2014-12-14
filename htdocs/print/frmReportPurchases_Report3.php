<?php
	require_once("fpdf/fpdf.php");
	
	$SupplierCode	=	$_GET['SupplierCode'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	if ($stmt = $mysqli->prepare(" SELECT 	Code, Name
								   FROM 	supplier 
								   WHERE 	Code =  ?")){
		/* Execute the prepared Statement */
		$stmt	->	bind_param('s',$SupplierCode);
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1, $result2);
			
		/* fetch values */
		while ($stmt->fetch()) {
			$SupplierCode		=	$result1;
			$SupplierName	=	$result2;
		}
				
		if ($stmt = $mysqli->prepare("SELECT    p.InvoiceDate, a.asset_name, a.Unit, I.Price
		 							  FROM 		`purchases` p JOIN `supplier` s ON p.SupplierCode = s.Code JOIN `purchaseslineitem` I ON p.InvoiceNo = I.InvoiceNo JOIN `asset_id` a ON I.AssetID = a.asset_id
									  WHERE 	s.Code= ?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$SupplierCode); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['InvoiceDate']	= $result1;
				$lineItemData[$i]['AssetName']	= $result2;
				$lineItemData[$i]['Unit']		= $result3;
				$lineItemData[$i]['Price']		= $result4;
				$i++;
			}
		}	
	}
	
	/* generate pdf file */
	$pdf	=	new FPDF();
	$pdf ->	AddPage();
	$pdf ->	SetFont('Arial','',10);

	/* current date */
	$pdf ->	Cell(0,5,date('d/m/Y'),0,0,'R');
	
	/* line */
	$pdf ->	SetLineWidth(0.7);
	$pdf ->	Line(10,15,200,15);
	
	/* new line */
	$pdf ->	Ln(10);
	
	/* invoice no */
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	Cell(30,5,'Supplier Code: ',0,0,'C');
	$pdf ->	SetFont('Arial','',10);
	$pdf ->	Cell(50,5,$SupplierCode,0,0,'L');
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	Cell(30,5,'Supplier Name: ',0,0,'L');
	$pdf ->	SetFont('Arial','',10);
	$pdf ->	Cell(60,5,$SupplierName,0,0,'L');
		
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	SetLineWidth(0.3);
	
	/* column name */
	$header	=	array('#','Invoice Date','Asset Name','Unit','Price');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(10,45,60,30,35);
		
		$pdf ->	SetFont('Arial','B',10);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],7,$header[$i],'1',0,'C');
		}
			

		$pdf ->	Ln();	
			
		/* display data in lineitem */
		$i 		=	1;
		$pdf ->	SetFont('Arial','',10);			
		foreach($lineItemData as $row)
		{
			$pdf ->	Cell($w[0],6,$i,'1',0,'C');
			$pdf ->	Cell($w[1],6,$row['InvoiceDate'],'1',0,'C');
			$pdf ->	Cell($w[2],6,$row['AssetName'],'1',0,'L');
			$pdf ->	Cell($w[3],6,$row['Unit'],'1',0,'L');
			$pdf ->	Cell($w[4],6,number_format($row['Price'],2),'1',0,'R');
	
			$pdf ->	Ln();
			$i++;
		}
			
		$pdf ->	Cell(array_sum($w),0.3,'','T');
		$pdf ->	Ln();
			
	}
	else{
		/* no data found */
		$pdf ->	Ln(4);
		$pdf ->	SetTextColor(255,0,0);
		$pdf ->	Cell(0,6,'No Data',0,0,'C');	
	}
	
	/* generate PDF output */
	$pdf->Output("Supplier Code - ".$SupplierCode.".pdf","I");  

	
?>