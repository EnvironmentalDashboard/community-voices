<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;

/**
 * @overview Swift provider
 */

class Swift extends Provider {
    public function init()
    {
        /**
         * If no file /opendkim/mail.private exists, we will not use the mailer.
         * 
         * @configure
         */
        $mailerFactory = function () {
            $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');

            $defaultsPlugin = new Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin([
                'from' => ['no-reply@environmentaldashboard.org' => 'Environmental Dashboard'],
            ]);

            $mailer = new Swift_Mailer($transport);
            $mailer->registerPlugin($defaultsPlugin);

            return $mailer;
        };

        $this->injector->delegate('Swift_Mailer', $mailerFactory);

        $dkimLocation = '/opendkim/mail.private';

        $this->injector->define('Swift_Signers_DKIMSigner', [
            ':privateKey' => file_exists($dkimLocation) ? file_get_contents($dkimLocation) : NULL,
            ':domainName' => 'environmentaldashboard.org',
            ':selector' => 'mail',
            ':passphrase' => ''
        ]);
    }
}