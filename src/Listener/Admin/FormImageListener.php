<?php


namespace App\Listener\Admin;


use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Arkounay\Bundle\UxMediaBundle\Form\UxMediaType;
use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;

class FormImageListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
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

        if (self::IsImage($field)) {
            /** @var FormBuilderInterface $formBuilder */
            $formBuilder->add($field->getIndex(), UxMediaType::class, array_merge($field->guessFormOptions(), [
                'conf' => 'default'
            ]));
            $event->stopPropagation();
        }
    }


    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsImage($field)) {
            $field->setTwig('@ArkounayQuickAdminGenerator/crud/fields/_image.html.twig');
            $field->setSortable(false);
        }
    }

    private static function IsImage(Field $field): bool
    {
        return stripos($field->getIndex(), 'image') !== false && $field->getType() === 'string';
    }


}