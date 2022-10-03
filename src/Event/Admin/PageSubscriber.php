<?php


namespace App\Event\Admin;

use App\Form\Admin\TinymceType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class PageSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'pagebundle.add_field' => 'addField',
        ];
    }

    public function addField(GenericEvent $event): void
    {
        $field = $event->getSubject();
        if ($field['type'] === TinymceType::class || $field['type'] === 'tinymce') {
            $event->setArgument('formType', TinymceType::class);
            $event->setArgument('formOptions', [
                'attr' => ['data-controller' => 'tinymce', 'data-tinymce-trigger-change-value' => true]
            ]);
        }
    }

}