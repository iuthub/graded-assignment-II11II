<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view('task', [
            'tasks' => $tasks
        ]);
   
    }
    public function addTask(Request $request){
       
            $validator = Validator::make($request->all(), [
                'newTask' => 'required|max:255|min:2',
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect('/task')
                    ->withInput()
                    ->withErrors($validator);
            }
        
            $task = new Task;
            $task->task = $request->newTask;
            $task->user_id=$request->user_id;
            $task->save();
        
            return redirect('/task');
     
    }  
     public function edit(Request $request,$id){
       
            $validator = Validator::make($request->all(), [
                'editTask' => 'required|max:255|min:2',
            ]);
            if ($validator->fails()) {
                return redirect('/task')
                    ->withInput()
                    ->withErrors($validator);
            }
         
                Task::where('id',$id)->update(['task'=>$request->editTask]);
            // DB::table('tasks')
            // ->where('id', $id)
            // ->update(['task' => $request->editTask]);
    
            return redirect('/task');
     
    }
    public function editTask($id){
     
           return view('edit', [
            'id' => $id
        ]);
    
   }
   public function deleteTask ($id) {
        Task::findOrFail($id)->delete();
    
        return redirect('/task');
    }
}
