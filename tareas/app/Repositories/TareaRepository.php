<?php

namespace App\Repositories;

use App\Interfaces\TareaRepositoryInterface;
use App\Models\Tarea;

class TareaRepository implements TareaRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(){
        return Tarea::all();
    }

    public function getById($id){
       return Tarea::findOrFail($id);
    }

    public function store(array $data){
       return Tarea::create($data);
    }

    public function update(array $data,$id){
       return Tarea::whereId($id)->update($data);
    }
    
    public function delete($id){
       Tarea::destroy($id);
    }
}
