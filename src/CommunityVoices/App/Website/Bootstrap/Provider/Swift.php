<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use Swift_SendmailTransport;
use Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin;
use Swift_Mailer;

use CommunityVoices\App\Website\Component\Provider;

/**
 * @overview Swift provider
 */

class Swift extends Provider
{
    public function init()
    {
        /**
         * If no file /opendkim/mail.private exists, we will not use the mailer.
         *
         * @config
         */
        $mailerFactory = function () {
            $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');

            $defaultsPlugin = new SwiftMailerDefaultsPlugin([
                'from' => ['no-reply@environmentaldashboard.org' => 'Environmental Dashboard'],
            ]);

            $mailer = new Swift_Mailer($transport);
            $mailer->registerPlugin($defaultsPlugin);

            return $mailer;
        };

        $this->injector->delegate('Swift_Mailer', $mailerFactory);

        $dkimLocation = '/opendkim/mail.private';

        $this->injector->define('Swift_Signers_DKIMSigner', [
            ':privateKey' => file_exists($dkimLocation) ? file_get_contents($dkimLocation) : null,
            ':domainName' => 'environmentaldashboard.org',
            ':selector' => 'mail',
            ':passphrase' => ''
        ]);
    }
}
