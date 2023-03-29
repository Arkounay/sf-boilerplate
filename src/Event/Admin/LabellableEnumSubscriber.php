<?php


namespace App\Event\Admin;

use App\Entity\Enum\Labellable;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class LabellableEnumSubscriber implements EventSubscriberInterface
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

        if (self::IsLabellableEnum($field) ) {
            $formBuilder->add($field->getIndex(), $field->guessFormType(), array_merge($field->guessFormOptions(), [
                'choice_label' => fn(Labellable $e) => $e->getLabel(),
            ]));
            $event->stopPropagation();
        }
    }

    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsLabellableEnum($field)) {
            $field->setTwig('@ArkounayQuickAdminGenerator/crud/fields/_labellable_enum.html.twig');
            $field->setSortable(false);
        }
    }


    private static function IsLabellableEnum(Field $field): bool
    {
        return $field->getType() === 'enum' && is_a($field->getAssociationMapping(), Labellable::class, true);
    }


}