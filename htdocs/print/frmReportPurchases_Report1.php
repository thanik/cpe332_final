<?php
	require_once("fpdf/fpdf.php");
	
	$TopN	=	$_GET['TopN'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf10');
				
		if ($stmt = $mysqli->prepare("SELECT s.Name, SUM(p.AmountDue) AS TotalPurchases, COUNT(p.InvoiceNo) AS NoPurchases
									FROM purchases p JOIN supplier s ON p.SupplierCode = s.Code
									Group by s.Name
									ORDER By TotalPurchases DESC LIMIT ?")){
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$TopN); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['Name']	= $result1;
				$lineItemData[$i]['TotalPurchases']	= $result2;
				$lineItemData[$i]['NoPurchases']		= $result3;
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
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',10);
	$pdf ->	SetLineWidth(0.3);

	$pdf ->	SetFont('Arial','B',13);
	$pdf ->	Cell(15,5,'Top: ',0,0,'L');
	$pdf ->	SetFont('Arial','',13);
	$pdf ->	Cell(6,5,$TopN,0,0,'L');
	$pdf ->	SetFont('Arial','B',13);
	$pdf ->	Cell(4,5,'Supplier with Highest Purchases: ',0,0,'L');
	$pdf ->	Ln(10);
	
	/* column name */
	$header	=	array('#','Supplier Name','Total Purchases','No Purchases');
	
	
	/* check data in lineitem */
	if(count($lineItemData)>0)
	{
		/* set width of each column */
		$w	=	array(15,65,45,30);
		
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
			$pdf ->	Cell($w[1],6,$row['Name'],'1',0,'L');
			$pdf ->	Cell($w[2],6,number_format($row['TotalPurchases'],2),'1',0,'R');
			$pdf ->	Cell($w[3],6,$row['NoPurchases'],'1',0,'R');
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
	$pdf->Output("Top - ".$TopN.".pdf","I");  

	
?>