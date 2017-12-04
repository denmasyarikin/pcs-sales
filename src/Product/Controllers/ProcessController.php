<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
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
        $product = $request->getProduct($request);

        $transform = new ProductProcessListTransformer($product->processes);

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create process
     *
     * @param CreateProductProcessRequest $request
     * @return json
     */
    public function createProcess(CreateProductProcessRequest $request)
    {
        $product = $request->getProduct();
        $process = $product->createProcess($request->only([
            'parent_id', 'type', 'type_as', 'reference_id',
            'name', 'specific', 'quantity', 'base_price', 'required',
            'static_price', 'static_to_order_count', 'unit_id'
        ]));

        if ($product->status === 'draft') {
            $product->update(['status' => 'active']);
        }

        $product->updateProductPrice();

        return new JsonResponse([
            'message' => 'Product Process has been created',
            'data' => (new ProductProcessDetailTransformer($process))->toArray()
        ], 201);
    }
    
    /**
     * update process
     *
     * @param CreateProductProcessRequest $request
     * @return json
     */
    public function updateProcess(UpdateProductProcessRequest $request)
    {
        $product = $request->getProduct();
        $process = $request->getProductProcess();
 
        $process->update($request->only([
            'parent_id', 'type', 'type_as', 'reference_id',
            'name', 'specific', 'quantity', 'base_price', 'required',
            'static_price', 'static_to_order_count', 'unit_id'
        ]));

        $product->updateProductPrice();

        return new JsonResponse([
            'message' => 'Product Process has been updated',
            'data' => (new ProductProcessDetailTransformer($process))->toArray()
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
        $product->updateProductPrice();

        return new JsonResponse(['message' => 'Product process has been deleted']);
    }
}
