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
        try {
            $swiftMessage = $this->convertToSwift($email);

            $this->swiftMailer->send($swiftMessage);
        } catch (\Swift_SwiftException $e) {
            // This is where we will redirect to an error page
            // so that we successfully handle email failing locally.
            throw $e;
        }
    }

    private function convertToSwift(Entity\Email $email)
    {
        $message = new Swift_Message;
        $message->attachSigner($this->swiftDkimSigner);

        $message->setTo($email->getTo());
        $message->setSubject($email->getSubject());
        $message->setBody($email->getBody(), 'text/html');

        return $message;
    }
}
