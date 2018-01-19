<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\ProductGroup;
use Denmasyarikin\Sales\Product\Requests\CreateProductGroupRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductGroupRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductGroupRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductGroupListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductGroupDetailTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductListCollectionTransformer;

class GroupController extends Controller
{
    /**
     * bank list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $banks = $this->getProductGroupList($request);

        $transform = new ProductGroupListTransformer($banks);

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * get bank list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getProductGroupList(Request $request)
    {
        $banks = ProductGroup::orderBy('created_at', 'DESC');

        if ($request->has('key')) {
            $banks->where('id', $request->key);
            $banks->orwhere('name', 'like', "%{$request->key}%");
        }

        return $banks->get();
    }

    /**
     * create product group.
     *
     * @param CreateProductGroupRequest $request
     *
     * @return json
     */
    public function createGroup(CreateProductGroupRequest $request)
    {
        $productGroup = ProductGroup::create($request->only(['name']));

        return new JsonResponse([
            'message' => 'Product group has been created',
            'data' => (new ProductGroupDetailTransformer($productGroup))->toArray()
        ], 201);
    }

    /**
     * update product group.
     *
     * @param UpdateProductGroupRequest $request
     *
     * @return json
     */
    public function updateGroup(UpdateProductGroupRequest $request)
    {
        $productGroup = $request->getProductGroup();

        $productGroup->update($request->only(['name']));

        return new JsonResponse(['message' => 'Product group has been updated']);
    }

    /**
     * update product group.
     *
     * @param DeleteProductGroupRequest $request
     *
     * @return json
     */
    public function deleteGroup(DetailProductGroupRequest $request)
    {
        $productGroup = $request->getProductGroup();

        $productGroup->delete();

        return new JsonResponse(['message' => 'Product group has been deleted']);
    }
}
