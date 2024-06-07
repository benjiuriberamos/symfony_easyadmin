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

class BaseCompleteController extends AbstractController
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

    public function createAction(Request $request): Response
    {
        $entity = new $this->class();
        $form = $this->createForm($this->formType, $entity);
        $form->add('return', SubmitType::class, ['label' => 'Agregar']);
        $form->add('submit', SubmitType::class, ['label' => 'Agregar y continuar editando']);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                try {
                    $this->manager->save($entity);
                    $this->addFlash('success', 'Se ha guardado con éxito!');
                    if ($form->get('submit')->isClicked()) {
                        return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake());
                } catch (Exception $exception) {

                }
            }

            $this->addFlash('error', 'Se produjo un error');
        }

        return $this->render($this->package . '/' . u($this->entity)->snake() . '/form.html.twig', [
            'entity' => $entity,
            'model' => $this->entity,
            'form' => $form
        ]);
    }

    public function editAction(Request $request, $entity): Response
    {
        $editForm = $this->createForm($this->formType, $entity, [
            'action' => $this->generateUrl('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]),
        ]);
        $editForm->add('return', SubmitType::class, ['label' => 'Guardar']);
        $editForm->add('submit', SubmitType::class, ['label' => 'Guardar y continuar editando']);

        if ($request->isMethod('POST')) {
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() and $editForm->isValid()) {
                try {
                    $this->manager->update($entity);
                    $this->addFlash('success', 'Se ha actualizado con éxito!');
                    if ($editForm->get('submit')->isClicked()) {
                        return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]);
                    }
                    return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake());
                } catch (Exception $exception) {

                }
            }

            $this->addFlash('error', 'Se produjo un error');
        }

        $deleteForm = $this->getDeleteForm($entity);

        return $this->renderForm($this->package . '/' . u($this->entity)->snake() . '/form.html.twig', [
            'entity' => $entity,
            'model' => $this->entity,
            'form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    public function deleteAction(Request $request, $entity): Response
    {
        $form = $this->getDeleteForm($entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            try {
                $this->manager->delete($entity);
                $this->addFlash('success', 'Se ha eliminado con éxito!');
                return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake());
            } catch (Exception $exception) {

            }
        }

        $this->addFlash('error', 'Se produjo un error');
        return $this->redirectToRoute('sd_admin_' . u($this->entity)->snake() . '_edit', ['id' => $entity->getId()]);
    }

    protected function getDeleteForm($entity): FormInterface
    {
        // TODO: Fix method DELETE
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sd_admin_' . u($this->entity)->snake() . '_delete', ['id' => $entity->getId()]))
            ->add('submit', SubmitType::class, ['label' => 'Eliminar'])
            ->setMethod(Request::METHOD_POST)
            ->getForm();
    }
}
