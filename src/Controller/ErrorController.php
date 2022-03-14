<?php

namespace App\Controller;

use App\Entity\Agency;
use App\Entity\Contact;
use App\Entity\Enum\ContactEnum;
use App\Entity\News;
use App\Form\ContactType;
use App\Repository\AgencyRepository;
use App\Repository\NewsRepository;
use App\Repository\TestimonyRepository;
use App\Service\MailingService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ErrorController extends AbstractController
{

    public function show(Request $request, FlattenException $exception): Response
    {
        $twig = '@Twig/Exception/error.html.twig';

        if (str_starts_with($request->getRequestUri(), '/admin')) {
            $twig = '@ArkounayQuickAdminGenerator/error.html.twig';
        }

        return $this->render($twig, [
            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText()
        ]);
    }

}
