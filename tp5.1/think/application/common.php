<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//存放公共函数

//发送邮件函数
//$to 发给谁  $title 邮件标题  $content 邮件内容
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function mailto($to,$title,$content){
    //实例化一个邮件对象
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        //关闭调试模式
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output
        $mail->CharSet='utf-8';
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers指定主要和备用SMTP服务器
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'leruge@163.com';                 // SMTP username
        $mail->Password = 'Ai157511';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('leruge@163.com', '梦中程序员');
        $mail->addAddress($to);     // Add a recipient添加收件人
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

//        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $title;//发送的主题
        $mail->Body    =$content;//邮件内容
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

         return $mail->send();//返回一下发送的结果
//        echo 'Message has been sent';
    } catch (Exception $e) {
//        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        //如果返回异常，用tp框架自带的异常处理返回$mail的异常信息
        Exception($mail->ErrorInfo,'1001');
    }
}
