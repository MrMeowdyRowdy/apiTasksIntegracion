<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Http\Requests\StoreTareaRequest;
use App\Http\Requests\UpdateTareaRequest;
use App\Interfaces\TareaRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\TareaResource;
use Illuminate\Support\Facades\DB;

class TareaController extends Controller
{
    private TareaRepositoryInterface $tareaRepositoryInterface;
    
    public function __construct(TareaRepositoryInterface $tareaRepositoryInterface)
    {
        $this->tareaRepositoryInterface = $tareaRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->tareaRepositoryInterface->index();

        return ApiResponseClass::sendResponse(TareaResource::collection($data),'',200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $tarea = $this->tareaRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new TareaResource($tarea),'',200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarea $tarea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         $this->tareaRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Tarea Delete Successful','',204);
    }
}