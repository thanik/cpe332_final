<?php
	require_once("fpdf/fpdf.php");
	
	$AssetID	=	$_GET['AssetID'];
	
	$mysqli = new mysqli("localhost", "root", "qwertyu1", "asset");
	$mysqli -> set_charset('utf8');
	if ($stmt = $mysqli->prepare(" SELECT 	asset_id, asset_name, asset_type, unit, yearly_depreciation, purchase_value, beginning_value, depreciated_value, current_value
								   FROM 	asset_id 
								   WHERE 	asset_id =  ?")) {
		/* Execute the prepared Statement */
		$stmt	->	bind_param('s',$AssetID); 
		$stmt 	-> 	execute();

		/* Bind results to variables */
		$stmt-> bind_result($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);
			
		/* fetch values */
		while ($stmt->fetch()) {
			$AssetID			=	$result1;
			$AssetName			=	$result2;
			$AssetType			=	$result3;
			$Unit				=	$result4;
			$YearlyDepreciation	=	$result5;
			$PurchaseValue		=	$result6;
			$BeginningValue		=	$result7;
			$DepreciatedValue	=	$result8;
			$CurrentValue		=	$result9;
		}
				
		if ($stmt = $mysqli->prepare("SELECT 	L.component_name, L.component_type, L.quantity, L.rough_value, L.notes
		 							  FROM 		asset_id A JOIN asset_id_lineitem L ON A.asset_id = L.asset_id
									  WHERE 	A.asset_id = ?")) {
				
			/* Execute the prepared Statement */
			$stmt	->	bind_param('s',$AssetID); 
			$stmt 	-> 	execute();
	
			/* Bind results to variables */
			$stmt-> bind_result($result1, $result2, $result3, $result4, $result5);			
								
			/* fetch values */
			$i = 0;
			while ($stmt->fetch()) {
				$lineItemData[$i]['ComponentName']	= $result1;
				$lineItemData[$i]['ComponentType']	= $result2;
				$lineItemData[$i]['Quantity']		= $result3;
				$lineItemData[$i]['RoughValue']		= $result4;
				$lineItemData[$i]['Notes']			= $result5;
				$i++;
			}
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
	$pdf ->	Cell(30,5,'Asset ID: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$AssetID,0,0,'L');
	
	/* invoice date */
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Asset Name: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$AssetName,0,0,'L');

	$pdf ->	Ln(10);

	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Asset Type: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(50,5,$AssetType,0,0,'L');
	
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Unit: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,$Unit,0,0,'L');

	$pdf ->	Ln(10);

	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Yearly Depreciation: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,number_format($YearlyDepreciation,2),0,0,'R');
		
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Purchase Value: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,number_format($PurchaseValue,2),0,0,'R');

	$pdf ->	Ln(10);

	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Beginning Value: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,number_format($BeginningValue,2),0,0,'R');
		
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Depreciated Value: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,number_format($DepreciatedValue,2),0,0,'R');

	$pdf ->	Ln(10);

	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	Cell(30,5,'Current Value: ',0,0,'L');
	$pdf ->	SetFont('Arial','',8);
	$pdf ->	Cell(60,5,number_format($CurrentValue,2),0,0,'R');

	$pdf ->	Ln();
	$pdf ->	SetFont('Arial','B',8);
	$pdf ->	SetLineWidth(0.3);

	/* column name */
	$header	=	array('#','Component Name','Component Type','Quantity','Rough Value','Notes');
	
	
	/* check data in lineitem */
	if($i>0)
	{
		/* set width of each column */
		$w	=	array(5,30,25,15,30,40);
		
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
			$pdf ->	Cell($w[1],6,$row['ComponentName'],'',0,'C');
			$pdf ->	Cell($w[2],6,$row['ComponentType'],'',0,'C');
			$pdf ->	Cell($w[3],6,$row['Quantity'],'',0,'C');
			$pdf ->	Cell($w[4],6,number_format($row['RoughValue'],2),'',0,'R');
			$pdf ->	Cell($w[5],6,$row['Notes'],'',0,'C');
	
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
	$pdf->Output("Asset ID - ".$AssetID.".pdf","I");  

	
?>