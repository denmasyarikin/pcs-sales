<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductProcessRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductProcessRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductProcessRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductProcessRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductProcessListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductProcessDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProcessController extends Controller
{
    /**
     * get list.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getList(DetailProductRequest $request)
    {
        $product = $request->getProduct();

        $transform = new ProductProcessListTransformer($product->getProcesses());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * get detail.
     *
     * @param DetailProductProcessRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductProcessRequest $request)
    {
        $transform = new ProductProcessDetailTransformer($request->getProductProcess());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create process.
     *
     * @param CreateProductProcessRequest $request
     *
     * @return json
     */
    public function CreateProductProcessRequest(CreateProductProcessRequest $request)
    {
        $product = $request->getProduct();

        if ($request->input('parent_id') !== null) {
            if (!$product->processes()->whereNull('parent_id')->whereId($request->parent_id)->exists()) {
                throw new BadRequestHttpException("Parent ID is not belong to this product  or is children");
            }
        }

        $process = $product->createProcess($request->only([
            'parent_id', 'type', 'type_as', 'reference_id',
            'name', 'specific', 'quantity', 'base_price', 'required',
            'static_price', 'static_to_order_count', 'unit_id',
        ]));

        if ('draft' === $product->status) {
            $product->update(['status' => 'active']);
        }

        return new JsonResponse([
            'message' => 'Product Process has been created',
            'data' => (new ProductProcessDetailTransformer($process))->toArray(),
        ], 201);
    }

    /**
     * update process.
     *
     * @param CreateProductProcessRequest $request
     *
     * @return json
     */
    public function updateProcess(UpdateProductProcessRequest $request)
    {
        $product = $request->getProduct();
        $process = $request->getProductProcess();

        $process->update($request->only([
            'parent_id', 'type', 'type_as', 'reference_id',
            'name', 'specific', 'quantity', 'base_price', 'required',
            'static_price', 'static_to_order_count', 'unit_id',
        ]));

        return new JsonResponse([
            'message' => 'Product Process has been updated',
            'data' => (new ProductProcessDetailTransformer($process))->toArray(),
        ]);
    }

    /**
     * delete process.
     *
     * @param DeleteProductProcessRequest $request
     *
     * @return json
     */
    public function deleteProcess(DeleteProductProcessRequest $request)
    {
        $product = $request->getProduct();
        $process = $request->getProductProcess();

        $process->delete();

        if ($product->processes()->count() === 0) {
            $product->update(['status' => 'draft']);
        }

        return new JsonResponse(['message' => 'Product process has been deleted']);
    }
}
