<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Http\Requests\StoreTareaRequest;
use App\Http\Requests\UpdateTareaRequest;
use App\Interfaces\TareaRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TareaResource;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Tarea Management API",
 *     description="API for managing tasks, including CRUD operations"
 * )
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 */
class TareaController extends Controller
{
    private TareaRepositoryInterface $tareaRepositoryInterface;
    
    public function __construct(TareaRepositoryInterface $tareaRepositoryInterface)
    {
        $this->tareaRepositoryInterface = $tareaRepositoryInterface;
    }
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="List all tasks",
     *     description="Retrieve a list of all tasks in the system",
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of tasks",
     *     )
     * )
     */
    public function index()
    {
        $data = $this->tareaRepositoryInterface->index();

        return ApiResponseClass::sendResponse(TareaResource::collection($data),'',200);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     description="Create a new task in the system",
     *     operationId="createTask",
     *     requestBody={
     *         "description": "JSON object representing the task to be created",
     *         "required": true,
     *         "content": {
     *             "application/json": {
     *                 "example": {
     *                     "title": "New Task",
     *                     "description": "This is a test task",
     *                     "status": "pending"
     *                 }
     *             }
     *         }
     *     },
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "id": 1,
     *                 "title": "New Task",
     *                 "description": "This is a test task",
     *                 "status": "pending",
     *                 "created_at": "2024-11-20T12:00:00Z",
     *                 "updated_at": "2024-11-20T12:00:00Z"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid Input",
     *         @OA\JsonContent(
     *             example={
     *                 "error": "Validation error",
     *                 "message": "The title field is required."
     *             }
     *         )
     *     )
     * )
     */
    public function store(StoreTareaRequest $request)
    {
        $details =[
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ];
        DB::beginTransaction();
        try{
             $tarea = $this->tareaRepositoryInterface->store($details);

             DB::commit();
             return ApiResponseClass::sendResponse(new TareaResource($tarea),'Tarea Create Successful',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a specific task",
     *     description="Retrieve details of a task by its ID",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task details",
     *     )
     * )
     */
    public function show($id)
    {
        $tarea = $this->tareaRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new TareaResource($tarea),'',200);
    }

    /**
     * @OA\Put(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Update an existing task",
     *     description="Update details of an existing task",
     *     operationId="updateTask",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     requestBody={
     *         "required": true,
     *         "description": "JSON object representing the task to be updated",
     *         "content": {
     *             "application/json": {
     *                 "example": {
     *                     "title": "Updated Task",
     *                     "description": "Updated description",
     *                     "status": "completed"
     *                 }
     *             }
     *         }
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "message": "Task updated successfully"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid Input",
     *         @OA\JsonContent(
     *             example={
     *                 "error": "Validation error",
     *                 "message": "The title field is required."
     *             }
     *         )
     *     )
     * )
     */
    public function update(UpdateTareaRequest $request, $id)
    {
        $updateDetails =[
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ];
        DB::beginTransaction();
        try{
             $tarea = $this->tareaRepositoryInterface->update($updateDetails,$id);

             DB::commit();
             return ApiResponseClass::sendResponse('Tarea Update Successful','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Delete(
     *     path="/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Delete a task",
     *     description="Remove a task from the system by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Task deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
         $this->tareaRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Tarea Delete Successful','',204);
    }
}