<?php
 
namespace App\Http\Controllers;
 
use App\Models\Task;
use App\Models\TaskQueues;
 
class TaskController extends Controller
{

    /**
     * Create a new task in the Task model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createTask(Request $request)
    {
        foreach ($request['industry'] as $industry) {
            $task = new Task();
            $taskQ = new TaskQueues();
            $task->industry = $industry;
            $task->country = $request['country'];
            $task->state = $request['state'];
            $task->keyword = $request['keyword'];
            $task->board = $request['board'];
            $task->save();
            $taskQ->task_id = $task->id;
            $taskQ->save();
        }
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
    
    /**
     * Retrieve all tasks from the Task model.
     *
     * @return \Illuminate\Http\Response
     */
    public function runTask()
    {
        $taskQ = TasksQueue::first();
        if($taskQ){
            TasksQueue::delete($taskQ->id);
            $task =Tasks::find($taskQ->task_id);
            $client = new \GuzzleHttp\Client([
                'base_uri' => env('BOT_HOST_IP'),
            ]);
            // request to bot
            $payload = ([
                'industryValue' => $task->industry,
                'keyword' => $task->keyword,
                'country' => $task->country,
            ]);
            $response = $client->post('/v1/li/extract',
                ['json' => $payload]
            );
        }
    }
}