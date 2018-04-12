<?php

namespace Denmasyarikin\Sales\Order\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Order\Requests\DetailOrderRequest;
use Denmasyarikin\Sales\Order\Requests\CreateOrderAttachmentRequest;
use Denmasyarikin\Sales\Order\Requests\UpdateOrderAttachmentRequest;
use Denmasyarikin\Sales\Order\Requests\DeleteOrderAttachmentRequest;
use Denmasyarikin\Sales\Order\Transformers\OrderAttachmentListTransformer;
use Denmasyarikin\Sales\Order\Transformers\OrderAttachmentDetailTransformer;

class AttachmentController extends Controller
{
    /**
     * get list
     *
     * @param DetailOrderRequest $request
     * @return json
     */
    public function getList(DetailOrderRequest $request)
    {
        $order = $request->getOrder();

        return new JsonResponse([
            'data' => (new OrderAttachmentListTransformer($order->attachments))->toArray()
        ]);
    }

    /**
     * create attachemnt
     *
     * @param CreateOrderAttachmentRequest $request
     * @return json
     */
    public function createAttachment(CreateOrderAttachmentRequest $request)
    {
        $order = $request->getOrder();

        $attachemnt = $order->attachments()->create(
            $request->only(['type', 'file', 'description'])
        );

        return new JsonResponse([
            'messaage' => 'Order attachemnt has been created',
            'data' => (new OrderAttachmentDetailTransformer($attachemnt))->toArray()
        ], 201);
    }

    /**
     * update attachemnt
     *
     * @param UpdateOrderAttachmentRequest $request
     * @return json
     */
    public function updateAttachment(UpdateOrderAttachmentRequest $request)
    {
        $attachemnt = $request->getOrderAttachment();

        $attachemnt->update($request->only(['type', 'file', 'description']));

        return new JsonResponse([
            'messaage' => 'Order attachemnt has been updated',
            'data' => (new OrderAttachmentDetailTransformer($attachemnt))->toArray()
        ]);
    }

    /**
     * delete attachemnt
     *
     * @param DeleteOrderAttachmentRequest $request
     * @return json
     */
    public function deleteAttachment(DeleteOrderAttachmentRequest $request)
    {
        $attachemnt = $request->getOrderAttachment();

        $attachemnt->delete();

        return new JsonResponse(['messaage' => 'Order attachemnt has been deleted']);
    }
}