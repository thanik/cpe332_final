<?php
	require_once("fpdf/fpdf.php");
	
	$Code	=	$_GET['Code'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	if ($stmt = $mysqli->prepare(" SELECT 	Code,Name
								   FROM 	Customer 
								   WHERE 	Code =  ?")) {
		/* Execute the prepared Statement */
		$stmt	->	bind_param('s',$Code); 
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1,$result2);
			
		/* fetch values */
		while ($stmt->fetch()) {
			$Code		=	$result1;
			$Name		= 	$result2;
		}
				
		if ($stmt = $mysqli->prepare("SELECT h.InvoiceDate, A.asset_id, A.asset_name, A.unit, L.Price
		 							  FROM 		`sales`h JOIN `saleslineitem`L on h.InvoiceNo = L.InvoiceNo 
									  		JOIN `customer` c on h.CustomerCode = c.code 
											JOIN `asset_id`A on L.AssetID = A.asset_id
									  WHERE c.Code=?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$Code); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['InvoiceDate']	= $result1;
				$lineItemData[$i]['asset_id']	= $result2;
				$lineItemData[$i]['asset_name']		= $result3;
				$lineItemData[$i]['unit']		= $result4;
				$lineItemData[$i]['Price']	= $result5;
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
	$pdf ->	Line(10,15,200,15);
	
	/* new line */
	$pdf ->	Ln(10);
	
	/* invoice no */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Customer Code: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$Code,0,0,'L');
	
	/* invoice date*/
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Customer Name: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$Name,0,0,'L');
		
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Sales Date','Asset ID','Asset Name','Unit','Price');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(10,35,45,30,35,35);
		
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
			$pdf ->	Cell($w[1],6,$row['InvoiceDate'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['asset_id'],'',0,'L');
			$pdf ->	Cell($w[3],6,$row['asset_name'],'',0,'C');
			$pdf ->	Cell($w[4],6,$row['unit'],'',0,'C');
			$pdf ->	Cell($w[5],6,number_format($row['Price'],2),'',0,'R');
	
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
	$pdf->Output("Code - ".$Code.".pdf","I");  

	
?>