<?php
//PDO封装
class MySQL{
    private $obj;
    //连接数据库(私有的构造：防止实例化)
    private function __construct(){
        $dsn='mysql:host=localhost;dbname=infosystem';
        $username='root';
        $password='123';
        $this->obj=new PDO($dsn,$username,$password);
    }
    //防止克隆对象 赋初值
    private function __clone(){
        
    }
    //利用单例，获得该类的对象
    static function getObj(){
        static $db=null;
        if ($db==null){
            $db=new MySQL();
        }
        return $db;
    }
    //增
    function insert($tbname,$post){
        $keys_array=array_keys($post);
        //print_r($keys_array);        
        $keys=implode(',', $keys_array);
        $values=implode("','", $post);
        $sql='insert into '.$tbname.'('.$keys.')'.'value'.'(\''.$values.'\')';
        //echo $sql;
        $row=$this->obj->exec($sql);
        return $row;
    }
    //删
    function delete($dbname,$where=null){
        if(!$where==null){
            $where=' where '.$where;
        }
        $sql='delete from '.$dbname.$where;
        //echo $sql;
        $row=$this->obj->exec($sql);
        return $row;
    }
    //改
    function update($dbname,$post,$where=null){
        $sets = '';
        foreach($post as $key=>$value){
            if($sets!=''){
                $sets.=',';
            }
            $sets.=$key.'=\''.$value.'\'';
        }
        $where=is_null($where)?'':' where '.$where;
        $sql='update '.$dbname.' set '.$sets.$where;
        //echo $sql;
        $row=$this->obj->exec($sql);
        return $row;
    }
    //查全部数据
    function queryAll($dbname,$fetchstyle=null){
        $sql='select * from '.$dbname;
        $result=$this->obj->query($sql);
        $rows=$result->fetchAll($fetchstyle);
        return $rows;
    }
    //拼装sql语句
    function buildSql($config,$tbname){
        if(isset($config['fields'])&&$config['fields']!=''){
            $fileds = $config['fields'];
        }else{
            //没给
            $fileds = '*';
        }
        $sql = 'select '.$fileds.' from '.$tbname;
        
        if(isset($config['where'])&&$config['where']!=""){
            //$query =$query." where ".$where;
            $sql.=' where '.$config['where'];
        }
        //$config['group']
        if(isset($config['group'])&&$config['group']!=""){
            $sql.= ' group by '.$config['group'];
        }
        //$config['having']
        if(isset($config['having'])&&$config['having']!=""){
            $sql.= ' having '.$config['having'];
        }
        //$config['order']
        if(isset($config['order'])&&$config['order']!=""){
            $sql.= ' order by '.$config['order'];
        }
        //$config['limit']
        if(isset($config['limit'])&&$config['limit']!=""){
            $query.= ' limit '.$config['limit'];
        }
        // echo $sql;
        //返回拼装好的SQL语句
        return $sql;
    }
    //查询一条数据
    function queryOne($tbname,$fetchStyle,$config){
        $sql=$this->buildSql($config, $tbname);
        //echo $sql;
        $result=$this->obj->query($sql);
        $rows=$result->fetch($fetchStyle);
        return $rows;
    }
    
    
    
    
    
    
}












