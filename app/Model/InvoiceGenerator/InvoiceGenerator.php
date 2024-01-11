<?php

namespace App\Model\InvoiceGenerator;

use App\Model\Orm\Orders\Order;
use Latte\Engine;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

/**
 * Class InvoiceGenerator
 * @package App\Model\InvoiceGenerator
 * @author Jiří Andrlík
 */
class InvoiceGenerator
{
    public function __construct(
        private readonly Engine $latte
    )
    {}

    public function generatePDFInvoiceToString(Order $order): string
    {
        $params = [
            'order' => $order
        ];

        $pdf = new Mpdf(['mode' => 'utf-8', 'format'=>'A4', 'tempDir'=>__DIR__.'/../../../temp/mpdf']);
        $pdf->title = 'Objednávka č. '.$order->id;
        $pdf->writeHTML($this->latte->renderToString(__DIR__ . '/templates/invoice.latte', $params));
        return $pdf->Output('objednavka.pdf', Destination::STRING_RETURN);
    }

    public function generatePDFInvoiceInline(Order $order): void
    {
        $params = [
            'order' => $order
        ];

        $pdf = new Mpdf(['mode' => 'utf-8', 'format'=>'A4', 'tempDir'=>__DIR__.'/../../../temp/mpdf']);
        $pdf->title = 'Objednávka č. '.$order->id;
        $pdf->writeHTML($this->latte->renderToString(__DIR__ . '/templates/invoice.latte', $params));
        $pdf->Output('objednavka.pdf', Destination::INLINE);
    }



}