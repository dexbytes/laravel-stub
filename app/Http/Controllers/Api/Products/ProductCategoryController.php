<?php

namespace App\Http\Controllers\Api\Products;

use App\Models\Products\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Products\StoreProductCategoryRequest;
use App\Http\Requests\Products\UpdateProductCategoryRequest;
use App\Http\Resource\Products\ProductCategoryResource;
use App\Http\Resource\Products\ProductCategoryCollection;

class ProductCategoryController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/products/product-category",
        * operationId="Product Category",
        * tags={"Product Category"},
        * summary="Get list of Product Category",
        * description="Returns list of Product Category",
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
       return $this->sendResponse(ProductCategoryCollection::collection(ProductCategory::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/products/product-category",
     * operationId="Product CategoryStore",
     * tags={"Product Category"},
     * summary="Store Product Category",
     * description="Returns Product Category data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Product Category created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Product Category created Successfully",
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
    public function store(StoreProductCategoryRequest $request, ProductCategory $productCategory)
    {
          try
        {
            $resource = $productCategory->create($request->all());            
            return $this->sendResponse(new ProductCategoryResource($resource), "ProductCategory created Successfully");

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
        * @OA\Get(
        * path="/api/products/product-category/{id}",
        * operationId="Product CategoryGet",
        * tags={"Product Category"},
        * summary="Get Product Category information",
        * description="Returns Product Category information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Product Category id",
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

            $productCategory = ProductCategory::find($id);
            return $this->sendResponse(new ProductCategoryResource($productCategory), 'Successfully.');
       
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
     * @OA\Put(
     * path="/api/products/product-category/{id}",
     * operationId="Product CategoryUdpate",
     * tags={"Product Category"},
     * summary="Update existing Product Category",
     * description="Returns updated Product Category data",
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
     *          description="Product Category updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Product Category updated Successfully",
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
    public function update(UpdateProductCategoryRequest $request, $id)
    {
        try
        {   $productCategory = ProductCategory::findOrFail($id);
            $productCategory->update($request->all());            
            return $this->sendResponse(new ProductCategoryResource($productCategory), "ProductCategory updated Successfully");
        
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/products/product-category/{id}",
     *      operationId="deleteProduct Category",
     *      tags={"Product Category"},
     *      summary="Delete existing Product Category",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Product Category deleted Successfully",
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
            ProductCategory::find($id)->delete();
            return $this->sendResponse([], 'ProductCategory deleted Successfully');

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }
}
