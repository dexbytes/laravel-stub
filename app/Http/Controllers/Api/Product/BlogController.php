<?php

namespace App\Http\Controllers\Api\Product;

use App\Models\Product\Blog;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\Product\StoreBlogRequest;
use App\Http\Requests\Product\UpdateBlogRequest;
use App\Http\Resource\Product\BlogResource;
use App\Http\Resource\Product\BlogCollection;

class BlogController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/blog",
        * operationId="Blog",
        * tags={"Blog"},
        * summary="Get list of Blog",
        * description="Returns list of Blog",
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
       return $this->sendResponse(BlogCollection::collection(Blog::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/blog",
     * operationId="BlogStore",
     * tags={"Blog"},
     * summary="Store Blog",
     * description="Returns Blog data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Blog created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Blog created Successfully",
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
    public function store(StoreBlogRequest $request, Blog $blog)
    {
          try
        {
            $resource = $blog->create($request->all());            
            return $this->sendResponse(new BlogResource($resource), "Blog created Successfully");

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
        * @OA\Get(
        * path="/api/blog/{id}",
        * operationId="blogGet",
        * tags={"Blog"},
        * summary="Get Blog information",
        * description="Returns Blog information",
        *  @OA\Parameter(
        *          name="id",
        *          description="Blog id",
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

            $blog = Blog::find($id);
            return $this->sendResponse(new BlogResource($blog), 'Successfully.');
       
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
     * @OA\Put(
     * path="/api/blog/{id}",
     * operationId="BlogUdpate",
     * tags={"Blog"},
     * summary="Update existing Blog",
     * description="Returns updated Blog data",
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
     *          description="Blog updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Blog updated Successfully",
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
    public function update(UpdateBlogRequest $request, $id)
    {
        try
        {   $blog = Blog::findOrFail($id);
            $blog->update($request->all());            
            return $this->sendResponse(new BlogResource($blog), "Blog updated Successfully");
        
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/blog/{id}",
     *      operationId="deleteBlog",
     *      tags={"Blog"},
     *      summary="Delete existing Blog",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Blog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="Blog deleted Successfully",
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
            Blog::find($id)->delete();
            return $this->sendResponse([], 'Blog deleted Successfully');

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }
}
