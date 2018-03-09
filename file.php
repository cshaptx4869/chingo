<?php
global $import_file_path;

$import_file_path = "attachments/Excel";


function unlink_excel_file($filename){
	$file_path = WESCMS_ROOT .'/../'.$filename;
	if(file_exists($file_path)){
		@unlink($file_path);
	}		
}

function up_excel_file($input_name){
	$excels = array(
				'application/excel',
				'application/vnd.ms-excel',
				'application/msexcel',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/download',
	);
	if ( $_FILES[$input_name]['size'] == 0 )	{
		halt_js('请上传文件');
	}
	// if ( $_FILES[$input_name]['type'] == 'application/octet-stream' )	{
	// 	halt_js('请关闭excel再进行上传');
	// }
	/*if ( ! in_array($_FILES[$input_name]['type'], $excels) ){
		halt_js('请上传正确的Excel文档');
	}*/
	if ( $_FILES[$input_name]['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
		$excel_type = '2007';
	}
	else{
		$excel_type = '5';
	}
	if(is_uploaded_file($_FILES[$input_name]['tmp_name']))
	{
		$name = $_FILES[$input_name]['name'];
		$type = get_file_ext($name);
		$passtypes = array('xls', 'xlsx');
		if(!check_file_ext($type,$passtypes))
			halt_js('文件类型不正确！');
			
		$dsFile = save_import_path($type,$name);
		$test = move_uploaded_file($_FILES[$input_name]['tmp_name'],WESCMS_ROOT .'/../' . $dsFile);
		if(!$test)
		{
			halt_js('文件移动失败!');
		}
		return array($dsFile,$excel_type);
	}
	 else{
		 halt_js('意外错误！请联系管理员');
	}
}

/**
 * 读取上传的excel内容
 * 参数依次为：文件域名称，是否保留第一行表头（0，1），每列对应的字段名称）
*/
function get_excel_file_data($file_info,$have_th,$cols_fields = array()){
	
	$type = "Excel".$file_info[1];
	$excel = WESCMS_ROOT .'/../'. $file_info[0];
	$objReader = PHPExcel_IOFactory::createReader($type);
	$objPHPExcel = $objReader->load($excel);
	$sheet = $objPHPExcel->getSheet(0);
	
	$data = array();
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumStr = $sheet->getHighestColumn(); // 取得总列数
	$len = strlen($highestColumStr);
	$highestColumn = ($len - 1) * 26 + ord($highestColumStr{($len - 1)}) - ord('A') + 1;
	
	//print $highestColumn;
	
	$fields_num = count($cols_fields);
	if( $fields_num> 0 && $fields_num > $highestColumn){
		halt_js('上传的表格列数不正确！');	
	}
	
	/* 循环读取excel文件 */
	$data = array();
	
	$i = ($have_th == true) ? 2 : 1;  //表头判断从第几行开始读取
	//print $i;
	for ($i ; $i <= $highestRow; $i++)
	{
		$kh = TRUE;
		for ($j = 'A', $k = 0; $k < $highestColumn; $k++, $j++)
		{
			if($k > $fields_num-1){
				continue;
				
			}
			
			$index = ($fields_num>0) ? $cols_fields[$k] : $k;
			
			$val = $objPHPExcel->getActiveSheet()->getCell("$j$i")->getValue();
			
			if ( is_object($val) ) halt_js("Excel中{$j}{$i}单元格数据填写有问题请删除重新填写");
			
			if ( ! empty($val) )
			{
				$data[$i][$index] = trim($val);
				$kh = FALSE;
			}
			else{
				$data[$i][$index] = '';
			}
		}
		
		if ( $kh === TRUE )
		{
			unset($data[$i]);
		}
	}
		
	
	return $data;
	
}


/*
 *  文件上传
 */
function get_file_ext($filename)
{
	$dot_pos = strrpos($filename,'.');
	$type = $dot_pos === FALSE ? NULL : strtolower(substr($filename,$dot_pos+1));
	return $type;
}
function check_file_ext($type,$passtypes = array())
{
	if(is_array($passtypes) && $passtypes){
		if(in_array($type,$passtypes)){
			return true;
		}
		else{
			return false;
		}
	}
}
function save_import_path($ext,$name)
{
	global $import_file_path;
	
	$strFilePrefix = $import_file_path;
	if(!file_exists(WESCMS_ROOT .'/../' .$strFilePrefix))
	{
		mkdir(WESCMS_ROOT .'/../' . $strFilePrefix);
	}
	$file_id = date("ymdhis");
	$filename = $file_id .'.'.$ext;
	$dstFile = $strFilePrefix . '/' . $filename;
	return $dstFile;
}


?>