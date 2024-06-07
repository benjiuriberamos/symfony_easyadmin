<?php

namespace App\Controller\Core;

use App\Manager\BaseFormManager;
use App\Traits\ExportTrait;
use Exception;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class BaseFormController extends AbstractController
{
    use ExportTrait;

    const LAST_DAY = 'last_day';
    const LAST_15_DAYS = 'last_15_days';
    const LAST_MONTH = 'last_mont';
    const LAST_6_MONTHS = 'last_6_months';
    const LAST_YEAR = 'last_year';
    const LAST_CUSTOM = 'last_custom';

    const DATE_FORMAT = 'Y-m-d H:i:s';

    protected string $class;
    protected string $entity;
    protected string $model;
    protected string $package;
    protected DataTable $dataTable;
    protected DataTableFactory $dataTableFactory;
    protected BaseFormManager $manager;

    public function indexAction(Request $request): Response
    {
        $entities = $this->manager->findBy([], ['created_at' => 'DESC']);

        return $this->render($this->package . '/' . u($this->entity)->snake() . '/index.html.twig', [
            'datatable' => $this->dataTable,
            'model' => $this->entity,
            'entities' => $entities,
        ]);
    }

    public function showAction(Request $request, $entity): Response
    {
        return $this->render($this->package . '/' . u($this->entity)->snake() . '/show.html.twig', [
            'entity' => $entity,
            'model' => $this->entity,
        ]);
    }

    public function deleteAction(Request $request, $entity): RedirectResponse
    {
        try {
            $this->manager->delete($entity);
            $this->addFlash('success', 'Se ha eliminado con éxito!');
        } catch (Exception $exception) {
            $this->addFlash('error', 'Se produjo un error');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    protected function getEntities(Request $request)
    {
        $dates = $this->getDates($request->request->get('export_type'), $request->request->get('start_date'), $request->request->get('end_date'));
        return $this->manager->inRange($dates['start'], $dates['end']);
    }
}
