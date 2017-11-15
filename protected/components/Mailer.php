<?php
class Mailer
{

    public static $host = 'mail.sisenapp.com';
    public static $username = 'info@sisenapp.com';
    public static $password = 'mwaJF!4J';
    public static $port = '587';
    public static $secure = 'tls';

    /**
     * @param $to
     * @param $subject
     * @param $message
     * @param null $attachment
     * @return bool
     * @throws CException
     * @throws phpmailerException
     */
    public static function mail($to, $subject, $message, $attachment = NULL)
    {
        $mail_theme = Yii::app()->params['mailTheme'];
        $message = str_replace('{MessageBody}', $message, $mail_theme);
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = self::$host;
        $mail->Username = self::$username;
        $mail->Password = self::$password;
        $mail->SMTPSecure = self::$secure;
        $mail->Port = (int)self::$port;
        $mail->SetFrom(self::$username, Yii::app()->name);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        if(is_array($to))
            foreach($to as $address)
                $mail->AddAddress($address);
        else
            $mail->AddAddress($to);
        if($attachment)
            $mail->AddAttachment($attachment);
        return @$mail->Send();
    }
}