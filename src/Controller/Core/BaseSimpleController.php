<?php

namespace App\Controller\Core;

use App\Manager\BaseManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BaseSimpleController extends AbstractController
{
    protected string $class;
    protected string $entity;
    protected string $formType;
    protected string $package;
    protected BaseManager $manager;

    public function init(Request $request): Response
    {
        $entity = $this->manager->find(1);
        $entity = is_null($entity) ? new $this->class() : $entity;
        $form = $this->createForm($this->formType, $entity);
        $form->add('submit', SubmitType::class, ['label' => 'Guardar']);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                try {
                    $this->manager->save($entity);
                    $this->addFlash('success', 'Se ha guardado con éxito!');
                    return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake());
                } catch (Exception $exception) {

                }
            }

            $this->addFlash('error', 'Se produjo un error');
        }

        return $this->renderForm($this->package . '/' . u($this->entity)->snake() . '/form.html.twig', [
            'entity' => $entity,
            'form' => $form,
        ]);
    }
}
