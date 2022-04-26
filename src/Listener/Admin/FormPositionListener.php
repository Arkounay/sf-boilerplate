<?php


namespace App\Listener\Admin;


use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class FormPositionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            'qag.events.field_generation' => 'fieldGenerationEvent'
        ];
    }

    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsPosition($field)) {
            $field->setTwig('@ArkounayQuickAdminGenerator/crud/fields/_position.html.twig');
            $field->setSortable(true);
        }
    }

    private static function IsPosition(Field $field): bool
    {
        return $field->getIndex() === 'position' && $field->getType() === 'integer' ;
    }


}