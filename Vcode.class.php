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
                          'type'=>1);

    //array_merge 合并两个数组    返回:合并后的数组
    function __construct($config=array()){
        $this->config=array_merge($this->config,$config);
    }


    //1.背景
    private  function getBg(){
        //浅色 且 颜色随机
        $this->img = imagecreatetruecolor($this->config['width'], $this->config['height']);
        $color = imagecolorallocate($this->img, mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
        //填充画布
        imagefill($this->img,0,0,$color);
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

    //可选输出字符串
    private function getString(){
        switch ($this->config['type']){
            case 1:
                $string="0123456789";
                break;
            case 2:
                $string="abcdefghijklmnopqrstuvwxyz";
                break;
            case 3:
                $string="0123456789abcdefghijklmnopqrstuvwxyz";
                break;
            case 4:
                $string="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIKLMNOPQRSTUVWXYZ";
                break;
        }
        return $string;
    }


    //3.文字
    private function getWord(){
        $this->getString();
        //从上面的字符串中随机截取,每次截取1位,共截取四次
        $font = 5;
        $sWord = '';
        for($i=0;$i<$this->config['length'];$i++){
            $color = imagecolorallocate($this->img, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
            $start = mt_rand(0,strlen($this->getString())-1);
            $code = substr($this->getString(),$start,1);
            $sWord.=$code;
            $x = ($this->config['width']/$this->config['length'])*$i+5;
            $y = mt_rand(5,10);
            imagestring($this->img, $font, $x, $y, $code, $color);
        }
        $_SESSION['vcode'] = $sWord;
    }


    //4.输出
    function entry(){
        $this->getBg();
        $this->disturb();
        $this->getWord();
        //通知浏览器输出图片
        header("content-type:image/png");
        //GD库函数输出图片
        imagepng($this->img);
    }


    //析构方法
    function __destruct(){
        //释放内存
        imagedestroy($this->img);
    }
}
//__construct($width,$height,$length)
/* $config = array('length'=>5,'type'=>4);
$v = new Verify($config);
$v->entry(); */



















