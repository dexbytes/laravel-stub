<?php

namespace App\Http\Controllers\Api\Faq;

use App\Models\Faq\FaqCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Faq\StoreFaqCategoryRequest;
use App\Http\Requests\Faq\UpdateFaqCategoryRequest;
use App\Http\Resource\Faq\FaqCategoryResource;
use App\Http\Resource\Faq\FaqCategoryCollection;

class FaqCategoryController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/faq/faq-category",
        * operationId="FaqCategory",
        * tags={"FaqCategory"},
        * summary="Get list of FaqCategory",
        * description="Returns list of FaqCategory",
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
       return $this->sendResponse(FaqCategoryCollection::collection(FaqCategory::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/faq/faq-category",
     * operationId="FaqCategoryStore",
     * tags={"FaqCategory"},
     * summary="Store FaqCategory",
     * description="Returns FaqCategory data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="FaqCategory created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="FaqCategory created Successfully",
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
    public function store(StoreFaqCategoryRequest $request, FaqCategory $faqCategory)
    {
          try
        {
            $resource = $faqCategory->create($request->all());            
            return $this->sendResponse(new FaqCategoryResource($resource), "FaqCategory created Successfully");

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
        * @OA\Get(
        * path="/api/faq/faq-category/{id}",
        * operationId="faqCategoryGet",
        * tags={"FaqCategory"},
        * summary="Get FaqCategory information",
        * description="Returns FaqCategory information",
        *  @OA\Parameter(
        *          name="id",
        *          description="FaqCategory id",
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

            $faqCategory = FaqCategory::find($id);
            return $this->sendResponse(new FaqCategoryResource($faqCategory), 'Successfully.');
       
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
     * @OA\Put(
     * path="/api/faq/faq-category/{id}",
     * operationId="FaqCategoryUdpate",
     * tags={"FaqCategory"},
     * summary="Update existing FaqCategory",
     * description="Returns updated FaqCategory data",
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
     *          description="FaqCategory updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="FaqCategory updated Successfully",
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
    public function update(UpdateFaqCategoryRequest $request, $id)
    {
        try
        {   $faqCategory = FaqCategory::findOrFail($id);
            $faqCategory->update($request->all());            
            return $this->sendResponse(new FaqCategoryResource($faqCategory), "FaqCategory updated Successfully");
        
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/faq/faq-category/{id}",
     *      operationId="deleteFaqCategory",
     *      tags={"FaqCategory"},
     *      summary="Delete existing FaqCategory",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="FaqCategory id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="FaqCategory deleted Successfully",
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
            FaqCategory::find($id)->delete();
            return $this->sendResponse([], 'FaqCategory deleted Successfully');

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }
}
