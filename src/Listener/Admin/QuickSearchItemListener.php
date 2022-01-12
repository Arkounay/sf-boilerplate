<?php


namespace App\Listener\Admin;


use App\Controller\Admin\Crud;
use App\Entity\Travel;
use Arkounay\Bundle\QuickAdminGeneratorBundle\Model\Field;
use Artgris\Bundle\MediaBundle\Form\Type\MediaType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class QuickSearchItemListener implements EventSubscriberInterface
{

    public function __construct(private RouterInterface $router){}

    public static function getSubscribedEvents()
    {
        return [
            'qag.events.quick_search_item' => 'item',
            'qag.events.quick_search_crud' => 'crud',
        ];
    }

    public function item(GenericEvent $event): void
    {
        $entity = $event->getSubject();

        if ($entity instanceof Travel) {
            $event->setArgument('item_override', [
                'entity' => $entity,
                'url' => $this->router->generate('qag.travels/travel_edit', ['id' => $entity->getId(), 'category' => $entity->getCategory()->getId()])
            ]);
        }
    }

    public function crud(GenericEvent $event): void
    {
        /** @var $crud Crud */
        $crud = $event->getSubject();

        if ($crud->getEntity() === Travel::class) {
            $event->setArgument('search_result_override',[
                'entity' => $crud->getPluralName(),
                'crud_url' => false,
                'items' => $event->getArgument('search_result'),
                'count' => $event->getArgument('count'),
            ]);
        }
    }


}