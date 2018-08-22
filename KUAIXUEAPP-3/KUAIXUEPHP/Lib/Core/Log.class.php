<?php
class Log{
  //参数为 错误信息
  //错误等级
  //$type是用在error_log函数里的参数，当为3的时候表示以文件的形似来保存
  //错误日志存放的路径
  public static function write($msg,$level='ERROR',$type=3,$dest=NULL){
    if(!C('SAVE_LOG')) return;//如果配置项中不开启SAVE_LOG，我就直接退出
    if(is_null($dest)){
      //以一天为日志文件的最小单位，同一天的错误日志放在同一个日志文件里
      $dest=LOG_PATH.'/'.date('Y_m_d').'.log';
    }
    if(is_dir(LOG_PATH)){
      //error_log({参数1}{参数2}{参数3})函数用于往文件里写错误日志
      //参数1：要写入日志文件的信息
      //参数2：错误等级，默认为3，表示要把这个错误记录到日志文件里。
      //参数3：日志文件的目录位置
      error_log("[TIME]:".date('Y-m-d H:i:s')."{$level} : {$msg}\r\n",$type,$dest);
    }
  }
}
?>
