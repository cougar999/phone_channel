<?php
ini_set('memory_limit','256M');
set_time_limit(0);
error_reporting(0);
session_start();
require_once("../config.php");

$gets 			      = json_decode(stripcslashes($_REQUEST['rqstr']),true);
$gets['top_agent_id'] = empty($gets['top_agent_id'])?$_SESSION['agentid']:$gets['top_agent_id'];
$gets['page'] 	  = 1;
$gets['pagesize'] = 1000000;
$gets['isSup'] 	  = $_SESSION['isSup'];
$gets['sortorder']= empty($gets['sortname'])&&$gets['sortorder']=='asc'?'desc':$gets['sortorder'];
$statLoginModel   = new statLoginModel();

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()
			->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
$styleArray1 = $css_common = array(
	'font' => array(
		'bold' => true,
		'color'=>array(
			'argb' => '00000000'
		),
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	),
);
if($gets['type'] == 'sketch'){
	$gets['date']      = empty($gets['date'])?date('Y-m-d',strtotime("-1 day")):$gets['date'];
	$gets['end_date']  = empty($gets['end_date'])?date('Y-m-d',strtotime("-1 day")):$gets['end_date'];
			
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1', '子公司');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
    $_letter    = 'B';
	for($i=strtotime($gets['date']);$i<=strtotime($gets['end_date']);$i+=86400){
    	$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(20);
		//$objPHPExcel->getActiveSheet()->setCellValue($_letter.'1', intval(date('m',$i)).'月'.intval(date('d',$i)).'号');
        $objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell($_letter.'1') );
        $objRichText->createText(intval(date('m',$i)).'月'.intval(date('d',$i)).'号登陆');
        $objPayable = $objRichText->createTextRun('(未登陆)');
        $objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
        
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}

    $data = $statLoginModel->getStatLoginInfo($gets);
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['channel_name']);
    	foreach ($_tmp_let as $kk=>$vv){
    		$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell($kk.($k+2)) );
	        $objRichText->createText(intval($v[$vv.'_y']));
	        $objPayable = $objRichText->createTextRun("(".intval($v[$vv.'_n']).")");
	        $objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
			//$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), "{$v[$vv.'_y']}({$v[$vv.'_n']})");
    	}
    }
}elseif ($gets['type'] == 'sketch_ad'){
	$gets['date']      = empty($gets['date'])?date('Y-m-d',strtotime("-1 day")):$gets['date'];
	$gets['end_date']  = empty($gets['end_date'])?date('Y-m-d',strtotime("-1 day")):$gets['end_date'];
			
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1', '渠道名称');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
    $_letter    = 'B';
	for($i=strtotime($gets['date']);$i<=strtotime($gets['end_date']);$i+=86400){
    	$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(20);
		//$objPHPExcel->getActiveSheet()->setCellValue($_letter.'1', intval(date('m',$i)).'月'.intval(date('d',$i)).'号');
        $objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell($_letter.'1') );
        $objRichText->createText(intval(date('m',$i)).'月'.intval(date('d',$i)).'号登陆');
        $objPayable = $objRichText->createTextRun('(未登陆)');
        $objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
        
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}
            
    $data = $statLoginModel->getStatLoginAllData($gets);
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['name']);
    	foreach ($_tmp_let as $kk=>$vv){
    		$objRichText = new PHPExcel_RichText( $objPHPExcel->getActiveSheet()->getCell($kk.($k+2)) );
	        $objRichText->createText(intval($v[$vv.'_y']));
	        $objPayable = $objRichText->createTextRun("(".intval($v[$vv.'_n']).")");
	        $objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_RED ) );
			//$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), "{$v[$vv.'_y']}({$v[$vv.'_n']})");
    	}
    }
}elseif($gets['type'] == 'detail'){
	$gets['date']      = empty($gets['date'])?date('Y-m-d',strtotime("-1 day")):$gets['date'];
	$gets['end_date']  = empty($gets['end_date'])?date('Y-m-d',strtotime("-1 day")):$gets['end_date'];
	
	$objPHPExcel->setActiveSheetIndex(0)
            	->setCellValue('A1', '店面');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            
	$_letter    = 'B';
	for($i=strtotime($gets['date']);$i<=strtotime($gets['end_date']);$i+=86400){
    	$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($_letter.'1', intval(date('m',$i)).'月'.intval(date('d',$i)).'号是否登陆');
        
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}
    
	$data = $statLoginModel->getStatShopLoginInfo($gets);
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['shop_name']);
    	foreach ($_tmp_let as $kk=>$vv){
    		$v[$vv.'_y'] = intval($v[$vv.'_y']);
    		$v[$vv.'_y'] == 0 && $objPHPExcel->getActiveSheet()->getStyle($kk.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    		$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), $v[$vv.'_y'] == 0 ? "否" : "是");
    	}
    }        
}elseif ($gets['type'] == 'detail_dt_ad'){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '渠道名称');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            
    $gets['s_day']     = empty($gets['s_day'])?date('Y-m-01',strtotime("-1 day")):$gets['s_day'];
	$gets['e_day']     = empty($gets['e_day'])?date('Y-m-t',strtotime("-1 day")):$gets['e_day'];
	$_letter           = 'B';
	for($i=strtotime($gets['s_day']);$i<=strtotime($gets['e_day']);$i+=86400){
    	$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(10);
		$objPHPExcel->getActiveSheet()
            		->setCellValue($_letter.'1', intval(date('d',$i)).'号');
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}
	
	$data = $statLoginModel->getStatDetailLoginAllData($gets);
	
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['name']);
    	foreach ($_tmp_let as $kk=>$vv){
			$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), strip_tags($v[$vv]));
    	}
    }
}elseif ($gets['type'] == 'detail_dt'){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '分公司');
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            
    $gets['s_day']     = empty($gets['s_day'])?date('Y-m-01',strtotime("-1 day")):$gets['s_day'];
	$gets['e_day']     = empty($gets['e_day'])?date('Y-m-t',strtotime("-1 day")):$gets['e_day'];
	$_letter           = 'B';
	for($i=strtotime($gets['s_day']);$i<=strtotime($gets['e_day']);$i+=86400){
    	$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(10);
		$objPHPExcel->getActiveSheet()
            		->setCellValue($_letter.'1', intval(date('d',$i)).'号');
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}
	
	$data = $statLoginModel->getStatDetailLoginInfo($gets);
	
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['channel_name']);
    	foreach ($_tmp_let as $kk=>$vv){
			$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), strip_tags($v[$vv]));
    	}
    }
}elseif ($gets['type'] == 'detail_dtinfo'){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '店面');
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            
    $gets['s_day']     = empty($gets['s_day'])?date('Y-m-01',strtotime("-1 day")):$gets['s_day'];
	$gets['e_day']     = empty($gets['e_day'])?date('Y-m-t',strtotime("-1 day")):$gets['e_day'];
	$_letter           = 'B';
	for($i=strtotime($gets['s_day']);$i<=strtotime($gets['e_day']);$i+=86400){
		$objPHPExcel->getActiveSheet()->getColumnDimension($_letter)->setWidth(8);
		$objPHPExcel->getActiveSheet()
            		->setCellValue($_letter.'1', intval(date('d',$i)).'号');
        $_tmp_let[$_letter] = "d_".date('m',$i).date("d",$i);
        ++$_letter;
	}
	
	$data = $statLoginModel->getStatDetailShopLoginInfo($gets);
	
    foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['shop_name']);
    	foreach ($_tmp_let as $kk=>$vv){
    		if(strip_tags($v[$vv]) == '否'){
				$objPHPExcel->getActiveSheet()->getStyle($kk.($k+2))
							->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    		}
			$objPHPExcel->getActiveSheet()->setCellValue($kk.($k+2), strip_tags($v[$vv]));
    	}
    }
}elseif ($gets['type'] == 'use_sketch_ad'){
 	 $objPHPExcel->getActiveSheet()->getStyle('A')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('B')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('C')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('E')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('F')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('G')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('H')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('I')->applyFromArray($styleArray1);
	 $gets['date'] = empty($gets['date'])?date('Y-m-01',strtotime("-1 day")):$gets['date'];
	 $objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '渠道名称')
            	  -> setCellValue('B1', '登陆数')
            	  -> setCellValue('C1', '手机连接(台)')
            	  -> setCellValue('D1', '苹果手机连接(台)')
            	  -> setCellValue('E1', '安卓手机连接(台)')
            	  -> setCellValue('F1', '苹果软件下载(个)')
            	  -> setCellValue('G1', '安卓软件下载(个)')
            	  -> setCellValue('H1', '苹果卡激活(张)')
            	  -> setCellValue('I1', '下载卡激活(张)');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('I')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSketchAllData($gets);
	foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['name']);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['login_count']);
    	$v['phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('C'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), !$v['phone_connect']?'无':$v['phone_connect']);
    	$v['iso_phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('D'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), !$v['iso_phone_connect']?'无':$v['iso_phone_connect']);
    	$v['apk_phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('E'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), !$v['apk_phone_connect']?'无':$v['apk_phone_connect']);
    	$v['iso_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('F'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), !$v['iso_soft_dl']?'无':$v['iso_soft_dl']);
    	$v['apk_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('G'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), !$v['apk_soft_dl']?'无':$v['apk_soft_dl']);
    	$v['iso_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('H'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), !$v['iso_card_act']?'无':$v['iso_card_act']);
    	$v['dl_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('I'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), !$v['dl_card_act']?'无':$v['dl_card_act']);
	}
}elseif ($gets['type'] == 'use_sketch'){
	 $objPHPExcel->getActiveSheet()->getStyle('A')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('B')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('C')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('E')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('F')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('G')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('H')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('I')->applyFromArray($styleArray1);
	 $gets['date'] = empty($gets['date'])?date('Y-m-01',strtotime("-1 day")):$gets['date'];
	 $objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '公司名')
            	  -> setCellValue('B1', '登陆数')
            	  -> setCellValue('C1', '手机连接(台)')
            	  -> setCellValue('D1', '苹果手机连接(台)')
            	  -> setCellValue('E1', '安卓手机连接(台)')
            	  -> setCellValue('F1', '苹果软件下载(个)')
            	  -> setCellValue('G1', '安卓软件下载(个)')
            	  -> setCellValue('H1', '苹果卡激活(张)')
            	  -> setCellValue('I1', '下载卡激活(张)');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('G')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('H')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('I')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSketchInfo($gets);
	foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['channel_name']);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['login_count']);
    	$v['phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('C'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), !$v['phone_connect']?'无':$v['phone_connect']);
    	$v['iso_phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('D'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), !$v['iso_phone_connect']?'无':$v['iso_phone_connect']);
    	$v['apk_phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('E'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), !$v['apk_phone_connect']?'无':$v['apk_phone_connect']);
    	$v['iso_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('F'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), !$v['iso_soft_dl']?'无':$v['iso_soft_dl']);
    	$v['apk_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('G'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), !$v['apk_soft_dl']?'无':$v['apk_soft_dl']);
    	$v['iso_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('H'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('H'.($k+2), !$v['iso_card_act']?'无':$v['iso_card_act']);
    	$v['dl_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('I'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('I'.($k+2), !$v['dl_card_act']?'无':$v['dl_card_act']);
	}
}elseif ($gets['type'] == 'use_sketch_channel_ad'){
	 $objPHPExcel->getActiveSheet()->getStyle('A')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('B')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('C')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('E')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('F')->applyFromArray($styleArray1);
	 $objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '渠道名称')
            	  -> setCellValue('B1', '手机连接(台)')
            	  -> setCellValue('C1', '苹果软件下载(个)')
            	  -> setCellValue('D1', '安卓软件下载(个)')
            	  -> setCellValue('E1', '苹果卡激活(张)')
            	  -> setCellValue('F1', '下载卡激活(张)');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSketChnAllData($gets);
	foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['name']);
    	$v['phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('B'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), !$v['phone_connect']?'无':$v['phone_connect']);
    	$v['iso_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('C'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), !$v['iso_soft_dl']?'无':$v['iso_soft_dl']);
    	$v['apk_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('D'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), !$v['apk_soft_dl']?'无':$v['apk_soft_dl']);
    	$v['iso_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('E'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), !$v['iso_card_act']?'无':$v['iso_card_act']);
    	$v['dl_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('F'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), !$v['dl_card_act']?'无':$v['dl_card_act']);
	}
}elseif ($gets['type'] == 'use_sketch_channel'){
	 $objPHPExcel->getActiveSheet()->getStyle('A')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('B')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('C')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('E')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('F')->applyFromArray($styleArray1);
	 $objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '公司名')
            	  -> setCellValue('B1', '手机连接(台)')
            	  -> setCellValue('C1', '苹果软件下载(个)')
            	  -> setCellValue('D1', '安卓软件下载(个)')
            	  -> setCellValue('E1', '苹果卡激活(张)')
            	  -> setCellValue('F1', '下载卡激活(张)');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSketChnInfo($gets);
	foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['channel_name']);
    	$v['phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('B'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), !$v['phone_connect']?'无':$v['phone_connect']);
    	$v['iso_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('C'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), !$v['iso_soft_dl']?'无':$v['iso_soft_dl']);
    	$v['apk_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('D'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), !$v['apk_soft_dl']?'无':$v['apk_soft_dl']);
    	$v['iso_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('E'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), !$v['iso_card_act']?'无':$v['iso_card_act']);
    	$v['dl_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('F'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), !$v['dl_card_act']?'无':$v['dl_card_act']);
	}
}elseif ($gets['type'] == 'use_sketch_shop'){
	 $objPHPExcel->getActiveSheet()->getStyle('A')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('B')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('C')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('D')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('E')->applyFromArray($styleArray1);
	 $objPHPExcel->getActiveSheet()->getStyle('F')->applyFromArray($styleArray1);
	 $objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '店名')
            	  -> setCellValue('B1', '手机连接(台)')
            	  -> setCellValue('C1', '苹果软件下载(个)')
            	  -> setCellValue('D1', '安卓软件下载(个)')
            	  -> setCellValue('E1', '苹果卡激活(张)')
            	  -> setCellValue('F1', '下载卡激活(张)');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSketShopInfo($gets);
	foreach ($data['Rows'] as $k=>$v){
    	$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['shop_name']);
    	$v['phone_connect'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('B'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), !$v['phone_connect']?'无':$v['phone_connect']);
    	$v['iso_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('C'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), !$v['iso_soft_dl']?'无':$v['iso_soft_dl']);
    	$v['apk_soft_dl'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('D'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), !$v['apk_soft_dl']?'无':$v['apk_soft_dl']);
    	$v['iso_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('E'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), !$v['iso_card_act']?'无':$v['iso_card_act']);
    	$v['dl_card_act'] == 0 && $objPHPExcel->getActiveSheet()->getStyle('F'.($k+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    	$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), !$v['dl_card_act']?'无':$v['dl_card_act']);
	}
}elseif ($gets['type'] == 'phone_type_sketch'){
	$objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '机型')
            	  -> setCellValue('B1', '平台')
            	  -> setCellValue('C1', '连接数');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(30);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	
	$data = $statLoginModel->getStatPhoneTypeSketchInfo($gets);
	
	foreach ($data['Rows'] as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['phone_type_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['phone_os']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $v['connect_count']);
	}
}elseif ($gets['type'] == 'phone_imei_sketch'){
	$objPHPExcel -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '渠道名称')
            	  -> setCellValue('B1', '店面名称')
            	  -> setCellValue('C1', '机型')
            	  -> setCellValue('D1', '连接台数')
            	  -> setCellValue('E1', '软件下载数');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	
	$data = $statLoginModel->getStatPhoneImeiSketchInfo($gets);
	
	foreach ($data['Rows'] as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['channel_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['shop_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $v['phone_type_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $v['connect_count']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $v['download_count']);
	}
}elseif ($gets['type'] == 'use_soft_detail'){
	$objPHPExcel  -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '软件名称')
            	  -> setCellValue('B1', '下载数量');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	
	$data = $statLoginModel->getStatUseSoftInfo($gets);
	
	foreach ($data['Rows'] as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['soft_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['download_times']);
	}
}elseif ($gets['type'] == 'get_gold_info'){
	$data         = array();
	$objPHPExcel  -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '名称')
            	  -> setCellValue('B1', '总收入')
            	  -> setCellValue('C1', '连续登陆')
            	  -> setCellValue('D1', '手机连接')
            	  -> setCellValue('E1', '软件下载')
            	  -> setCellValue('F1', '手机复制')
            	  ;
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
	$data = $statLoginModel->getStatGoldAllData($gets);
	
	foreach ($data['Rows'] as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), " ".$v['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), " ".number_format($v['gold_total'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), " ".number_format($v['gold_login'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), " ".number_format($v['gold_connect'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), " ".number_format($v['gold_install'], 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), " ".number_format($v['gold_copy'], 2, '.', ','));
	}
}elseif ($gets['type'] == 'get_gold_detail'){
	$data         = array();
	$max_diff     = 20;
	$objPHPExcel  -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '分公司名称')
            	  -> setCellValue('B1', '店面名称')
            	  -> setCellValue('C1', '店员')
            	  -> setCellValue('D1', '任务描述')
            	  -> setCellValue('E1', '任务完成')
            	  -> setCellValue('F1', '时间')
            	  -> setCellValue('G1', '备注')
            	  ;
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('F')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('G')->setWidth(20);
	
	$gets['top_agent_id'] = $gets['top_agent_id_'];
	if ($max_diff*60*60*24 < ($_ed=strtotime($gets['end_date']))-($_sd=strtotime($gets['date']))){
		$gets['date'] = date('Y-m-d',$_ed-$max_diff*60*60*24);
	}
	$data = $statLoginModel->getStatGoldDetailData($gets);
	
	foreach ($data['Rows'] as $k=>$v){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($k+2), $v['top_agent_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($k+2), $v['channel_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($k+2), $v['seller_real_name']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($k+2), $v['mission_desc']);
		//任务完成
		$_mission_completion = $v['mission_status'];
		$_mission_completion = 0==$_mission_completion?'无效操作':(1==$_mission_completion?'已领取'.floatval($v['goldcoin']/10).'个金币':(2==$_mission_completion?'有效未领取':'有效未完成'));
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($k+2), $_mission_completion);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.($k+2), $v['operation_time']);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($k+2), $v['remarks']);
	}
}elseif ($gets['type'] == 'member'){
	$memberModel 	   = new ChAgent();
	$objPHPExcel  -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '用户ID')
            	  -> setCellValue('B1', '名称')
            	  -> setCellValue('C1', '用户名')
            	  -> setCellValue('D1', '密码')
            	  -> setCellValue('E1', '类型');
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$index = 2;
	
	foreach ($gets['data'] as $k=>$item){
		$tmp_data = $memberModel->getChnInfo(array('id'=>$item['id'],'type'=>$item['type']));
		if (empty($tmp_data)) continue;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($index), $tmp_data[0]['id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($index), $tmp_data[0]['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($index), $tmp_data[0]['username']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($index), " ".$tmp_data[0]['password']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($index), $item['type']==0?"{$tmp_data[0]['level']}级渠道":"店员");
		//设置背景色
		if ($tmp_data[0]['level'] == 1 || $tmp_data[0]['level'] == 2){
			$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle('E'.($index));
			$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b050');
			$objPHPExcel->getActiveSheet()->duplicateStyle($objFill_blue, "A{$index}:E{$index}");
		}
		$index++;
	}
}elseif ($gets['type'] == 'mem_type'){
	$memberModel 	   = new ChAgent();
	$tip_cf = array(
		'1' => '渠道',
		'2' => '分公司',
		'3' => '店面'
	);
	if (empty($gets['exp_id'])){
		!$gets['isSup'] && $gets['exp_id'] = $_SESSION['real_id'];
	}
	$objPHPExcel  -> setActiveSheetIndex(0)
            	  -> setCellValue('A1', '用户ID')
            	  -> setCellValue('B1', '名称')
            	  -> setCellValue('C1', '用户名')
            	  -> setCellValue('D1', '密码')
            	  -> setCellValue('E1', '类型')
            	  -> setCellValue('F1', '等级')
            	  ;
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('A')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('B')->setWidth(50);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel  -> getActiveSheet()->getColumnDimension('D')->setWidth(20);
	$index = 2;
	
	$tmp_data     = $memberModel->getMemInfo(array('exp_id'=>$gets['exp_id']));
	
	foreach ($tmp_data as $k=>$item){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($index), $item['rl_id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($index), " ".$item['name']);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($index), " ".$item['username']);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.($index), " ".$item['password']);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($index), $item['level']==4?$item['_level']==3?'店员':'负责人':$tip_cf[$item['level']]);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.($index), " ".$item['level']);
		//设置背景色
		if ($item['level'] == 1 || $item['level'] == 2 || ($item['level']==4&&$item['_level']!=3)){
			$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle('E'.($index));
			$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b050');
			$objPHPExcel->getActiveSheet()->duplicateStyle($objFill_blue, "A{$index}:E{$index}");
		}
		if ($item['level'] == 3){
			$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle('E'.($index));
			$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
			$objPHPExcel->getActiveSheet()->duplicateStyle($objFill_blue, "A{$index}:E{$index}");
		}
		if (!$item['status']){
	    	$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle('B'.($index));
			$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
	    }
		$index++;
	}
}elseif ($gets['type'] == 'decision_shop'){
	$data         = array();
	$data 		  = $statLoginModel->getStatDecisionShopData($gets);
	$cf_tmp_agv   = array(
		'usage_rate' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['usage_rate']*100 ),
		'conversion_rate_android' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['conversion_a_rate']*100 ),
		'conversion_rate_ios' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['conversion_i_rate']*100 ),
		'arup_android' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['arup_android'] ),
		'arup_ios' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['arup_ios'] ),
		'arup_android_biz' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['arup_android_biz'] ),
		'usage_rate_one_key_install' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['usage_rate_one_key_install'] ),
		'single_cost' => sprintf ( '%01.2f' , $data['Rows']['average'][0]['single_cost'] )
	);
	$_letter      = 'A';
	$cf_tmp       = array(
		array('name'=>'店面名称','width'=>'50','key'=>'shop_name'),
		array('name'=>'日均销量','width'=>'20','key'=>'daily_sales'),
		array('name'=>'安卓连接手机台数','width'=>'20','key'=>'match_android'),
		array('name'=>'苹果连接台数','width'=>'20','key'=>'match_ios'),
		array('name'=>'使用率('.$cf_tmp_agv['usage_rate'].'%)','width'=>'20','key'=>'usage_rate'),
		array('name'=>'安卓下载台数','width'=>'20','key'=>'phone_android'),
		array('name'=>'安卓下载转化率('.$cf_tmp_agv['conversion_rate_android'].'%)','width'=>'20','key'=>'conversion_rate_android'),
		array('name'=>'苹果下载台数','width'=>'20','key'=>'phone_ios'),
		array('name'=>'苹果下载转化率('.$cf_tmp_agv['conversion_rate_ios'].')','width'=>'20','key'=>'conversion_rate_ios'),
		array('name'=>'安卓软件下载数量','width'=>'20','key'=>'soft_android'),
		array('name'=>'苹果软件下载量','width'=>'20','key'=>'soft_ios'),
		array('name'=>'安卓推荐软件下载量','width'=>'20','key'=>'biz_soft_android'),
		array('name'=>'安卓软件arup值('.($cf_tmp_agv['arup_android']).')','width'=>'20','key'=>'arup_android'),
		array('name'=>'苹果软件arup值('.$cf_tmp_agv['arup_ios'].')','width'=>'20','key'=>'arup_ios'),
		array('name'=>'推荐软件arup值('.$cf_tmp_agv['arup_android_biz'].')','width'=>'20','key'=>'arup_android_biz'),
		array('name'=>'单台成本('.$cf_tmp_agv['single_cost'].')','width'=>'20','key'=>'single_cost'),
		array('name'=>'一键安装台数','width'=>'20','key'=>'phone_one_key_install'),
		array('name'=>'一键安装使用率('.$cf_tmp_agv['usage_rate_one_key_install'].'%)','width'=>'20','key'=>'usage_rate_one_key_install')
	);
	foreach($cf_tmp as $item){
		$objPHPExcel  -> setActiveSheetIndex(0) -> setCellValue("{$_letter}1", $item['name']);
		$objPHPExcel  -> getActiveSheet()->getColumnDimension($_letter)->setWidth($item['width']);
		$_letter++;
	}
	foreach ($data['Rows']['analysis'] as $k=>$v){
		$_letter = 'A';
		foreach($cf_tmp as $item){
			$_val = $v[$item['key']];
			if(in_array($item['key'],array('usage_rate','conversion_rate_android','conversion_rate_ios','usage_rate_one_key_install'))){
				$_val 		  = sprintf ( '%01.2f' , $_val*100 );
				$_color 	  = $_val==$cf_tmp_agv[$item['key']]?'FFFFFF':($_val>$cf_tmp_agv[$item['key']]?'C00000':'77AD06');
				$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle($_letter.($k+2));
				$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($_color);
				$_val .= '%';
			}else if(in_array($item['key'],array('arup_android','arup_ios','arup_android_biz','single_cost'))){
				$_val         = sprintf ( '%01.2f' , $_val );
				$_color 	  = $_val==$cf_tmp_agv[$item['key']]?'FFFFFF':($_val>$cf_tmp_agv[$item['key']]?'C00000':'77AD06');
				$objFill_blue = $objPHPExcel->getActiveSheet()->getStyle($_letter.($k+2));
				$objFill_blue->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($_color);
			}
			$objPHPExcel->getActiveSheet()->setCellValue($_letter.($k+2), $_val);
			$_letter++;
		}
	}
}else if('get_mag_info' == $gets['type']){
	$memberModel = new ChAgent();
	$_level      = intval($gets['level']);
	$cf_tmp      = array(
		'1'=>array("level"=>"渠道客户评级","name"=>"渠道客户名称","address"=>"总部所在地区"),
		'2'=>array("level"=>"分公司评级","name"=>"分公司名称","address"=>"分公司所在地区"),
		'3'=>array("level"=>"店面评级","name"=>"店面名称","address"=>"店面所在地区")
	);
	
	$_letter     = 'A';
	$head_arr    = array(
		array('name'=>$cf_tmp[$_level]['name'],'width'=>'20','key'=>'name'),
		array('name'=>$cf_tmp[$_level]['address'],'width'=>'20','key'=>'company_address'),
		array('name'=>'负责人','width'=>'20','key'=>'contact_name'),
		array('name'=>'联系电话','width'=>'20','key'=>'contact_tel')
	);
	if(3 == $_level){
		$head_arr[] = array('name'=>'操作店员','width'=>'20','key'=>'real_name');
		$head_arr[] = array('name'=>'联系电话','width'=>'20','key'=>'telphone');
	}
	if(3 > $_level){
		$head_arr[] = array('name'=>'店面数量','width'=>'20','key'=>'sp_count_total');
		$head_arr[] = array('name'=>'已开通数量','width'=>'20','key'=>'sp_count_open');
	}
	if(1 == $_level){
		$head_arr[] = array('name'=>'拓展人员','width'=>'20','key'=>'sp_expand_er');
		$head_arr[] = array('name'=>'拓展进度','width'=>'20','key'=>'sp_expand_ing');
	}
	$head_arr[] = array('name'=>'评级','width'=>'20','key'=>'shop_prop');
	foreach($head_arr as $item){
		$_cf_css   = empty($item['css'])?$css_common:$item['css'];
		$_cf_width = empty($item['width'])?'20':$item['width'];
		$objPHPExcel -> getActiveSheet()->getStyle("{$_letter}1")->applyFromArray($_cf_css);
		$objPHPExcel -> setActiveSheetIndex(0) -> setCellValue("{$_letter}1", $item['name']);
		$objPHPExcel -> getActiveSheet()->getColumnDimension($_letter)->setWidth($_cf_width);
		$_letter++;
	}
	
	$data = $memberModel->getMagInfo($gets);
	foreach ((array)$data['Rows'] as $k=>$v){
		$_letter = 'A';
		foreach($head_arr as $item){
			$_val = $v[$item['key']];
			if('shop_prop' == $item['key']){
				$_tmp_cf_prop = array(
					'A'=>'优质',
					'B'=>'良好',
					'C'=>'一般',
					'D'=>'很差'
				);
				$_val = $_tmp_cf_prop[strtoupper($_val)];
			}
			$objPHPExcel->getActiveSheet()->setCellValue($_letter.($k+2), " ".$_val);
			$_letter++;
		}
	}
}else if('gold_exp' == $gets['type']){
	include_once(TP_LIB_DIR.'classes/biz/goldcoin/GoldCoinExchange.php');
	include_once(TP_LIB_DIR."classes/biz/member/Person.php");
	include_once(TP_LIB_DIR."classes/biz/member/Member.php");
	$o_person = new Person();
	$o_member = new Member();
	$obj_GoldCoinExchange = new GoldCoinExchange();
	extract($gets);
	$status = intval($status);
	$_type_ = trim($_type_);
	$head_arr = array(
		array('name'=>'申请时间','width'=>'20','key'=>'create_time'),
		array('name'=>'店员名','width'=>'20','key'=>'sales_name'),
		array('name'=>'店员信息','width'=>'30','key'=>'agent_info'),
		array('name'=>'联系电话','width'=>'20','key'=>'telphone'),
		array('name'=>'兑换金币(个)','width'=>'20','key'=>'goldcoin'),
		array('name'=>'人民币(元)','width'=>'20','key'=>'goldcoin_'),
		array('name'=>'兑换方式','width'=>'20','key'=>'ex_type'),
		array('name'=>'充值手机号','width'=>'20','key'=>'payment_mobnumber'),
		array('name'=>'开户人','width'=>'20','key'=>'account_name'),
		array('name'=>'开户身份证','width'=>'20','key'=>'identity_card'),
		array('name'=>'开户银行','width'=>'20','key'=>'bank_name'),
		array('name'=>'银行卡号','width'=>'20','key'=>'bank_card_no')
	);
	if(!$status){}
	else if(2 == $status){
		$head_arr[] = array('name'=>'审核完成时间','width'=>'20','key'=>'update_time');
	}else if(4 == $status){
		$head_arr[] = array('name'=>'付款完成时间','width'=>'20','key'=>'update_time');
	}else{
		$head_arr[] = array('name'=>'审核结果','width'=>'20','key'=>'description');
	}
	$_letter     = 'A';
	foreach($head_arr as $item){
		$objPHPExcel  -> setActiveSheetIndex(0) -> setCellValue("{$_letter}1", $item['name']);
		$objPHPExcel  -> getActiveSheet()->getColumnDimension($_letter)->setWidth($item['width']);
		$_letter++;
	}
	$_POST = $gets;
	if('lists' == $_type_){
		$arr_input['status'] = intval($_POST['status']);
		if(array_key_exists('ex_type', $_POST)){
			$arr_input['ex_type'] = intval($_POST['ex_type']);
		}
		$pageno = intval($gets['page']);
		$count  = intval($gets['pagesize']);
		$arr_list = (array)$obj_GoldCoinExchange->getGoldCoinExchangeList($arr_input,"limit {$count}");
		for ($i = 0;$i < count($arr_list);$i++){
			$arr_real_id[] = $arr_list[$i]['sales_id'];
		}
		$arr_real_id = array_unique($arr_real_id);
		unset($arr_hash_sales_info);
		foreach ($arr_real_id as $real_id){
			unset($arr_pinput);
			$arr_pinput['id'] = $real_id;
			$arr_person_info = $o_person->viewPersonById($arr_pinput);
			unset($arr_agent_input);
			$arr_agent_input['id'] = $arr_person_info['agent_id'];
			$arr_member_agent = $o_member->getMemberAgentInfo($arr_agent_input);
			$arr_hash_sales_info[$real_id]['real_name'] = $arr_person_info['real_name'];
			$arr_hash_sales_info[$real_id]['agent_info'] = join(' - ', $arr_member_agent);
		}
		for ($i = 0;$i < count($arr_list);$i++){
			$real_id = $arr_list[$i]['sales_id'];
			$arr_list[$i]['sales_name'] = $arr_hash_sales_info[$real_id]['real_name'];
			$arr_list[$i]['agent_info'] = $arr_hash_sales_info[$real_id]['agent_info'];
		}
	}elseif('list' == $_type_){
		$arr_input['status'] = 1;
		if(array_key_exists('ex_type', $_POST)){
			$arr_input['ex_type'] = intval($_POST['ex_type']);
		}
		$pageno = intval($gets['page']);
		$count  = intval($gets['pagesize']);
		$arr_list = (array)$obj_GoldCoinExchange->getGoldCoinExchangeList($arr_input,"limit {$count}");
		for ($i = 0;$i < count($arr_list);$i++){
			$arr_real_id[] = $arr_list[$i]['sales_id'];
		}
		$arr_real_id = array_unique($arr_real_id);
		unset($arr_hash_sales_info);
		foreach ($arr_real_id as $real_id){
			unset($arr_pinput);
			$arr_pinput['id'] = $real_id;
			$arr_person_info = $o_person->viewPersonById($arr_pinput);
			unset($arr_agent_input);
			$arr_agent_input['id'] = $arr_person_info['agent_id'];
			if(empty($arr_agent_input['id'])){
				$arr_hash_sales_info[$real_id]['real_name'] = '“该用户已删除”';
				$arr_hash_sales_info[$real_id]['agent_info'] = '由于用户数据清理，该用户已删除，无信息！';
			}else{
				$arr_member_agent = $o_member->getMemberAgentInfo($arr_agent_input);
				$arr_hash_sales_info[$real_id]['real_name'] = $arr_person_info['real_name'];
				$arr_hash_sales_info[$real_id]['agent_info'] = join(' - ', $arr_member_agent);
			}
		}
		for ($i = 0;$i < count($arr_list);$i++){
			$real_id = $arr_list[$i]['sales_id'];
			$arr_list[$i]['sales_name'] = $arr_hash_sales_info[$real_id]['real_name'];
			$arr_list[$i]['agent_info'] = $arr_hash_sales_info[$real_id]['agent_info'];
		}
	}
	//$arr_list
	foreach ((array)$arr_list as $k=>$v){
		$_letter = 'A';
		foreach($head_arr as $item){
			$_val = $v[$item['key']];
			if('goldcoin_' == $item['key']){
				$_val = "￥".floatval($v['goldcoin']/10);
			}else if('ex_type' == $item['key']){
				$cf__goldexchangeextype = array(
					"1"=>"手机话费充值",
					"2"=>"银行卡转账",
					"0"=>"旧数据"
				);
				$_val = $cf__goldexchangeextype[$_val];
			}
			$objPHPExcel->getActiveSheet()->setCellValue($_letter.($k+2), " ".$_val);
			$_letter++;
		}
	}
}

$suit_arr = array(
	'sketch' => '登陆概览',
	'sketch_ad' => '登陆概览',
	'detail' => '登陆概览',
	'use_sketch' => '使用概览',
	'use_sketch_ad' => '使用概览',
	'detail_dt' => '登陆详情',
	'detail_dt_ad' => '登陆详情',
	'detail_dtinfo' => '登陆详情',
	'use_sketch_shop' => '店面使用详情',
	'use_sketch_channel' => '店面使用详情',
	'use_sketch_channel_ad' => '店面使用详情',
	'decision_shop' => '决策平台店面维度',
	'phone_type_sketch' => '机型使用详情',
	'use_soft_detail' => $gets['soft_type']==1?'苹果软件下载详情':'安卓软件下载详情'
);
$filename = $gets['exp_name'].'-';
if ($gets['type']=='detail_dtinfo'||$gets['type']=='detail_dt'||$gets['type']=='detail_dt_ad') $filename.= date('Y年m月');
elseif($gets['type']=='use_sketch'){
	$filename.=date('Y年m月d日',strtotime($gets['date']));
}else $filename.=date('Y年m月d日',strtotime($gets['date'])).'到'.date('Y年m月d日',strtotime($gets['end_date']));
!empty($gets['name']) && $filename.="-搜索{$gets['name']}";
$filename.='-'.$suit_arr[$gets['type']];

if (in_array($gets['type'],array('member','mem_type','get_mag_info','gold_exp'))) $filename = "数据导出--".date('Y年m月d号H点i分s秒');

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-excel;");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header("Content-Disposition:attachment;filename=".iconv('utf-8', 'gbk//ignore', $filename).".xlsx");
header("Content-Transfer-Encoding:binary");
$objWriter->save('php://output');