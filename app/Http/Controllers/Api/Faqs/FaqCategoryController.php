<?php

namespace App\Http\Controllers\Api\Faqs;

use App\Models\Faqs\FaqCategory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Faqs\StoreFaqCategoryRequest;
use App\Http\Requests\Faqs\UpdateFaqCategoryRequest;
use App\Http\Resource\Faqs\FaqCategoryResource;
use App\Http\Resource\Faqs\FaqCategoryCollection;

class FaqCategoryController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/faqs/faq-category",
        * operationId="Faq Category",
        * tags={"Faq Category"},
        * summary="Get list of Faq Category",
        * description="Returns list of Faq Category",
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
     * path="/api/faqs/faq-category",
     * operationId="Faq CategoryStore",
     * tags={"Faq Category"},
     * summary="Store Faq Category",
     * description="Returns Faq Category data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Faq Category created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Faq Category created Successfully",
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
        * path="/api/faqs/faq-category/{id}",
        * operationId="Faq CategoryGet",
        * tags={"Faq Category"},
        * summary="Get Faq Category information",
        * description="Returns Faq Category information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Faq Category id",
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
     * path="/api/faqs/faq-category/{id}",
     * operationId="Faq CategoryUdpate",
     * tags={"Faq Category"},
     * summary="Update existing Faq Category",
     * description="Returns updated Faq Category data",
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
     *          description="Faq Category updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Faq Category updated Successfully",
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
     *      path="/api/faqs/faq-category/{id}",
     *      operationId="deleteFaq Category",
     *      tags={"Faq Category"},
     *      summary="Delete existing Faq Category",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Faq Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Faq Category deleted Successfully",
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
