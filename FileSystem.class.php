<?php
header('Content-Type:text/html;charset=utf-8');
/*
 * 目录:
 * 创建目录
 * 删除目录
 * 重命名目录
 * 复制目录
 * 判断目录是否为空
 * 统计目录下文件和目录
 * 文件:
 */

class FileSystem{
    //创建目录
    function mkDirectory($path,$mode=0777,$recursive=true){
        //提高系统函数mkdir的成功几率
        //减少mkdir()系统函数报错
        //$path="test"//false
        if(!file_exists($path)){
            return mkdir($path,$mode,$recursive);
        }
        return false;
    }

    //删除目录-空目录 rmdir()
    //       非空目录 清空目录下的文件,然后删除空目录
    function rmDirectory($path){
        if(file_exists($path)){
            //存在
            if(is_file($path)){
                //存在文件
                return unlink($path);
            }else{
                //存在目录,递归删除目录
                $dir=opendir($path);
                while(($filename=readdir($dir))!==false){
                    //记得一定要过滤掉.和.. 不然拉闸
                        if($filename!='.'&&$filename!='..'){
                               //拼装目录
                               $filename=$path.'/'.$filename;
                               if(is_dir($filename)){
                                   $this->rmDirectory($filename);
                               }else{
                                   unlink($filename);
                               }
                        }
                }
                //释放资源  已经删除掉了目录下的文件
                closedir($dir);
                return rmdir($path);
            }
        }else {
            //不存在
            return false;
        }
    }

    //重命名目录
    function renameDirectory($oldname, $newname){
        /* -减少函数报错
         * -提高函数执行成功率
         *  $oldname 不存在
         *  $newname 存在
         *  */
        if (file_exists($oldname)&&!file_exists($newname)){
            return rename($oldname, $newname);
        }
        return false;
    }

    //复制目录
    function copyDirectory($source,$dest){
        //判断
        if(file_exists($dest)){
            //存在
            if(is_file($dest)){
                //$dest 是文件
                return false;
            }
        }else{
            //不存在
            $this->mkDirectory($dest);
        }
        //复制内容
        $dir=opendir($source);
        while(($filename=readdir($dir))!==false){
            if($filename!='.'&&$filename!='..'){
                //拼装目录
                $sFilename=$source.'/'.$filename;
                $dFilename=$dest.'/'.$filename;
                if(is_dir($sFilename)){
                    $this->copyDirectory($sFilename, $dFilename);
                }else{
                    copy($sFilename, $dFilename);
                }
            }
        }
        closedir($dir);
        return true;
    }

    //判断目录是否为空
    function isEmptyDir($path){
        $dir=opendir($path);
        while (($filename=readdir($dir))!==false){
            if(!($filename=='.'||$filename=='..')){// [!(true||false)]==false 除了点 如果还有文件则整体判断为真,return false;
                return false;
            }
        }
        //说明目录下除了.和..之外,没有其他文件,为空目录
        closedir($dir);
        return true;
    }

    //统计目录下的文件数和目录数
    function countDirectory($path){
        static $dirInfo=array('fileCount'=>0,'dirCount'=>0);

        //打开目录
        $dir=opendir($path);
        while($filename=readdir($dir)){
            //过滤
            if(($filename!='.'&&$filename!='..')){
                //拼装路径
                $filename=$path.'/'.$filename;
                if(is_dir($filename)){
                    //是目录
                    $dirInfo['dirCount']+=1;
                    //计算统计a子目录下文件和目录数的代码
                    $this->countDirectory($filename);
                }else{
                    //是文件
                    $dirInfo['fileCount']+=1;
                }
            }
        }
        closedir($dir);
        return $dirInfo;
    }


    //获取文件信息
    function getFileInfo($filename){
        //文件名称
        $fileInfo['basename']=pathinfo($filename,PATHINFO_BASENAME);
        //文件的扩展名
        $fileInfo['extension']=pathinfo($filename,PATHINFO_EXTENSION);
        //文件的创建时间
        $fileInfo['filectime']=date('Y-m-d H:i:s',filectime($filename));
        //文件的修改时间
        $fileInfo['filemtime']=date('Y-m-d H:i:s',filemtime($filename));
        //文件的最后访问时间
        $fileInfo['fileatime']=date('Y-m-d H:i:s',fileatime($filename));
        //文件的大小
        $fileInfo['size']=$this->getFileSize($filename);//字节数
        return $fileInfo;
    }


    //获取文件大小
    function getFileSize($filename){
        $size=filesize($filename);
        $DW=array('B','KB','MB','GB','TB');
        $n=0;
        while($size>=1024){
            $size/=1024;
            $n++;
        }
        return $size.$DW[$n];
    }










}
$f=new FileSystem;

/*
1.创建目录
$path='./test';
var_dump($f->mkDirectory($path));
*/


/*
2.删除目录
var_dump($f->rmDirectory($path));
*/


/*
3.复制文件
$oldname='old';
$newname='new';
var_dump($f->renameDirectory($oldname, $newname));
*/


//var_dump($f->copyDirectory('test', 'new'));


//4.判断是否为空目录
/* var_dump($f->isEmptyDir('new'));
echo $f->isEmptyDir('one'); */


//5.统计目录下的文件数和目录数
//print_r($f->countDirectory('one'));


//6.获取文件信息
print_r($f->getFileInfo('one/1.txt'));


//7.获取文件的大小
//echo '文件的大小为:'.$f->getFileSize('one/1.txt');
















