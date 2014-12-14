<?php
	require_once("fpdf/fpdf.php");
	
	$AssetType	=	$_GET['AssetType'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
				
		if ($stmt = $mysqli->prepare("SELECT 	A.asset_id, A.asset_name, A.yearly_depreciation, A.purchase_value, D.for_month, D.for_year, D.depreciation_date, L.depreciation_value_month, L.new_depreciation_value_month
		 							  FROM 		asset_id A JOIN depreciation_line L ON A.asset_id = L.asset_id JOIN depreciation D ON D.depreciation_no = L.depreciation_no
									  WHERE 	A.asset_type = ?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$AssetType); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['AssetID']						= $result1;
				$lineItemData[$i]['AssetName']						= $result2;
				$lineItemData[$i]['YearlyDepreciation']				= $result3;
				$lineItemData[$i]['PurchaseValue']					= $result4;
				$lineItemData[$i]['ForMonth']						= $result5;
				$lineItemData[$i]['ForYear']						= $result6;
				$lineItemData[$i]['DepreciationDate']				= $result7;
				$lineItemData[$i]['DepreciationValueMonth']			= $result8;
				$lineItemData[$i]['NewDepreciationValueMonth']		= $result9;
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
	$pdf ->	Cell(30,5,'AssetType: ',0,0,'C');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$AssetType,0,0,'L');
		
	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.5);

	/* column name */
	$header	=	array('#','Asset ID','Asset Name','Yearly Depreciation','Purchase Value','For Month','For Year','Depreciation Date','Depreciation This Month','New Value After This Month');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(5,20,30,30,20,20,20,30,45,45);
		
		$pdf ->	SetFont('Arial','B',8);
		$pdf ->	Ln(10);
		
		/* display column name */
		for($i=0; $i<count($header); $i++)
		{
			$pdf ->	Cell($w[$i],9,$header[$i],'T',0,'C');
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
			$pdf ->	Cell($w[3],6,$row['YearlyDepreciation'],'',0,'R');
			$pdf ->	Cell($w[4],6,number_format($row['PurchaseValue'],2),'',0,'R');
			$pdf ->	Cell($w[5],6,$row['ForMonth'],'',0,'C');
			$pdf ->	Cell($w[6],6,$row['ForYear'],'',0,'C');
			$pdf ->	Cell($w[7],6,$row['DepreciationDate'],'',0,'C');
			$pdf ->	Cell($w[8],6,number_format($row['DepreciationValueMonth'],2),'',0,'R');
			$pdf ->	Cell($w[9],6,number_format($row['NewDepreciationValueMonth'],2),'',0,'R');
	
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
	$pdf->Output("Asset Type - ".$AssetType.".pdf","I");  

	
?>