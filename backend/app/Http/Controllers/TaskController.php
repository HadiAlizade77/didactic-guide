<?php
 
namespace App\Http\Controllers;
 
use App\Models\Task;
 
class TaskController extends Controller
{
    // /**
    //  * Show the profile for a given user.
    //  */
    // public function show(string $id): View
    // {
    //     return view('user.profile', [
    //         'user' => User::findOrFail($id)
    //     ]);
    // }

    /**
     * Create a new task in the Task model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request)
    {
        // Create a new task in the Task model
        $task = new Task();
        $task->industry = $request['industry'];
        $task->country = $request['country'];
        $task->state = $request['state'];
        $task->keyword = $request['keyword'];
        $task->board = $request['board'];
        $task->save();

        // Return a response
        return response()->json(['message' => 'Task created successfully']);
    }
    
    /**
     * Retrieve all tasks from the Task model.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTasks()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }
}