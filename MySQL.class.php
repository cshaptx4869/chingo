<?php
//单例模式的数据库类
/*
 * 完成数据库的
 * 增,删,改,查
 */
class MySQL{
    //声明成员属性
    public $link;
    //用于设置获取结果集的数组格式
    public $fetchStyle;
    private static $obj = null;
    
    
    private function __construct($server,$username,$password,$dbname){
        $this->link = mysqli_connect($server,$username,$password,$dbname);
    }
    
    //在类中定义成员方法用于获取类对象
    static function getObj($server,$username,$password,$dbname){
        if(is_null(self::$obj)){
            self::$obj = new MySQL($server,$username,$password,$dbname);
        }
        return self::$obj;
    }
    //增
    function insert($tbname,$data){
        /*
         * $data = array('name'=>'jack03',
         *               'pwd'=>'123',
         *               'address'=>'bj',
         *               'email'=>'jack03@qq.com');
         * $keys = name,pwd,address,email 
         * $values = jack03','123','bj','jack03@qq.com             
         */
        //获取data数组中所有的键名称
        $keys_arr = array_keys($data);
        //将$keys_arr转换为字符串
        $keys = implode(',', $keys_arr);
        //将$data数组中的值转换为字符串
        $values = implode('\',\'',$data);
      
        //完成新增一个用户的功能       
        $query = 'insert '.$tbname.'('.$keys.') 
                  value (\''.$values.'\')';
        
        //echo $query;exit;
        //echo "<hr/>";
        $boolean = mysqli_query($this->link, $query);
        //获取被影响行数
        return mysqli_affected_rows($this->link);
    }
    
    //删
    function delete($tbname,$where=null){
       
        //$query = is_null($where)?"delete from $tbname":"delete from $tbname  where $where";
        $where = is_null($where)?'':' where '.$where;
        $query = 'delete from '.$tbname.$where;
        
       /* if(is_null($where)){
            //where = null;
            $query = "delete from $tbname";            
        }else{
            //where = id=1;
            $query = "delete from $tbname
                      where $where";
        } */     
        
        mysqli_query($this->link, $query);
        return mysqli_affected_rows($this->link);
    }
    
    //改
    function update($tbname,$data,$where=null){
       /*
        * $data = array(
        *         "name"=>"jerry",
        *         "pwd"=>"555"         
        *         );
        * $sets = name='jerry',pwd='555'
        */
        $sets = '';
        foreach($data as $key=>$value){
            if($sets!=''){
                $sets.=',';
            }
            $sets.=$key.'=\''.$value.'\'';
        }
        $where = is_null($where)?'':' where '.$where;
        $query = 'update '.$tbname.' set '.$sets.$where;
        
        mysqli_query($this->link,$query);
        //被影响行数
        return mysqli_affected_rows($this->link);
    }
    //拼装SQL语句
    function buildQuery($tbname,$config){
        /*
         * $config=array('fields',
         *               'where',
         *               'group',
         *               'having',
         *               'order',
         *               'limit');
         */
        //处理字段下标 config['fields']
        if(isset($config['fields'])&&$config['fields']!=''){
            $fileds = $config['fields'];            
        }else{
            //没给
            $fileds = '*';
        }
        $query = 'select '.$fileds.' from '.$tbname;
        
        if(isset($config['where'])&&$config['where']!=""){
            //$query =$query." where ".$where;
            $query .=' where '.$config['where'];
        }
        //$config['group']
        if(isset($config['group'])&&$config['group']!=""){
            $query.= ' group by '.$config['group'];
        }
        //$config['having']
        if(isset($config['having'])&&$config['having']!=""){
            $query.= ' having '.$config['having'];
        }
        //$config['order']
        if(isset($config['order'])&&$config['order']!=""){
            $query.= ' order by '.$config['order'];
        }
        //$config['limit']
        if(isset($config['limit'])&&$config['limit']!=""){
            $query.= ' limit '.$config['limit'];
        }
        //echo $query;exit;
        //返回拼装好的SQL语句
        return $query;
    }
    //设置成员属性fetchStyle
    function setStyle($fetchstyle){
        //判断接收到的参数是否是三个系统常量之一
        //MYSQLI_ASSOC,MYSQLI_BOTH,MYSQLI_NUM
        if($fetchstyle!=MYSQLI_ASSOC&&$fetchstyle!=MYSQLI_BOTH&&$fetchstyle!=MYSQLI_NUM){
            $fetchstyle = MYSQLI_ASSOC;
        }
        $this->fetchStyle = $fetchstyle;
    }
    
    //查
    function fetchAll($tbname,$config=array()){       
        $query = $this->buildQuery($tbname,$config);
       
        $result = mysqli_query($this->link,$query);
        //从结果集中获取数据
        return mysqli_fetch_all($result,$this->fetchStyle);
    }  
    
    //查询一条数据
    function fetchOne($tbname,$config=array()){
        //news 表中查询一条记录 
        $query = $this->buildQuery($tbname,$config);
      
        $result = mysqli_query($this->link,$query);
        //从结果集中获取数据
        var_dump($this->fetchStyle);
        //MYSQLI_BOTH,MYSQLI_ASSOC,MYSQLI_NUM
        return mysqli_fetch_array($result,$this->fetchStyle);
    }
    
    function __destruct(){    
        mysqli_close($this->link);
    }
}









