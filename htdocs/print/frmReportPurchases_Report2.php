<?php
	require_once("fpdf/fpdf.php");
	
	$Month	=	$_GET['Month'];
	$Year	 = 	$_GET['Year'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf10');
	if ($stmt = $mysqli->prepare("SELECT s.Code,s.Name,SUM(h.AmountDue),COUNT(h.InvoiceNo)
	 FROM purchases h JOIN supplier s ON h.SupplierCode = s.Code
	 WHERE MONTH(h.InvoiceDate) = ? AND YEAR(h.InvoiceDate)=?
	 GROUP BY s.Code, s.Name")) {
		/* Execute the prepared Statement */
		$stmt	->	bind_param('ii',$Month,$Year);
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1, $result2, $result3, $result4);
			
		/* fetch values */
		$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['SupplierCode']	= $result1;
				$lineItemData[$i]['SupplierName']	= $result2;
				$lineItemData[$i]['TotalPurchases']		= $result3;		
				$lineItemData[$i]['NoPurchases']	= $result4;
				$i++;
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
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	Cell(30,5,'Month: ',0,0,'L');
	$pdf ->	SetFont('Arial','',10);
	$pdf ->	Cell(60,5,$Month,0,0,'L');
		
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	Cell(30,5,'Year: ',0,0,'L');
	$pdf ->	SetFont('Arial','',10);
	$pdf ->	Cell(60,5,$Year,0,0,'L');
	
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','SupplierCode','SupplierName','TotalPurchases','NoPurchases');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(13,30,79,33,32);
		
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
			$pdf ->	Cell($w[1],6,$row['SupplierCode'],'1',0,'C');
			$pdf ->	Cell($w[2],6,$row['SupplierName'],'1',0,'L');
			$pdf ->	Cell($w[3],6,number_format($row['TotalPurchases'],2),'1',0,'R');
			$pdf ->	Cell($w[4],6,$row['NoPurchases'],'1',0,'R');
	
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
	$pdf->Output("Asset Purchases by Date.pdf","I");  	
?>