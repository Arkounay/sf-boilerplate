<?php


namespace App\Listener\Admin;


use App\Form\Admin\TinymceType;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class FormTinymceListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            'qag.events.form.field' => 'formEvent',
            'qag.events.field_generation' => 'fieldGenerationEvent'
        ];
    }

    public function formEvent(GenericEvent $event): void
    {
        $formBuilder = $event->getSubject();

        /** @var Field $field */
        $field = $event->getArgument('field');

        if (self::IsTiny($field)) {
            $formBuilder->add($field->getIndex(), $field->getFormType(), array_merge($field->guessFormOptions(), [
                'attr' => ['data-controller' => 'tinymce', 'data-tinymce-trigger-change-value' => false]
            ]));
            $event->stopPropagation();
        }
    }


    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsTiny($field)) {
            $field->setSortable(false);
            $field->setDisplayedInList(false);
        }
    }

    private static function IsTiny(Field $field): bool
    {
        return $field->getFormType() === TinymceType::class;
    }


}