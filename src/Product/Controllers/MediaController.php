<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductMediaRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductMediaRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductMediaRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductMediaRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductMediaListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductMediaDetailTransformer;

class MediaController extends Controller
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

        $transform = new ProductMediaListTransformer($product->medias()->orderBy('primary', 'DESC')->get());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create media.
     *
     * @param CreateProductMediaRequest $request
     *
     * @return json
     */
    public function createMedia(CreateProductMediaRequest $request)
    {
        $product = $request->getProduct();

        if ($request->primary) {
            $product->medias()->update(['primary' => false]);
        }

        $media = $product->medias()->create($request->only([
            'type', 'content', 'sequence', 'primary',
        ]));

        return new JsonResponse([
            'message' => 'Product Media has been created',
            'data' => (new ProductMediaDetailTransformer($media))->toArray(),
        ], 201);
    }

    /**
     * update media.
     *
     * @param UpdateProductMediaRequest $request
     *
     * @return json
     */
    public function updateMedia(UpdateProductMediaRequest $request)
    {
        $product = $request->getProduct();
        $media = $request->getProductMedia();

        if ($request->primary) {
            $product->medias()->update(['primary' => false]);
        }

        $media->update($request->only([
            'type', 'content', 'sequence', 'primary',
        ]));

        return new JsonResponse([
            'message' => 'Product Media has been updated',
            'data' => (new ProductMediaDetailTransformer($media))->toArray(),
        ]);
    }

    /**
     * update media primary.
     *
     * @param DetailProductMediaRequest $request
     *
     * @return json
     */
    public function updateMediaPrimary(DetailProductMediaRequest $request)
    {
        $product = $request->getProduct();
        $media = $request->getProductMedia();

        if ($request->primary) {
            $product->medias()->update(['primary' => false]);
        }

        $media->update(['primary' => true]);

        return new JsonResponse(['message' => 'Product Media Primary has been updated']);
    }

    /**
     * delete media.
     *
     * @param DeleteProductMediaRequest $request
     *
     * @return json
     */
    public function deleteMedia(DeleteProductMediaRequest $request)
    {
        $product = $request->getProduct();
        $media = $request->getProductMedia();
        $media->delete();

        return new JsonResponse([
            'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            'message' => 'Product media has been deleted',
        ]);
    }
}
