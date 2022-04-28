<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{

    public function show(Request $request, FlattenException $exception): Response
    {
        $twig = '@Twig/Exception/error.html.twig';

        if (str_starts_with($request->getRequestUri(), '/admin') && $this->isGranted('ROLE_ADMIN')) {
            $twig = '@ArkounayQuickAdminGenerator/error.html.twig';
        }

        return $this->render($twig, [
            'status_code' => $exception->getStatusCode(),
            'status_text' => $exception->getStatusText()
        ]);
    }

}
