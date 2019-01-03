<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Basically just a wrapper for PHPMailer
 */

use PHPMailer\PHPMailer\Exception;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Contract;

use Swift_Mailer;
use Swift_SignedMessage;
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
        try {
            $swiftMessage = $this->convertToSwift($email);

            $this->swiftMailer->send($swiftMessage);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function convertToSwift(Entity\Email $email)
    {
        $message = Swift_SignedMessage::newInstance();
        $message->attachSigner($this->swiftDkimSigner);

        $message->setFrom($email->getFrom());
        $message->setTo($email->getTo());
        $message->setSubject($email->getSubject());
        $message->setBody($email->getBody());

        return $message;
    }
}
