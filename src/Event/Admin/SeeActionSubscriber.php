<?php


namespace App\Event\Admin;


use App\Controller\Admin\Previewable;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Action;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Actions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class SeeActionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            'qag.events.actions' => 'actionEvent',
        ];
    }

    public function actionEvent(GenericEvent $event): void
    {
        /** @var Actions $actions */
        $crud = $event->getArgument('crud');
        if ($crud instanceof Previewable) {
            $actions = $event->getSubject();
            $entity = $event->getArgument('entity');
            $action = (new Action('see'))->setLabel('Voir')->setAttributes(['target' => '_blank']);
            $action->setCustomHref($crud->generatePreviewUrl($entity));

            $actions->add($action);
            if (isset($actions['delete'])) {
                $actions->moveToLastPosition('delete');
            }
        }
    }



}