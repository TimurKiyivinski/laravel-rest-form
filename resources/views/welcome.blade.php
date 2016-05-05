<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

        <meta name="_token" content="{{ csrf_token() }}"/>

        <style>
        body {
            padding-top: 80px;
        }
        </style>

    </head>
    <body>
        <div class="container">
            {{-- This row will show all our Tasks --}}
            <div class="row">
                <div class="page-header">
                    <h1>Tasks<a id="create" href="{{ route('task.create') }}" class="btn btn-primary pull-right">Create</a></h1>
                </div>
                <div id="tasks_well" class="well" data-url="{{ route('task.index') }}">
                    <div id="task_template" class="panel panel-primary hidden">
                        <div class="panel-heading">
                            <a href="{{ route('task.edit', 'id') }}" class="btn btn-warning pull-right">Update</a>
                        </div>
                        <div class="panel-body">
                            Data
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($task))
            {{-- This row will update our task --}}
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Update Task
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="form_name">Name</label>
                            <input type="text" class="form-control" id="form_name" placeholder="Task Name" value="{{ $task->name }}"/>
                        </div>
                        <div class="form-group">
                            <label for="form_data">Data</label>
                            <textarea class="form-control" id="form_data" placeholder="Task Data">{{ $task->data }}</textarea>
                        </div>
                        <button id="submit" type="submit" class="btn btn-primary submit" data-method="PUT" data-url="{{ route('task.update', $task->id) }}">Update</button>
                        <button id="submit" type="submit" class="btn btn-danger submit" data-method="DELETE" data-url="{{ route('task.destroy', $task->id) }}">Delete</button>
                    </div>
                </div>
            </div>
            @else
            {{-- This row will create our tasks --}}
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Create Task
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="form_name">Name</label>
                            <input type="text" class="form-control" id="form_name" placeholder="Task Name"/>
                        </div>
                        <div class="form-group">
                            <label for="form_data">Data</label>
                            <textarea class="form-control" id="form_data" placeholder="Task Data"></textarea>
                        </div>
                        <button id="submit" type="submit" class="btn btn-primary submit" data-method="POST" data-url="{{ route('task.store') }}">Create</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
<script type="text/javascript">
(function() {
    // Get CSRF token
    let getToken = function() {
        return $('meta[name=_token]').attr('content')
    }

    // Adds a task to the task well
    let addTask = function(task) {
        let task_panel = $('#task_template').clone()
        task_panel.removeClass('hidden')

        // Gets update button HTML
        let update_button = task_panel.children('.panel-heading').html()
        update_button = update_button.replace("id", task.id) // Change URL to new ID

        task_panel.children('.panel-heading').html(`${update_button} <h4>${task.name}</h4>`)
        task_panel.children('.panel-body').html(task.data)
        $('#tasks_well').append(task_panel)
    }

    // Get all tasks as a list
    let getTasks = function() {
        let url = $('#tasks_well').data('url')
        $.get(url, function(data) {
            data.forEach(function(task) {
                addTask(task)
            })
        })
    }

    $('.submit').click(function(){
        let method = $(this).data('method')
        let url = $(this).data('url')
        data = {
            _token: getToken(),
            name: $('#form_name').val(),
            data: $('#form_data').val()
        }

        $.ajax({
            'url': url,
            'method': method,
            'data': data
        }).done(function(data) {
            if (method == "POST") {
                addTask(data)
            } else {
                window.location = $('#create').attr('href')
            }
        })

    })

    getTasks()
})()
</script>
    </body>
</html>
