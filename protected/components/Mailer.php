<?php
class Mailer
{

    /**
     * Send mail
     *
     * @param $to string
     * @param $subject string
     * @param $message string
     * @param $from string
     * @param $SMTP array
     * @param $attachments array
     *
     * @return boolean
     */
    public static function mail($to, $subject, $message, $from, $SMTP = array(), $attachments = array())
    {
        $mailTheme = Yii::app()->params['mailTheme'];
        $mailTheme = str_replace('{CurrentYear}', JalaliDate::date('Y'), $mailTheme);
        $message = str_replace('{MessageBody}', $message, $mailTheme);

        /*$mail=Yii::app()->swiftMailer;
        $mailHost = 'mail.hyperapps.ir';
        $mailPort = 465;
        $Transport = $mail->smtpTransport($mailHost, $mailPort)->setUsername('no-reply@hyperapps.ir')->setPassword('hyperapps.ir');
        $Mailer = $mail->mailer($Transport);
        $Message = $mail
            ->newMessage($subject)
            ->setFrom(array($from => Yii::app()->name))
            ->setTo(array($to => $to))
            ->addPart($message, 'text/html')
            ->setBody('plain text');

        return $Mailer->send($Message);*/


        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->CharSet = 'UTF-8';
        $mail->SetFrom($from, Yii::app()->name);
        if ($SMTP && isset($SMTP['Host']) && isset($SMTP['Secure']) && isset($SMTP['Username']) && isset($SMTP['Password']) && isset($SMTP['Port'])) {
            //$mail->SMTPDebug = 3;
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $SMTP['Host'];
            $mail->SMTPSecure = $SMTP['Secure'];
            $mail->Username = $SMTP['Username'];
            $mail->Password = $SMTP['Password'];
            $mail->Port = $SMTP['Port'];
        }
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->AddAddress($to);
        if ($attachments)
            foreach ($attachments as $attachment)
                $mail->AddAttachment($attachment);
        return $mail->Send();
    }
}