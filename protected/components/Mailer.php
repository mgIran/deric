<?php
class Mailer
{

    public static $host = 'mail.avayeshahir.com';
    public static $username = 'noreply@avayeshahir.com';
    public static $password = '!@avayeshahir1395';
    public static $port = '587';
    public static $secure = '';

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
        $mail->SMTPSecure = self::$secure;
        $mail->Username = self::$username;
        $mail->Password = self::$password;
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