<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Order;
use Barryvdh\DomPDF\Facade as PDF;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvoiceController extends Controller
{
    /**
     * show invoice.
     *
     * @param DetailOrderRequest $request
     *
     * @return json
     */
    public function showInvoice(DetailOrderRequest $request, $id, $type)
    {
        if (!$this->isValidType($type)) {
            throw new BadRequestHttpException('not supported type');
        }

        $order = $this->getOrder($request);

        if ($type === 'html') {
            return View::make('sales.order::invoice')->withType('html')->withOrder($order);
        }

        return $this->showInvoicePDF($order);
    }

    /**
     * show as pdf
     *
     * @param Order $order
     * @return pdf
     */
    protected function showInvoicePDF(Order $order)
    {
        $pdfOptions = [
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ];

        $pdf = PDF::setOptions($pdfOptions)
            ->loadView('sales.order::invoice', ['order' => $order, 'type' => 'pdf'])
            ->setPaper('a4');
        
        return (new Response($pdf->output(), 200))
                  ->header('Content-Type', 'application/pdf')
                  ->header('Content-Disposition', 'inline; filename="' . $order->code . '.pdf"');
    }

    /**
     * show invoice.
     *
     * @param DetailOrderRequest $request
     *
     * @return json
     */
    public function showReceipt(DetailOrderRequest $request, $id, $type)
    {
        if (!$this->isValidType($type)) {
            throw new BadRequestHttpException('not supported type');
        }

        $order = $this->getOrder($request);

        if ($type === 'html') {
            return View::make('sales.order::receipt')->withType('html')->withOrder($order);
        }

        return $this->showAsPDF($order);
    }

    /**
     * is valid type
     *
     * @param string $type
     * @return bool
     */
    protected function isValidType($type)
    {
        return  in_array($type, ['html', 'pdf']);
    }

    /**
     * get order
     *
     * @param DetailOrderRequest $request
     * @return Order
     */
    protected function getOrder(DetailOrderRequest $request)
    {
        return $order = $request->getOrder()->load([
            'chanel', 'customer', 'items',
            'adjustments', 'attachments',
            'cancelation', 'payments'
        ]);
    }
}
