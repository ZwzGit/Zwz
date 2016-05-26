<?php
class Redis {

    public $handler;
    public $options = array();

    /**
     * 构造函数
     * @access public
     * @param array $options    缓存参数
     */
    public function __construct($options=array()){
        //判断是否加载redis扩展
        if(!extendion_loaded('redis')){
            exit('请开启扩展redis');
        }

        if(empty($options)){
            $options = array(
                'host'          =>  config_val('REDIS_HOST')?config_val('REDIS_HOST'):'127.0.0.1',
                'prot'          =>  config_val('REDIS_PORT')?config_val('REDIS_PORT'):6379,
                'timeout'       =>  config_val('DATA_CACHE_TIMEOUT')?config_val('DATA_CACHE_TIMEOUT'):false,
                'persistent'    =>  false,
            );
        }

        $this->options = $options;
        $this->options['expire'] = isset($options['expire'])?$options['expire']:config_val('DATA_CACHE_TIME');
        $this->options['prefix'] = isset($options['prefix'])?$options['prefix']:config_val('DATA_CACHE_PREFIX');
        $this->options['length'] = isset($options['length'])?$options['length']:0;
        $func = $options['persistent']?'pconnect':'connect';
        $this->handler = new \Redis;
        $options['timeout'] === false?
            $this->handler->$func($options['host'],$options['port']):
            $this->handler->$func($options['host'],$options['port'],$options['timeout']);
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name  缓存变量名
     * @return mixed
     */
    public function get($name){
        $value = $this->handler->get($this->options['prefix'].$name);
        $jsonData = json_decode($value,true);
        return ($jsonData === null)?$value:$jsonData;
    }

    /**
     * 写入缓存
     * @access public
     * @param $name     缓存变量名
     * @param $value    存储数据
     * @param $expire   有效时间（秒）
     * @return OK或null
     */
    public function set($name, $value, $expire = nulll){
        if(is_null($expire)){
            $expire = $this->options['expire'];
        }
        $name = $this->options['prefix'].name;
        $value = (is_object($value) || is_array($value) ? json_encode($value) : $value);
        if(is_int($expire)){
            $result = $this->handler->setex($name, $expire, $value);
        }else{
            $result = $this->handler->set($name, $value);
        }
        return $result;
    }

    /**
     * 删除缓存
     * @access public
     * @param $name 缓存变量名
     * @return boolean
     */
    public function rm($name){
        return $this->handler->delete($this->options['prefix'].$name);
    }

    /**
     * 递增数额
     * @param $key      缓存变量名
     * @param int $default  增加数额默认为1
     * @return 操作后的值
     */
    public function incr($key,$default = 1){
        if($default == 1){
            return $this->handler->incr($key);
        }else{
            return $this->handler->incrBy($key,$default);
        }
    }

    /**
     * 递减数额
     * @param $key      缓存变量名
     * @param int $default  递减数额默认为1
     * @return 操作后的值
     */
    public function decr($key,$default = 1){
        if($default == 1){
            return $this->decr($key);
        }else{
            return $this->decrBy($key,$default);
        }
    }

    /**
     * 队列添加 push
     * @param $key  缓存变量名
     * @param $value    缓存值
     * @return int
     */
    public function lpush($key,$value){
        return $this->handler->lPush($key,$value);
    }

    /**
     * 删除队列的第一个值
     * @param $key
     * @return 删除的值
     */
    public function lpop($key){
        return $this->handler->lPop($key);
    }

    /**
     * 获取缓存
     * @param $key  缓存变量名
     * @param $start    缓存开始数
     * @param $end      缓存结束数
     * @return array    缓存数组
     */
    public function lrange($key,$start,$end){
        return $this->lRange($key,$start,$end);
    }

    /**
     * 设置hash缓存
     * @param $name 名称
     * @param $key  key
     * @param $value 值
     * @return int
     */
    public function hset($name,$key,$value){
        if(is_array($value)){
            return $this->handler->hSet($name,$key,serialize($value));
        }
        return $this->handler->hSet($name,$key,$value);
    }

    /**
     * 获取hash缓存
     * @param $name 名称
     * @param null $key key
     * @param bool $serialize 格式化
     * @return array
     */
    public function hget($name,$key=null,$serialize=true){
        if($key){
            $row = $this->handler->hGet($name,$key);
            if($row && $serialize){
                unserialize($row);
            }
            return $row;
        }
        return $this->handler->hGetAll($name);
    }

    /**
     * 删除hash缓存
     * @param $name
     * @param null $key
     * @return int
     */
    public function hdel($name,$key=null){
        if($key){
            return $this->handler->hDel($name,$key);
        }
        return $this->handler->hDel($name);
    }

    /**
     * 开启事务
     * @return Redis
     */
    public function multi(){
        return $this->handler->multi();
    }

    /**
     * 执行事务
     */
    public function exec(){
        return $this->handler->exec();
    }

    /**
     * 取消事务
     */
    public function discard(){
        return $this->handler->discard();
    }
    /**
     * 清除缓存
     * @access public
     * @return boolean
     */
    public function clear(){
        return $this->handler->flushDB();
    }

    /**
     * 关闭连接
     */
    public function close(){
        return $this->handler->close();
    }
}