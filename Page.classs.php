<?php
final class Page
{
    // 总记录数
    private $totalRows;
    // 每页记录数
    private $pageSize;
    // 总页数
    private $totalPage;
    // 页码关键字
    private $queryPage = "page";
    // 其他参数
    private $otherParma = '';
    // 分页显示页码个数
    private $len;
    // 当前url路径
    private $path;
    // 当前页
    private $curPage;
    // 上一页名字
    public $lastPageStr = '上一页';
    // 下一页
    public $nextPageStr = '下一页';
    // 首页
    public $startStr = '首页';
    // 尾页
    public $endStr = '尾页';
    // 是否需要首页
    public $startNeed = true;
    // 是否需要尾页
    public $endNeed = true;
    // 是否显示数字
    public $numShow = true;
    // 是否使用默认样式 ul class=pagination  li class=display,acitve
    public $defaultCss = true;
    function __construct($totalRows, $pageSize=10, $len=7)
    {
        if (!is_numeric($totalRows))die('totalRows param type error!');
        if (!is_numeric($pageSize))die('pageSize param type error!');
        if (!is_numeric($len))die('len param type error!');
        $this->totalRows = $totalRows;
        $this->pageSize = $pageSize;
        $this->len = $len;
        $this->init();
    }
    // 获取当前页
    public function __get($name)
    {
        if ($name == 'curPage'){
            return $this->curPage;
        }
    }
    // 初始化
    private function init()
    {
        $this->getTotalPage(); // 获取总页数
        $this->getPath(); // 获取url
        $this->getCurPage(); // 获取当前页
        if ($this->defaultCss)
            $this->loadCss();   // 加载css样式
    }
    // 设置页码关键字
    public function setKey($key)
    {
        $this->queryPage = $key;
    }
    // 设置其他参数
    public function setOtherKey(array $param)
    {
        $paramStr = '';
        foreach ($param as $k => $v){
            $paramStr .= '&'.$k.'='.$v;
        }
        $this->otherParma = $paramStr;
    }
    // 获取总页数
    private function getTotalPage()
    {
        $this->totalPage = ceil($this->totalRows/$this->pageSize);
    }
    // 获取链接地址
    private function getPath()
    {
        $this->path = $_SERVER['SCRIPT_NAME'];
    }
    // 获取并验证当前页码
    private function getCurPage()
    {
        $curPage = isset($_GET[$this->queryPage])?$_GET[$this->queryPage]:1;
        if ($curPage > $this->totalPage)
        {
            $curPage = $this->totalPage;
        } elseif ($curPage < 1){
            $curPage = 1;
        }
        $this->curPage = $curPage;
    }
    // 获取偏移量
    private function offset()
    {
        $offset = ($this->curPage - 1) * $this->pageSize;
        return $offset;
    }
    // 输出limit字符串
    public function limit()
    {
        return ' '.$this->offset().','.$this->pageSize.' ';
    }
    // 上一页
    private function lastPage()
    {
        if ($this->startNeed)
            $list = '<li><a href="'.$this->path.'?'.$this->queryPage.'=1'.$this->otherParma.'" >'.$this->startStr.'</a></li>';
        else
            $list = '';
        if ($this->curPage == 1){
            $list .= '<li class="disabled"><a href="#">'.$this->lastPageStr.'</a></li>';
        }else{
            $list .= '<li><a href="'.$this->path.'?'.$this->queryPage.'='.($this->curPage-1).$this->otherParma.'" >'.$this->lastPageStr.'</a></li>';
        }
        if ($this->numShow){
            if (($this->len % 2 == 0))
                $num = floor(($this->len-1)/2);
            else
                $num = ($this->len-1)/2;
            for ($i=$num; $i>0; $i--){
                $lastPage = $this->curPage-$i;
                if ($lastPage < 1) continue;
                $list .= '<li><a href="'.$this->path.'?'.$this->queryPage.'='.$lastPage.$this->otherParma.'" >'.$lastPage.'</a></li>';
            }
        }
        return $list;
    }
    // 当前页
    private function curPage()
    {
        if ($this->numShow)
            $list = '<li class="active"><a href="'.$this->path.'?'.$this->queryPage.'='.$this->curPage.$this->otherParma.'">'.$this->curPage.'</a></li>';
        else
            $list = '';
        return $list;
    }
    // 下一页
    private function nextPage()
    {
        if (($this->len % 2 == 0))
            $num = ceil(($this->len-1)/2);
        else
            $num = ($this->len-1)/2;
        $list = '';
        if ($this->numShow) {
            for ($i = 1; $i <= $num; $i++) {
                $nextPage = $this->curPage + $i;
                if ($nextPage > $this->totalPage) continue;
                $list .= '<li><a href="' . $this->path . '?' . $this->queryPage . '=' . $nextPage . $this->otherParma . '" >' . $nextPage . '</a></li>';
            }
        }
        if ($this->curPage >= $this->totalPage) {
            $list .= '<li class="disabled"><a href="#">'.$this->nextPageStr.'</a></li>';
        } else {
            $list .= '<li><a href="'.$this->path.'?'.$this->queryPage.'='.($this->curPage+1).$this->otherParma.'" >'.$this->nextPageStr.'</a></li>';
        }
        if ($this->endNeed)
            $list .= '<li><a href="'.$this->path.'?'.$this->queryPage.'='.$this->totalPage.$this->otherParma.'" >'.$this->endStr.'</a></li>';
        return $list;
    }
    // 生成链接
    public function links()
    {
        $html = '<ul class="pagination">';
        $html .= $this->lastPage();
        $html .= $this->curPage();
        $html .= $this->nextPage();
        $html .= '</ul>';
        return $html;
    }
    // 样式加载
    private function loadCss()
    {
        echo '<style>
                .pagination{
                    border:solid 1px #DDDDDD;
                    border-radius: 4px;
                    display: block;
                    list-style: none;
                    display: inline-block;
                    padding-left: 0;
                
                }
                .pagination>li>a{
                    text-decoration: none;
                    color: #428BCA;
                }
                .pagination>li{
                    display: inline;
                    background: #FFFFFF;
                    float: left;
                    border-right: solid 1px #dddddd;
                }
                .page-style-btn,
                .page-style-link{
                    border:none;
                }
                .page-style-btn>li{
                    margin: 0 3px;
                    border: solid 1px #dddddd !important;
                    border-radius: 3px !important;
                }
                .page-style-link>li{
                    border:none;
                    border-radius:0 !important;
                }
                .pagination>li>a{
                    text-align: center;
                }
                .pagination>li:first-child{
                    border-radius: 4px 0 0 4px;
                }
                .pagination>li:last-child{
                    border-radius: 0 4px 4px 0;
                }
                .pagination>li:last-child{
                    border-right:none;
                }
                .pagination>li.active{
                    background: #127EE8;
                    color:#ffffff;
                }
                .pagination>li.disabled{
                    opacity: 0.7;
                }
                .pagination>li.active>a{
                    color:#ffffff;
                }
                .pagination>li>a{
                    float: left;
                    padding: 6px 12px;
                }
                .page-lg>li>a{
                    padding: 10px 20px;
                    font-size: 20px;
                }
                .page-sm>li>a{
                    padding: 0px 5px;
                    font-size:12px;
                }
                .pagination>li:hover{
                    background: #EAEAEA;
                }
                .pagination>li.active:hover{
                    background: #127EE8;
                    
                }
                .pagination>li.active>a{
                    cursor: default;
                }
                .pagination>li>a:hover{
                    text-decoration: none;
                }
                .page-style-link>li>a:hover{
                    text-decoration: underline;
                }
                .pagination>li.disabled>a:hover{
                    cursor: not-allowed;
                }
                </style>';
    }
   public function __destruct()
   {
       echo 'object page is destoryed';
   }
}
