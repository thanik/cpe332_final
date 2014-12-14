<?php
	require_once("fpdf/fpdf.php");
	
	$Month	=	$_GET['Month'];
	$Year	=	$_GET['Year'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');

	
	
if ($stmt = $mysqli->prepare("SELECT Month(InvoiceDate), Year(InvoiceDate)
FROM 	sales
WHERE 	Month(InvoiceDate) = ? AND Year(InvoiceDate) = ?;")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('ii',$Month,$Year); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2);			
								
			/* fetch values */
			while ($stmt->fetch()) {
			$Month		=	$result1;
			$Year	=	$result2;
			}
		}
	if ($stmt = $mysqli->prepare("SELECT h.CustomerCode,c.Name,SUM(h.AmountDue),COUNT(h.InvoiceNo)
	 FROM `sales` h JOIN `customer`c on h.CustomerCode = c.Code
	 WHERE Month(h.InvoiceDate)=? AND Year(h.InvoiceDate)=? 
	 GROUP BY h.CustomerCode")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('ii',$Month,$Year); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['CustomerCode']	= $result1;
				$lineItemData[$i]['Name']	= $result2;
				$lineItemData[$i]['AmountDue']		= $result3;
				$lineItemData[$i]['InvoiceNo']		= $result4;
				$i++;
			}
		}	
		
		
	/* generate pdf file */
	$pdf	=	new FPDF('L');
	$pdf ->	AddPage();
	$pdf ->	SetFont('Arial','',8);

	/* current date */
	$pdf ->	Cell(0,5,date('y-m-d'),0,0,'R');
	
	/* line */
	$pdf ->	SetLineWidth(0.7);
	$pdf ->	Line(10,15,260,15);
	
	/* new line */
	$pdf ->	Ln(10);
	
	/* invoice no */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Month: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$Month,0,0,'L');
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Year : ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$Year,0,0,'L');
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Customer Code','Customer Name','Amount Due','No. Sale');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(15,40,95,50,50);
		
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],6,$header[$i],'T',0,'C');
		}
			
		$pdf ->	Ln();
		$pdf ->	Cell(array_sum($w),0.3,'','T');
		$pdf ->	Ln();	
			
		/* display data in lineitem */
		$i 		=	1;
		$pdf ->	SetFont('Arial','',8);			
		foreach($lineItemData as $row)
		{
			$pdf ->	Cell($w[0],5,$i,'',0,'C');
			$pdf ->	Cell($w[1],5,$row['CustomerCode'],'',0,'C');
			$pdf ->	Cell($w[2],5,$row['Name'],'',0,'L');
			$pdf ->	Cell($w[3],5,number_format($row['AmountDue'],2),'',0,'R');
			$pdf ->	Cell($w[4],5,$row['InvoiceNo'],'',0,'C');
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
	$pdf->Output("Year - ".$Year.".pdf","I");  

	
?>