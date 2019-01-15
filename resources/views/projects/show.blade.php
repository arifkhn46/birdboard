@extends('layouts.app')

@section('content')

  <header class="flex item-end mb-3">
      <div class="flex justify-between w-full items-center">
        <h2 class="text-grey text-sm font-normal">
          <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects</a> / {{ $project->title }}
        </h2>

        <a class="button" href="/projects/create">New Project</a>
      </div>
  </header>

  <main>
    <div class="lg:flex -mx-3">
      <div class="lg:w-3/4 px-3 mb-6">
        <div class="mb-8">
          <h2 class="text-grey text-lg font-normal mb-3">Tasks</h2>

            @foreach($project->tasks as $task)
              <div class="card mb-3">
                {{ $task->body }}
              </div>
            @endforeach
            <div class="card mb-3">
              <form action="{{ $project->path() . '/tasks'}}" method="POST">
                @csrf
                <input placeholder="Add a new task" name="body" class="w-full"/>
              </form>
            </div>
        </div>
        <div>
            <h2 class="text-grey text-lg font-normal mb-3">General Notes</h2>

            <textarea class="card w-full" style="min-height: 200px;">Lorem ipsum</textarea>
        </div>
      </div>
      <div class="lg:w-1/3 px-3 py">
        <div class="mb-8"></div>
        @include ('projects.card')
      </div>
    </div>
  </main>

@endsection