<?php
    /*定时备份数据库文件 E:/mysql_dump/ */
    //设置时区
    date_default_timezone_set('PRC');
    //创建目录
    $dirname = 'e:/mysql_dump/'.date('Y-m-d');
    if(!file_exists($dirname)){
        mkdir($dirname);
    }
    //sql文件保存地址
    $filename = $dirname.'/'.date('YmdHis');
    //命令行
    $command = 'e:/wamp/bin/mysql/mysql5.6.17/bin/mysqldump -uroot -p123456 -B pratice > '.$filename.'.sql';
    //exec — 执行一个外部程序 格式 string exec ( string $command [, array &$output [, int &$return_var ]] )
    exec($command);
    //压缩大于20M的sql文件
    if(filesize($filename.'.sql')>20000000){
        $zip = new ZipArchive();
        if($zip->open($filename.'.zip',ZipArchive::OVERWRITE)===TRUE){    //ZipArchive::OVERWRITE表示如果zip文件存在，就覆盖掉原来的zip文件。
            $zip->addFile($filename.'.sql');        //注意压缩的目标文件必须存在哦 若存在第二个参数 则会把原文件名重命名
            $zip->close();
        }
        unlink($filename.'.sql');
    }
    //超过5天则删除备份文件
    $dir = dirname(__FILE__);
    $handler = opendir($dir);
    while ($files = readdir($handler)){
        if ($files != '.' && $files != '..' ){
            $is_dir = $dir.'/'.$files;
            //是目录
            if(is_dir($is_dir)){
                //超过5天
                if (filectime($is_dir)<time()-5*24*3600){
                    $hander = opendir($is_dir);
                    while($file = readdir($hander)){
                        if ($file != '.' && $file != '..' ){
                            unlink($is_dir.'/'.$file);
                        }
                    }
                    closedir($hander);
                    rmdir($is_dir);
                }
            }
        }
    }
    closedir($handler);

