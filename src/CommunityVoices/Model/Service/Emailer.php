<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Basically just a wrapper for PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Emailer
{
    public function __construct(PHPMailer $mail)
    {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('no-reply@environmentaldashboard.org', 'Environmental Dashboard');
        // This should be the same as the domain of your From address
        $mail->DKIM_domain = 'environmentaldashboard.org';
        // Path to your private key:
        $mail->DKIM_private = '/opendkim/mail.private';
        // Set this to your own selector
        $mail->DKIM_selector = 'mail';
        // Put your private key's passphrase in here if it has one
        $mail->DKIM_passphrase = '';
        // The identity you're signing as - usually your From address
        $mail->DKIM_identity = $mail->From;
        $this->mail = $mail;
    }

    public function to(string $recipient)
    {
        $this->mail->addAddress($recipient);
    }

    public function subject(string $subject)
    {
        $this->mail->Subject = $subject;
    }

    public function sendMessage(string $html)
    {
        $this->mail->msgHTML($html);
        if (!$this->mail->send()) {
            throw new Exception\Mailer($this->mail->ErrorInfo);
        }
    }
}
