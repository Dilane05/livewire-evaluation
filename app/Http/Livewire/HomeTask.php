<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class HomeTask extends Component
{

    
    use WithPagination;

    public $title , $description , $progress;

    public $test , $search ,$start , $end , $startdates , $endates;

    public $during , $taskAdd , $taskEdit , $taskDelete  , $taskView , $taskCalendar , $task_edit_id , $task_delete_id , $task_view_id;

    public $datet;

    public $events = [];

    public function updated(){
        $this->datet = Carbon :: now ();
    }

    protected $querystring = [
        'search' => ['except' => '']
    ];

    public function newtTask(){
        if ($this->taskAdd == "active") {
            $this->taskAdd = "";
        }else {        
            $this->taskAdd = "active";
        }
    }
    
    public function calendars(){
        if ($this->taskCalendar == "active") {
            $this->taskCalendar = "";
        }else{ 
            $this->taskCalendar = "active";
            //  dd($this->taskCalendar);
        }
    }


   
    public function closeAdd(){ 
        $this->taskAdd = "";
    }

    public function closeEdit(){ 
        $this->taskEdit = "";
    }

    public function closeDelete(){
        $this->taskDelete = "";
    }

    public function closeView(){
        $this->taskView = "";
    }


    public function storeTaskData()
    {
        $this->validate([
            'title' => 'required',
            'startdates' => 'required',
            'endates' => 'required',
            'description' => 'required',
        ]);

        $task = new Task();
        $task->title = $this->title;
        $task->startdates = $this->startdates;
        $task->endates = $this->endates;
        $task->description = $this->description;
        $task->progress = 0;
        
        $task->save();

        
        session()->flash('message', 'New Task has added successfully');
    }


    public function viewTask($id){
        if ($this->taskView == "active") {
            $this->taskView = "";
        }else {        
            $this->taskView = "active";
        }

        $this->task_view_id = $id;

    }

    public function fullTask(){
        
        $task = Task::where('id', $this->task_view_id)->first();

        $task->progress = 100;

        $task->save();

        session()->flash('message', 'Task has been termined');

    }


    public function editTask($id){
        if ($this->taskEdit == "active") {
            $this->taskEdit = "";
        }else {    
            $this->taskEdit = "active";    
        }


        
        $task = Task::where('id', $id)->first();

        $this->task_edit_id = $task->id;
        $this->title = $task->title;
        $this->startdates = $task->startdates;
        $this->endates = $task->endates;
        $this->description = $task->description;

    }

    public function editTaskData(){
       
        
        $task = Task::where('id', $this->task_edit_id )->first();

        $task->title = $this->title;
        $task->startdates = $this->startdates;
        $task->endates = $this->endates;
        $task->description = $this->description;
        $task->save();

        
        session()->flash('message', ' Task has Updated successfully');
    }

    public function deleteTask($id){
        if ($this->taskDelete == "active") {
            $this->taskDelete = "";
        }else {        
            $this->taskDelete = "active";
        }

        $this->task_delete_id = $id;

    }


    public function deleteTaskData(){
        
        $task = Task::where('id', $this->task_delete_id)->first();

        $task->delete();

        session()->flash('message', 'Task has been delete successfuly');

    }
    

    public function render()
    {
        $tasks = Task::where('title' , 'LIKE' , "%{$this->search}%")->paginate(5);
        

        $this->events = json_encode(Task::all());
        return view('livewire.home-task',[
                'tasks' => $tasks 
              ]);
    }
}
