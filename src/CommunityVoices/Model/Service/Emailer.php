<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Basically just a wrapper for PHPMailer
 */

use PHPMailer\PHPMailer\Exception;

use Swift_Mailer;
use Swift_Message;

class Emailer
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

    public function send(Swift_Message $swiftMessage)
    {
        $swiftMessage->attachSigner($this->swiftDkimSigner);
    }
}
