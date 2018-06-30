<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductConfigurationRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductConfigurationRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductConfigurationRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductConfigurationListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductConfigurationDetailTransformer;

class ConfigurationController extends Controller
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

        $transform = new ProductConfigurationListTransformer($product->getConfigurations());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create configuration.
     *
     * @param CreateProductConfigurationRequest $request
     *
     * @return json
     */
    public function createConfiguration(CreateProductConfigurationRequest $request)
    {
        $product = $request->getProduct();
        $configuration = $product->configurations()->create(
            $request->only(['name', 'type', 'configuration', 'required'])
        );

        return new JsonResponse([
            'message' => 'Product Configuration has been created',
            'data' => (new ProductConfigurationDetailTransformer($configuration))->toArray(),
        ], 201);
    }

    /**
     * update configuration.
     *
     * @param CreateProductConfigurationRequest $request
     *
     * @return json
     */
    public function updateConfiguration(UpdateProductConfigurationRequest $request)
    {
        $product = $request->getProduct();
        $configuration = $request->getProductConfiguration();

        $configuration->update(
            $request->only(['name', 'type', 'configuration', 'required'])
        );

        return new JsonResponse([
            'message' => 'Product Configuration has been updated',
            'data' => (new ProductConfigurationDetailTransformer($configuration))->toArray(),
        ]);
    }

    /**
     * delete configuration.
     *
     * @param DeleteProductConfigurationRequest $request
     *
     * @return json
     */
    public function deleteConfiguration(DeleteProductConfigurationRequest $request)
    {
        $product = $request->getProduct();
        $configuration = $request->getProductConfiguration();

        $configuration->delete();

        if (0 === $product->configurations()->count()) {
            $product->update(['status' => 'draft']);
        }

        return new JsonResponse(['message' => 'Product configuration has been deleted']);
    }
}
