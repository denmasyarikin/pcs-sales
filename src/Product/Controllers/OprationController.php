<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductProcessRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductOprationRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductOprationRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductOprationRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductOprationRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductOprationListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductOprationDetailTransformer;

class OprationController extends Controller
{
    /**
     * get list.
     *
     * @param DetailProductProcessRequest $request
     *
     * @return json
     */
    public function getList(DetailProductProcessRequest $request)
    {
        $process = $request->getProductProcess();

        $transform = new ProductOprationListTransformer($process->productOprations);

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * get detail.
     *
     * @param DetailProductOprationRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductOprationRequest $request)
    {
        $transform = new ProductOprationDetailTransformer($request->getProductOpration());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create opration.
     *
     * @param CreateProductOprationRequest $request
     *
     * @return json
     */
    public function createOpration(CreateProductOprationRequest $request)
    {
        $process = $request->getProductProcess();
        $opration = $process->productOprations()->create(
            $request->only(['product_configuration_id', 'condition', 'condition_value', 'opration', 'opration_value'])
        );

        return new JsonResponse([
            'message' => 'Product Opration has been created',
            'data' => (new ProductOprationDetailTransformer($opration))->toArray(),
        ], 201);
    }

    /**
     * update opration.
     *
     * @param CreateProductOprationRequest $request
     *
     * @return json
     */
    public function updateOpration(UpdateProductOprationRequest $request)
    {
        $opration = $request->getProductOpration();

        $opration->update(
            $request->only(['product_configuration_id', 'condition', 'condition_value', 'opration', 'opration_value'])
        );

        return new JsonResponse([
            'message' => 'Product Opration has been updated',
            'data' => (new ProductOprationDetailTransformer($opration))->toArray(),
        ]);
    }

    /**
     * delete opration.
     *
     * @param DeleteProductOprationRequest $request
     *
     * @return json
     */
    public function deleteOpration(DeleteProductOprationRequest $request)
    {
        $opration = $request->getProductOpration();
        $opration->delete();

        return new JsonResponse(['message' => 'Product opration has been deleted']);
    }
}
