<?php


namespace App\Event\Admin;

use App\Entity\Enum\Labellable;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class EnumSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            'qag.events.form.field' => 'formEvent',
        ];
    }

    public function formEvent(GenericEvent $event): void
    {
        $formBuilder = $event->getSubject();

        /** @var Field $field */
        $field = $event->getArgument('field');

        if (self::IsEnum($field) && is_a($field->getAssociationMapping(), Labellable::class, true)) {
            $formBuilder->add($field->getIndex(), $field->guessFormType(), array_merge($field->guessFormOptions(), [
                'choice_label' => fn(Labellable $e) => $e->getLabel(),
            ]));
            $event->stopPropagation();
        }
    }


    private static function IsEnum(Field $field): bool
    {
        return $field->getType() === 'enum';
    }


}