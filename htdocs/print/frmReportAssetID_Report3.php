<?php
	require_once("fpdf/fpdf.php");
	
	$NumItem	=	$_GET['NumItem'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	
		if ($stmt = $mysqli->prepare("SELECT 	A.asset_id, A.asset_name, SUM(L.quantity), SUM(L.rough_value*L.quantity)
		 							  FROM 		asset_id A JOIN asset_id_lineitem L ON A.asset_id = L.asset_id 
		 							  GROUP BY  L.asset_id
									  ORDER BY	SUM(L.rough_value*L.quantity) DESC
									  LIMIT 	?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$NumItem); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['AssetID']				= $result1;
				$lineItemData[$i]['AssetName']				= $result2;
				$lineItemData[$i]['TotalComponentQuantity']	= $result3;
				$lineItemData[$i]['TotalRoughValue']		= $result4;
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

	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Top ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(35,5,$NumItem,0,0,'L');
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(40,5,' total components quantities and values for assets',0,0,'L');
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Asset ID','Asset Name','Total Component Quantity','Total Rough Value');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(5,20,95,40,30);
		
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
			$pdf ->	Cell($w[1],6,$row['AssetID'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['AssetName'],'',0,'C');
			$pdf ->	Cell($w[3],6,$row['TotalComponentQuantity'],'',0,'C');
			$pdf ->	Cell($w[4],6,number_format($row['TotalRoughValue'],2),'',0,'R');
	
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
	$pdf->Output("Top ".$NumItem." total components’ quantities and values for assets.pdf","I");  

	
?>