<?php
function handle_good_input_cmd(){
  global $smarty,$conn,$catalog_id;
    if($_FILES['file_stu']){
        //判断文件上传的类型
        $name= $_FILES['file_stu']['name'];
        $file = $_FILES['file_stu']['tmp_name'];
        $file_types = explode('.', $name);
        //获得后缀名
        $file_type = $file_types[count($file_types)-1];
        $type = array('xls','xlsx');
        if(!in_array($file_type, $file_types)){
            reload_js('请正确选择上传文件类型','handler.php?catalog_id='.$catalog_id.'&cmd=good_input');
        }
        //创建文件夹,返回保存地址
        $dsFile = get_file_save_path($name);
        //移动地址
        $dsFile = $dsFile.$name;

        $test = move_uploaded_file($file,$dsFile);
        //判断是否是合法的上传文件
        if($test){
            if($file_type == 'xls'){
                $inputFileType = 'Excel5';
            }elseif($file_type == 'xlsx'){
                $inputFileType = 'Excel2007';
            }
            //读取文件
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $objPHPExcel = $objReader->load($dsFile);//加载文件

            $objWorksheet = $objPHPExcel->getActiveSheet();//获得当前活动sheet
            //return int Highest row number
            $highestRow = $objWorksheet->getHighestRow();//取得总行数
            //return string Highest column name
            $highestColumn = $objWorksheet->getHighestColumn();//取得总列数 String
            //return 	int Column index (base 1 !!!)
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            $headtitle=array();
            //从第二行开始读取
            for ($row = 2;$row <= $highestRow;$row++) {
                $strs = array();
                //注意highestColumnIndex的列数索引从0开始
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    //return 	PHPExcel_Cell   return $this->_value;
                    $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
                $info = array(
                    // 'id'=>"$strs[0]",
                    'spbianhao' => iconv('utf-8', 'gbk', $strs[0]),
                    'spcname' => iconv('utf-8', 'gbk', $strs[1]),
                    'spbname' => iconv('utf-8', 'gbk', $strs[2]),
                    'spename' => iconv('utf-8', 'gbk', $strs[3]),
                    'price' => iconv('utf-8', 'gbk', $strs[4]),
                    'spfenzishi' => iconv('utf-8', 'gbk', $strs[5]),
                    'spdescript' => iconv('utf-8', 'gbk', $strs[6]),
                    'spchundu' => iconv('utf-8', 'gbk', $strs[7]),
                    'sprongliang' => iconv('utf-8', 'gbk', $strs[8]),
                    'spguige' => iconv('utf-8', 'gbk', $strs[9]),
                    'spstock' => "$strs[10]",
                    'changjiaid' => iconv('utf-8', 'gbk', $strs[11]),
                    'cateid' => iconv('utf-8', 'gbk', $strs[12]),
                    'uptime' => time(),
                );
                $result = $conn->AutoExecute('goods', $info, 'INSERT');
            }
            if($result){
                reload_js('数据导入成功','handler.php?cmd=list&catalog_id='.$catalog_id);
            }
        }
    }
  print $smarty->fetch('input.html');

}
