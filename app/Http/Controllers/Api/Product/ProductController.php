<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resource\Product\ProductResource;
use App\Http\Resource\Product\ProductCollection;

class ProductController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/product",
        * operationId="Product",
        * tags={"Product"},
        * summary="Get list of Product",
        * description="Returns list of Product",
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function index()
    {
       return $this->sendResponse(ProductCollection::collection(Product::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/product",
     * operationId="ProductStore",
     * tags={"Product"},
     * summary="Store Product",
     * description="Returns Product data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Product created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Product created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(StoreProductRequest $request, Product $product)
    {
          try
        {
            $resource = $product->create($request->all());            
            return $this->sendResponse(new ProductResource($resource), "Product created Successfully");

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
        * @OA\Get(
        * path="/api/product/{id}",
        * operationId="productGet",
        * tags={"Product"},
        * summary="Get Product information",
        * description="Returns Product information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Product id",
        *          required=true,
        *          in="path",
        *          @OA\Schema(
        *              type="integer"
        *          )
        *      ),
        *      @OA\Response(
        *          response=201,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function show($id)
    {
         try{

            $product = Product::find($id);
            return $this->sendResponse(new ProductResource($product), 'Successfully.');
       
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
     * @OA\Put(
     * path="/api/product/{id}",
     * operationId="ProductUdpate",
     * tags={"Product"},
     * summary="Update existing Product",
     * description="Returns updated Product data",
     *  @OA\Parameter(
    *          name="id",
    *          description="id",
    *          required=true,
    *          in="path",
    *          @OA\Schema(
    *              type="integer"
    *          )
    *      ),
     *    @OA\RequestBody(
     *         @OA\JsonContent()
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Product updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Product updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try
        {   $product = Product::findOrFail($id);
            $product->update($request->all());            
            return $this->sendResponse(new ProductResource($product), "Product updated Successfully");
        
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/product/{id}",
     *      operationId="deleteProduct",
     *      tags={"Product"},
     *      summary="Delete existing Product",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Product deleted Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        try
        {
            Product::find($id)->delete();
            return $this->sendResponse([], 'Product deleted Successfully');

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }
}
