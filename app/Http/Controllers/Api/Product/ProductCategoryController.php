<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Product\StoreProductCategoryRequest;
use App\Http\Requests\Product\UpdateProductCategoryRequest;
use App\Http\Resource\Product\ProductCategoryResource;
use App\Http\Resource\Product\ProductCategoryCollection;

class ProductCategoryController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/product/product-category",
        * operationId="ProductCategory",
        * tags={"ProductCategory"},
        * summary="Get list of ProductCategory",
        * description="Returns list of ProductCategory",
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
     * path="/api/product/product-category",
     * operationId="ProductCategoryStore",
     * tags={"ProductCategory"},
     * summary="Store ProductCategory",
     * description="Returns ProductCategory data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="ProductCategory created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="ProductCategory created Successfully",
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
        * path="/api/product/product-category/{id}",
        * operationId="productCategoryGet",
        * tags={"ProductCategory"},
        * summary="Get ProductCategory information",
        * description="Returns ProductCategory information",
        *  @OA\Parameter(
        *          name="id",
        *          description="ProductCategory id",
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
     * path="/api/product/product-category/{id}",
     * operationId="ProductCategoryUdpate",
     * tags={"ProductCategory"},
     * summary="Update existing ProductCategory",
     * description="Returns updated ProductCategory data",
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
     *          description="ProductCategory updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="ProductCategory updated Successfully",
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
     *      path="/api/product/product-category/{id}",
     *      operationId="deleteProductCategory",
     *      tags={"ProductCategory"},
     *      summary="Delete existing ProductCategory",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="ProductCategory id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="ProductCategory deleted Successfully",
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
