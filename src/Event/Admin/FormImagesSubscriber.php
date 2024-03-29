<?php


namespace App\Event\Admin;

use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Arkounay\Bundle\UxMediaBundle\Form\UxMediaCollectionType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;

class FormImagesSubscriber implements EventSubscriberInterface
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

        if (self::IsImages($field)) {
            /** @var FormBuilderInterface $formBuilder */
            $formBuilder->add($field->getIndex(), UxMediaCollectionType::class, array_merge($field->guessFormOptions(), [
                'required' => false,
                'conf' => 'default'
            ]));
            $event->stopPropagation();
        }
    }


    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsImages($field)) {
            $field->setTwig('@ArkounayQuickAdminGenerator/crud/fields/_images.html.twig');
            $field->setSortable(false);
        }
    }

    private static function IsImages(Field $field): bool
    {
        return (stripos($field->getIndex(), 'images') !== false || stripos($field->getIndex(), 'gallery') !== false) && $field->getType() === 'simple_array';
    }


}