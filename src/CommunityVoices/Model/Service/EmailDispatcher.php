<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Basically just a wrapper for PHPMailer
 */

use PHPMailer\PHPMailer\Exception;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Contract;

use Swift_Mailer;
use Swift_Message;
use Swift_Signers_DKIMSigner;

class EmailDispatcher
{
    private $swiftMailer;
    private $swiftDkimSigner;

    public function __construct(
        Swift_Mailer $swiftMailer,
        Swift_Signers_DKIMSigner $swiftDkimSigner
    ) {
        $this->swiftMailer = $swiftMailer;
        $this->swiftDkimSigner = $swiftDkimSigner;
    }

    public function send(Entity\Email $email)
    {
        $email->setFrom(['no-reply@environmentaldashboard.org' => 'Environemntal Dashboard']);

        try {
            $swiftMessage = $this->convertToSwift($email);

            $swiftMessage->attachSigner($this->swiftDkimSigner);
            $this->swiftMailer->send($swiftMessage);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function convertToSwift(Entity\Email $email)
    {
        $message = new Swift_Message();

        $message->setFrom($email->getFrom());
        $message->setTo($email->getTo());
        $message->setSubject($email->getSubject());
        $message->setBody($email->getBody());

        return $message;
    }
}
