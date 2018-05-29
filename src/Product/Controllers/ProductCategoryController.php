<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\ProductCategory;
use Denmasyarikin\Sales\Product\Requests\DetailProductCategoryRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductCategoryRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductCategoryRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductCategoryRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductCategoryListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductCategoryDetailTransformer;

class ProductCategoryController extends Controller
{
    /**
     * category list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $categories = $this->getProductCategoryList($request);
        $transform = new ProductCategoryListTransformer($categories);
        $data = ['data' => $transform->toArray()];

        if ($request->has('parent_id')) {
            $category = ProductCategory::find((int) $request->parent_id);

            if ($category) {
                $data['detail'] = (new ProductCategoryDetailTransformer($category))->toArray();
            }

            if ($category->parent_id) {
                $category = ProductCategory::find((int) $category->parent_id);

                if ($category) {
                    $data['parent'] = (new ProductCategoryDetailTransformer($category))->toArray();
                }
            }
        }

        return new JsonResponse($data);
    }

    /**
     * get category list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getProductCategoryList(Request $request)
    {
        $categories = ProductCategory::orderBy('name', 'ASC');

        if ($request->has('parent_id')) {
            $categories->whereParentId($request->parent_id);
        } else {
            $categories->whereNull('parent_id');
        }

        if ($request->has('workspace_id')) {
            $categories->workspaceId($request->workspace_id);
        } else {
            $categories->myWorkspace();
        }

        if ($request->has('key')) {
            $categories->orwhere('name', 'like', "%{$request->key}%");
        }

        return $categories->get();
    }

    /**
     * get detail.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductCategoryRequest $request)
    {
        $transform = new ProductCategoryDetailTransformer($request->getProductCategory());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create product category.
     *
     * @param CreateProductCategoryRequest $request
     *
     * @return json
     */
    public function createCategory(CreateProductCategoryRequest $request)
    {
        $productCategory = ProductCategory::create($request->only(['name', 'image', 'parent_id']));

        $productCategory->workspaces()->sync($request->workspace_ids);

        return new JsonResponse([
            'message' => 'Product category has been created',
            'data' => (new ProductCategoryDetailTransformer($productCategory))->toArray(),
        ], 201);
    }

    /**
     * update product category.
     *
     * @param UpdateProductCategoryRequest $request
     *
     * @return json
     */
    public function updateCategory(UpdateProductCategoryRequest $request)
    {
        $productCategory = $request->getProductCategory();

        $productCategory->update($request->only(['name', 'image', 'parent_id']));
        $productCategory->workspaces()->sync($request->workspace_ids);

        return new JsonResponse(['message' => 'Product category has been updated']);
    }

    /**
     * update product category.
     *
     * @param DeleteProductCategoryRequest $request
     *
     * @return json
     */
    public function deleteCategory(DeleteProductCategoryRequest $request)
    {
        $productCategory = $request->getProductCategory();

        foreach ($productCategory->children as $category) {
            $category->update(['parent_id' => $productCategory->parent_id]);
        }

        foreach ($productCategory->products as $product) {
            $product->update(['category_id' => $productCategory->parent_id]);
        }

        $productCategory->delete();

        return new JsonResponse(['message' => 'Product category has been deleted']);
    }
}
