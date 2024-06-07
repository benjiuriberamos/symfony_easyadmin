<?php

namespace App\Controller\Core;

use App\Manager\BaseManager;
use Exception;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BaseSperantController extends AbstractController
{
    protected string $class;
    protected string $entity;
    protected string $model;
    protected string $formType;
    protected string $package;
    protected DataTable $dataTable;
    protected DataTableFactory $dataTableFactory;
    protected BaseManager $manager;

    public function indexAction(Request $request, string $orderBy = 'title', string $mode = 'ASC'): Response
    {
        $order = ($orderBy != '' and $mode != '') ? [$orderBy => $mode] : [];
        $entities = $this->manager->findBy([], $order);

        return $this->render($this->package . '/' . u($this->entity)->snake() . '/index.html.twig', [
            'datatable' => $this->dataTable,
            'model' => $this->entity,
            'entities' => $entities,
        ]);
    }

    public function editAction(Request $request, $entity): Response
    {
        $form = $this->createForm($this->formType, $entity, [
            'action' => $this->generateUrl('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]),
        ]);
        $form->add('return', SubmitType::class, ['label' => 'Guardar']);
        $form->add('submit', SubmitType::class, ['label' => 'Guardar y continuar editando']);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                try {
                    $this->manager->update($entity);
                    $this->addFlash('success', 'Se ha actualizado con éxito!');
                    if ($form->get('submit')->isClicked()) {
                        return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake());
                } catch (Exception $exception) {

                }
            }

            $this->addFlash('error', 'Se produjo un error');
        }

        return $this->renderForm($this->package . '/' . u($this->entity)->snake() . '/form.html.twig', [
            'entity' => $entity,
            'model' => $this->entity,
            'form' => $form,
        ]);
    }
}
