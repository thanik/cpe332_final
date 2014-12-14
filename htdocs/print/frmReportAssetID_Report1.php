<?php
	require_once("fpdf/fpdf.php");
	
	$Month	=	$_GET['Month'];
	$Year	=	$_GET['Year'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');

		if ($stmt = $mysqli->prepare("SELECT 	asset_id, asset_name, asset_type, unit, yearly_depreciation, purchase_value, beginning_value, depreciated_value, current_value, total_component, total_component_value
		 							  FROM 		asset_id  
									  WHERE 	MONTH(purchase_date) = ? AND YEAR(purchase_date) = ?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('ss',$Month,$Year); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['AssetID']				= $result1;
				$lineItemData[$i]['AssetName']				= $result2;
				$lineItemData[$i]['AssetType']				= $result3;
				$lineItemData[$i]['Unit']					= $result4;
				$lineItemData[$i]['YearlyDepreciation']		= $result5;
				$lineItemData[$i]['PurchaseValue']			= $result6;
				$lineItemData[$i]['BeginningValue']			= $result7;
				$lineItemData[$i]['DepreciatedValue']		= $result8;
				$lineItemData[$i]['CurrentValue']			= $result9;
				$lineItemData[$i]['TotalComponent']			= $result10;
				$lineItemData[$i]['TotalComponentValue']	= $result11;
				$i++;
			}
		}
	
	/* generate pdf file */
	$pdf	=	new FPDF('L','mm','A4');
	$pdf ->	AddPage();
	$pdf ->	SetFont('Arial','',8);

	/* current date */
	$pdf ->	Cell(0,5,date('d/m/Y'),0,0,'R');
	
	/* line */
	$pdf ->	SetLineWidth(0.7);
	$pdf ->	Line(10,15,290,15);
	
	/* new line */
	$pdf ->	Ln(10);
	
	/* invoice no */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Month: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$Month,0,0,'L');
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Year: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$Year,0,0,'L');
		
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Asset ID','Asset Name','Asset Type','Unit','Yearly Depreciation','Purchase Value','Beginning Value','Depreciated Value','Current Value','Total Component','Total Component Value');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(5,20,45,30,10,30,20,25,25,25,25,25);
		
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
			$pdf ->	Cell($w[0],6,$i,'',0,'C');
			$pdf ->	Cell($w[1],6,$row['AssetID'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['AssetName'],'',0,'C');
			$pdf ->	Cell($w[3],6,$row['AssetType'],'',0,'C');
			$pdf ->	Cell($w[4],6,$row['Unit'],'',0,'R');
			$pdf ->	Cell($w[5],6,number_format($row['YearlyDepreciation'],2),'',0,'R');
			$pdf ->	Cell($w[6],6,number_format($row['PurchaseValue'],2),'',0,'R');
			$pdf ->	Cell($w[7],6,number_format($row['BeginningValue'],2),'',0,'R');
			$pdf ->	Cell($w[8],6,number_format($row['DepreciatedValue'],2),'',0,'R');
			$pdf ->	Cell($w[9],6,number_format($row['CurrentValue'],2),'',0,'R');
			$pdf ->	Cell($w[10],6,number_format($row['TotalComponent']),'',0,'R');
			$pdf ->	Cell($w[11],6,number_format($row['TotalComponentValue']),'',0,'R');
	
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
	$pdf->Output("Month - ".$Month."Year - ".$Year.".pdf","I");  

	
?>