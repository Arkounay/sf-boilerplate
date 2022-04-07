<?php


namespace App\Listener\Admin;


use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;

class FormPriceListener implements EventSubscriberInterface
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

        if (self::IsPrice($field)) {
            /** @var FormBuilderInterface $formBuilder */
            $formBuilder->add($field->getIndex(), MoneyType::class, [
                'required' => $field->isRequired(),
                'scale' => 2
            ]);
            $event->stopPropagation();
        }
    }


    public function fieldGenerationEvent(GenericEvent $event): void
    {
        /** @var Field $field */
        $field = $event->getSubject();

        if (self::IsPrice($field)) {
            $field->setTwig('@ArkounayQuickAdminGenerator/crud/fields/_price.html.twig');
        }
    }

    private static function IsPrice(Field $field): bool
    {
        return stripos($field->getIndex(), 'price') !== false && $field->getType() === 'decimal';
    }


}