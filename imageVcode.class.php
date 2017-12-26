<?php
session_start();
//验证码类
/*
 * 1.背景
 * 2.干扰
 * 3.文字
 * 4.输出
 */
class Verify{
    public $img;//用于保存GD资源
    public $config = array(
                          'width'=>80,
                          'height'=>30,
                          'length'=>4,
                          'type'=>3
    );
    function __construct($config=array()){
        $this->config=array_merge($this->config,$config);
    }


    //1.背景
    private  function getBg(){
        //背景图
        $path = "./bgs";
        $bgFile = array();
        $dir = opendir($path);
        while(($filename=readdir($dir))!==false){
            //过滤掉.和..
            if($filename!='.'&&$filename!='..'){
                $bgFile[] = $filename;
            }
        }
        closedir($dir);
        //从数组$bgFile随机取出一个文件名称
        $bgName =$path.'/'.$bgFile[array_rand($bgFile)];
        //获取图片信息
        list($width,$height,$type)=getimagesize($bgName);
        //已知 图片文件格式的数值形式和字符串形式的对应关系固定
        $type_array=array(1=>'gif',2=>'jpeg',3=>'png');
        $kzm=$type_array[$type];
        //拼接函数
        $hanshu='imagecreatefrom'.$kzm;
        //从现有图片中获取资源
        $src_img = $hanshu($bgName);
        $this->img = imagecreatetruecolor($this->config['width'],$this->config['height']);
        list($src_w,$src_h) = getimagesize($bgName);
        imagecopyresampled($this->img,$src_img,
            0,0,
            0,0,
            $this->config['width'],$this->config['height'],
            $src_w,$src_h);
        imagedestroy($src_img);

    }
    //2.干扰
    private function disturb(){
        //100个随机出现的点
        $xWidth = $this->config['width']-1;//79
        $yHeight = $this->config['height']-1;//29
        for($i=0;$i<100;$i++){
            $color = imagecolorallocate($this->img, mt_rand(100,200), mt_rand(100,200), mt_rand(100,200));
            imagesetpixel($this->img, mt_rand(1,$xWidth),
                mt_rand(1,$yHeight), $color);
        }
        //10条随机出现的线
        for($i=0;$i<10;$i++){
            $color = imagecolorallocate($this->img, mt_rand(100,200), mt_rand(100,200), mt_rand(100,200));
            imageline($this->img,mt_rand(1,$xWidth),
                mt_rand(1,$yHeight),mt_rand(1,$xWidth),
                mt_rand(1,$yHeight),$color);
        }

    }
    //3.文字
    private function getWord(){
        $string = $this->getString($this->config['type']);
        //从上面的字符串中随机截取,每次截取1位,共截取四次
        $font = 5;
        $sWord='';//用于存储每次截取到的字符
        for($i=0;$i<$this->config['length'];$i++){
            $color = imagecolorallocate($this->img, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
            $start = mt_rand(0,strlen($string)-1);
            $code = substr($string,$start,1);
            $sWord.=$code;
            $x = ($this->config['width']/$this->config['length'])*$i+5;
            $y = mt_rand(5,10);
            imagestring($this->img, $font, $x, $y, $code, $color);
        }
        $_SESSION['vcode']=$sWord;
    }
    //确定字库的成员方法
    function getString($type){
        switch($type){
            case 1:
                $string = "0123456789";
                break;
            case 2:
                $string = "abcdefghijklmnopqrstuvwxyz";
                break;
            case 3:
                $string = "0123456789abcdefghijklmnopqrstuvwxyz";
                break;
            case 4:
                $string = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                break;
        }
        return $string;
    }
    //4.输出
    function entry(){
        $this->getBg();
        //$this->disturb();
        $this->getWord();
        //通知浏览器输出图片
        header("content-type:image/png");
        //GD库函数输出图片
        imagepng($this->img);
    }
    //析构方法
    function __destruct(){
        // 释放内存
        imagedestroy($this->img);
    }
}
//__construct($width,$height,$length)
/* $config = array('length'=>5,'type'=>4);
$v = new Verify($config);
$v->entry(); */



















