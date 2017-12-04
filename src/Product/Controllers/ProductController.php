<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductDetailTransformer;

class ProductController extends Controller
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
        $products = $this->getProductList($request);

        $transform = new ProductListTransformer($products);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get product list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getProductList(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC');

        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $products->whereStatus('active');
                    break;

                case 'inactive':
                    $products->whereStatus('inactive');
                    break;
            }
        }

        if ($request->has('key')) {
            $products->where('id', $request->key);
            $products->orwhere('name', 'like', "%{$request->key}%");
            $products->orWhere('description', 'like', "%{$request->key}%");
        }

        return $products->paginate($request->get('per_page') ?: 10);
    }

    /**
     * get detail.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductRequest $request, $id)
    {
        $transform = new ProductDetailTransformer($request->getProduct());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create product.
     *
     * @param CreateProductRequest $request
     *
     * @return json
     */
    public function createProduct(CreateProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::create($request->only([
                'name', 'description', 'unit_id', 'min_order', 'customizable',
                'base_price', 'per_unit_price', 'process_service_count',
                'process_good_count', 'process_manual_count', 'status',
            ]));

            foreach ($request->processes as $process) {
                $this->createProcess($product, $process);
            }

            if ($request->has('medias')) {
                foreach ($request->medias as $media) {
                    $this->createMedia($product, $media);
                }
            }

            if ($request->has('groups')) {
                $this->assignGroups($product, $request->groups);
            }

            DB::commit();

            return new JsonResponse(['message' => 'Product has been created'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * create processes.
     *
     * @param Product $product
     * @param array   $processInput
     *
     * @return ProductProcess
     */
    protected function createProcess(Product $product, array $processInput)
    {
        $process = $product->processes()->create(array_intersect_key(
            $processInput, array_flip([
                'type', 'type_as', 'reference_id',
                'name', 'specific', 'quantity', 'base_price', 'required',
                'static_price', 'static_to_order_count', 'unit_id',
            ])
        ));

        if (isset($processInput['options'])) {
            foreach ($processInput['options'] as $option) {
                $process->children()->create(array_merge(array_intersect_key(
                    $option, array_flip([
                        'type', 'type_as', 'reference_id',
                        'name', 'specific', 'quantity', 'base_price', 'required',
                        'static_price', 'static_to_order_count', 'unit_id',
                    ])
                ), ['product_id' => $product->id]));
            }
        }

        return $process;
    }

    /**
     * create media.
     *
     * @param Product $product
     * @param array   $media
     *
     * @return ProductMedia
     */
    protected function createMedia(Product $product, array $media)
    {
        return $product->medias()->create(array_intersect_key(
            $media, array_flip(['type', 'content', 'sequence', 'primary'])
        ));
    }

    /**
     * assign product to group.
     *
     * @param Product $product
     *
     * @return bool
     */
    protected function assignGroups(Product $product, array $groups)
    {
        return $product->groups()->sync($groups);
    }

    /**
     * update product.
     *
     * @param UpdateProductRequest $request
     *
     * @return json
     */
    public function updateProduct(UpdateProductRequest $request)
    {
        $product = $request->getProduct();

        try {
            DB::beginTransaction();

            $product->update($request->only([
                'name', 'description', 'unit_id', 'min_order', 'customizable',
                'base_price', 'per_unit_price', 'process_service_count',
                'process_good_count', 'process_manual_count', 'status',
            ]));

            if ($request->has('processes')) {
                $this->updateProcess($product, $request->processes);
            }

            if ($request->has('medias')) {
                $this->updateMedia($product, $request->medias);
            }

            if ($request->has('groups.remove')) {
                $product->groups()->detach($request->input('groups.remove'));
            }

            if ($request->has('groups.add')) {
                $product->groups()->sync($request->input('groups.add'), false);
            }

            DB::commit();

            return new JsonResponse(['message' => 'Product has been updated']);
        } catch (\Exception $e) {
            DB::rollback();

            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * update process.
     *
     * @param Product $product
     * @param array   $processes
     */
    protected function updateProcess(Product $product, array $processes)
    {
        if (isset($processes['remove'])) {
            foreach ($processes['remove'] as $id) {
                if ($process = $product->processes()->find($id)) {
                    $process->delete();
                }
            }
        }

        if (isset($processes['add'])) {
            foreach ($processes['add'] as $process) {
                $this->createProcess($product, $process);
            }
        }
    }

    /**
     * update media.
     *
     * @param Product $product
     * @param array   $medias
     */
    protected function updateMedia(Product $product, array $medias)
    {
        if (isset($medias['remove'])) {
            foreach ($medias['remove'] as $id) {
                if ($media = $product->medias()->find($id)) {
                    $media->delete();
                }
            }
        }

        if (isset($medias['add'])) {
            foreach ($medias['add'] as $media) {
                $this->createMedia($product, $media);
            }
        }
    }

    /**
     * delete product.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function deleteProduct(DetailProductRequest $request)
    {
        $product = $request->getProduct();

        $product->delete();

        return new JsonResponse(['message' => 'Product has been deleted']);
    }
}
