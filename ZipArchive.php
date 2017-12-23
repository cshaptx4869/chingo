<?php
    header('content-type:text/html;charset=utf-8;');
    /* 解压缩zip文件 */
    $zip=new ZipArchive;//新建一个ZipArchive的对象
    if($zip->open('demo.zip')===TRUE){
        $zip->extractTo('../demo/images',array('pear_item.gif', 'testfromfile.php'));//假设解压缩到在上级的demo路径下images文件夹内 若文件夹不存在则创建 若有第二个参数,则指定要解压的文件
        $zip->close();//关闭处理的zip文件
    }

    /* 压缩为zip文件 */
    $zip=new ZipArchive;
    if($zip->open('test.zip',ZipArchive::OVERWRITE)===TRUE){    //ZipArchive::OVERWRITE表示如果zip文件存在，就覆盖掉原来的zip文件。
        $zip->addFile('../demo/image.txt','1.txt');        //假设要加入的文件是image.txt 注意压缩的目标文件必须存在哦 若存在第二个参数 则会把原文件名重命名
        $zip->addFile('../demo/images.txt','2.txt');       //压缩第二个文件
        $zip->close();
    }

    /* 文件追加内容添加到zip文件 */
    $zip=new ZipArchive;
    $res=$zip->open('test.zip',ZipArchive::CREATE); //CREATE 不存在zip则创建  使用ZIPARCHIVE::CREATE，系统就会往原来的zip文件里添加内容。
    if($res===TRUE){
        $zip->addFile('image.txt');
        $zip->addFile('images.txt');
        $zip->addFromString('image.txt','file content goes here'); //test.zip 文件中 向image.txt文件追加内容 若 image.txt文件不存在,则自动创建
        $zip->close();
        echo 'ok';
    }else{
        echo 'failed';
    }

    /* 将文件夹打包成zip文件 */
    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。 路径不要出现中文
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归压缩
                    addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
        }
        @closedir($path);
    }
    $zip=new ZipArchive();
    //压缩
    if($zip->open('dabao.zip', ZipArchive::OVERWRITE)=== TRUE){
        addFileToZip('dabao', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
        $zip->close(); //关闭处理的zip文件
    }


    /*                       TIPS      ZipArchive 类                                    */

    /* 一.方法
          mixed ZipArchive::open ( string $filename [, int $flags ] )
     *    参数:
     *        $filename : 打开的ZIP归档文件的文件名。
     *        $flags : ZIPARCHIVE::OVERWRITE  总是以一个新的压缩包开始，此模式下如果已经存在则会被覆盖。
     *                 ZipArchive::CREATE     如果不存在则创建一个zip压缩包。
     *                 ZIPARCHIVE::EXCL       如果压缩包已经存在，则出错。
     *                 ZIPARCHIVE::CHECKCONS  对压缩包执行额外的一致性检查，如果失败则显示错误。
     *    返回: 成功时返回true 否则返回错误代号
     *
     *   bool ZipArchive::addFile ( string $filename [, string $localname = NULL [, int $start = 0 [, int $length = 0 ]]] )
     *   参数:
     *       $filename : 要打包文件的路径
     *       $localname : 给原文件名重命名
     *
     *   bool ZipArchive::extractTo ( string $destination [, mixed $entries ] )
     *   参数 :
     *       $destination : 要解压的地址
     *       $entries : 提取的条目。它接受一个单独的条目名或一个名称数组。
     *
     * */
