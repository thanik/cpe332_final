<?php
	require_once("fpdf/fpdf.php");
	
	$DepreciationValue	=	$_GET['DepreciationValue'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');

		if ($stmt = $mysqli->prepare("SELECT 	depreciation_no, COUNT(depreciation_no)
		 							  FROM 		depreciation_line
									  WHERE 	depreciation_value > ?
									  GROUP BY	depreciation_no")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$DepreciationValue); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['DepreciationNo']		= $result1;
				$lineItemData[$i]['NumberDepreciation']		= $result2;
				$i++;
			}
		}	
	
	/* generate pdf file */
	$pdf	=	new FPDF();
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
	$pdf ->	Cell(30,5,'Depreciation Value > ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$DepreciationValue,0,0,'L');
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Depreciation No','The Number of Asset Depreciation by Depreciation Value');
	
	
	/* check data in lineitem */
	if(count($lineItemData)>0)
	{
		/* set width of each column */
		$w	=	array(5,30,100);
		
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],2,$header[$i],'T',0,'C');
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
			$pdf ->	Cell($w[1],6,$row['DepreciationNo'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['NumberDepreciation'],'',0,'C');
	
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
	$pdf->Output("Depreciation Value > ".$DepreciationValue.".pdf","I");  

	
?>