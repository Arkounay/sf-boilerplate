<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailingService
{

    public const TO = 'samuel@plugandcom.com';
    public const SUBJECT_PREFIX = '[__project_name__]';

    public function __construct(private MailerInterface $mailer){}

    public function sendContact(Contact $contact): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('__project_name__@plugandcom.com', '__project_name__'))
            ->to(self::TO)
            ->replyTo($contact->getEmail())
            ->subject(self::SUBJECT_PREFIX . " Nouvelle demande de contact de $contact")
            ->htmlTemplate('email/contact.html.twig')
            ->context(['contact' => $contact]);

        $this->mailer->send($email);
    }

}