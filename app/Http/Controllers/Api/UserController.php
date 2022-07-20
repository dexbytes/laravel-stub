<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resource\UserResource;
use App\Http\Resource\UserCollection;

class UserController extends BaseController
{
    
    
    public function __construct()
    {
        //
    }
    
     /**
        * @OA\Get(
        * path="/api/user",
        * operationId="User",
        * tags={"User"},
        * summary="Get list of User",
        * description="Returns list of User",
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
       return $this->sendResponse(UserCollection::collection(User::all()), 'Successfully.');
    }

    /**
     * @OA\POST(
     * path="/api/user",
     * operationId="UserStore",
     * tags={"User"},
     * summary="Store User",
     * description="Returns User data",
     *         @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User created Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User created Successfully",
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
    public function store(StoreUserRequest $request, User $user)
    {
          try
        {
            $resource = $user->create($request->all());            
            return $this->sendResponse(new UserResource($resource), "User created Successfully");

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
        * @OA\Get(
        * path="/api/user/{id}",
        * operationId="userGet",
        * tags={"User"},
        * summary="Get User information",
        * description="Returns User information",
        *  @OA\Parameter(
        *          name="id",
        *          description="User id",
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

            $user = User::find($id);
            return $this->sendResponse(new UserResource($user), 'Successfully.');
       
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

   /**
     * @OA\Put(
     * path="/api/user/{id}",
     * operationId="UserUdpate",
     * tags={"User"},
     * summary="Update existing User",
     * description="Returns updated User data",
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
     *          description="User updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User updated Successfully",
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
    public function update(UpdateUserRequest $request, $id)
    {
        try
        {   $user = User::findOrFail($id);
            $user->update($request->all());            
            return $this->sendResponse(new UserResource($user), "User updated Successfully");
        
        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/user/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete existing User",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *       @OA\Response(
     *          response=200,
     *          description="User deleted Successfully",
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
            User::find($id)->delete();
            return $this->sendResponse([], 'User deleted Successfully');

        } catch (\Exception $e) {                
            return $this->sendError($e->getMessage(), ['error'=> Response::HTTP_NOT_FOUND]);            
        }
    }
}
