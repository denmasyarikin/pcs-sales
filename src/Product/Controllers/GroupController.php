<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\ProductGroup;
use Denmasyarikin\Sales\Product\Requests\CreateProductGroupRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductGroupRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductGroupRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductGroupListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductListCollectionTransformer;

class GroupController extends Controller
{
    /**
     * get list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $groups = $this->getProductGroupsList($request);
        $products = $this->getProductList($request);

        return $this->sendReturn($groups, $products);
    }

    /**
     * get children list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getChildrenList(DetailProductGroupRequest $request)
    {
        $productGroup = $request->getProductGroup();

        $groups = $this->getProductGroupsList($request, $productGroup);
        $products = $this->getProductList($request, $productGroup);

        return $this->sendReturn($groups, $products);
    }

    /**
     * send return data
     *
     * @param Collection $groups
     * @param Collection $products
     * @return json
     */
    public function sendReturn($groups, $products)
    {
        $groupList = new ProductGroupListTransformer($groups);
        $productList = new ProductListCollectionTransformer($products);

        return new JsonResponse([
            'data' => [
                'groups' => $groupList->toArray(),
                'products' => $productList->toArray()
            ]
        ]);
    }

    /**
     * get product group list.
     *
     * @param Request $request
     * @param ProductGroup $productGroup
     *
     * @return paginator
     */
    protected function getProductGroupsList(Request $request, ProductGroup $productGroup = null)
    {
        $productGroups = ProductGroup::whereStatus('active');

        if (! is_null($productGroup)) {
            $productGroups->whereParentId($productGroup->id);
        }

        if ($request->has('key')) {
            $productGroups->where('id', $request->key);
            $productGroups->orwhere('name', 'like', "%{$request->key}%");
        }

        return $productGroups->get();
    }

    /**
     * get product list.
     *
     * @param Request $request
     * @param ProductGroup $productGroup
     *
     * @return paginator
     */
    protected function getProductList(Request $request, ProductGroup $productGroup = null)
    {
        $products = Product::whereStatus('active');

        if (is_null($productGroup)) {
            $products->has('groups', '=', 0);
        } else {
            $products->whereHas('groups', function($query) use ($productGroup) {
                return $query->whereProductGroupId($productGroup->id);
            });
        }

        if ($request->has('key')) {
            $products->where('id', $request->key);
            $products->orwhere('name', 'like', "%{$request->key}%");
            $products->orWhere('description', 'like', "%{$request->key}%");
        }

        return $products->get();
    }

    /**
     * create product group
     *
     * @param CreateProductGroupRequest $request
     * @return json
     */
    public function createGroup(CreateProductGroupRequest $request)
    {
        $productGroup = ProductGroup::create(
            $request->only(['name', 'parent_id', 'status'])
        );

        if ($request->has('products') AND count($request->products) > 0) {
            $productGroup->products()->attach($request->products);
        }

        return new JsonResponse(['message' => 'Product group has been created'], 201);
    }

    /**
     * update product group
     *
     * @param UpdateProductGroupRequest $request
     * @return json
     */
    public function updateGroup(UpdateProductGroupRequest $request)
    {
        $productGroup = $request->getProductGroup();
        $productGroup->update(
            $request->only(['name', 'parent_id', 'status'])
        );

        if ($request->has('products.remove') AND count($request->products['remove']) > 0) {
            $productGroup->products()->detach($request->products['remove']);
        }

        if ($request->has('products.add') AND count($request->products['add']) > 0) {
            $productGroup->products()->attach($request->products['add']);
        }

        return new JsonResponse(['message' => 'Product group has been updated']);
    }

    /**
     * update product group
     *
     * @param DeleteProductGroupRequest $request
     * @return json
     */
    public function deleteGroup(DetailProductGroupRequest $request)
    {
        $productGroup = $request->getProductGroup();

        $productGroup->delete();

        return new JsonResponse(['message' => 'Product group has been deleted']);
    }
}
