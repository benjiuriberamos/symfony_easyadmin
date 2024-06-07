<?php

namespace App\Traits;

use App\Controller\Core\BaseFormController;
use DateInterval;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ExportTrait
{
    public function exportExcel(array $data, string $name = 'leads')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($name);

        foreach ($data as $column => $item) {
            if (is_array($item)) {
                foreach ($item as $row => $value) {
                    $sheet->setCellValue($column . $row, $value);
                }
            }
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        try {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $name . '.xlsx"');
            $response->headers->set('Cache-Control','max-age=0');
        } catch (Exception $e) {
            $response = new Response(null, 500);
        }

        return $response;
    }

    public function getDates($type = null, $start_date = null, $end_date = null): array
    {
        if (is_null($type) and is_null($start_date) and is_null($end_date)) {
            return [
                'start' => null,
                'end' => null
            ];
        }

        if ($type === BaseFormController::LAST_CUSTOM) {
            $start = DateTime::createFromFormat('d/m/Y', $start_date);
            if ($start) {
                $start->setTime('0', '0', '0');
            }
            $end = DateTime::createFromFormat('d/m/Y', $end_date);
            if ($end) {
                $end->setTime('23', '59', '59');
            }

            if ($end < $start) {
                $end = $this->getEndDate();
            }

            return [
                'start' => $start ?: null,
                'end' => $end ?: null
            ];
        }

        $end = $this->getEndDate();
        $start = $this->getStartDate($type);

        return [
            'start' => $start,
            'end' => $end
        ];
    }

    public function getStartDate($type): DateTime
    {
        $end = new DateTime();
        $interval = $this->getInterval($type);
        $start = $end->sub($interval);
        $start->setTime('0', '0', '0');
        return $start;
    }

    public function getEndDate(): DateTime
    {
        $end = new DateTime();
        $end->setTime('23', '59', '59');
        return $end;
    }

    public function getInterval($type): DateInterval
    {
        $intervals = [
            BaseFormController::LAST_DAY => new DateInterval('P0D'),
            BaseFormController::LAST_15_DAYS => new DateInterval('P15D'),
            BaseFormController::LAST_MONTH => new DateInterval('P1M'),
            BaseFormController::LAST_6_MONTHS => new DateInterval('P6M'),
            BaseFormController::LAST_YEAR => new DateInterval('P1Y'),
        ];
        return array_key_exists($type, $intervals) ? $intervals[$type] : $intervals[BaseFormController::LAST_DAY];
    }
}
