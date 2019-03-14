@extends('layouts.app')

@section('content')

  <header class="flex item-end mb-3">
      <div class="flex justify-between w-full items-center">
        <h2 class="text-grey text-sm font-normal">
          <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects</a> / {{ $project->title }}
        </h2>

        <div>
          @foreach($project->members as $member)
            <img src="https://gravatar.com/avatar/{{ md5($member->email) }}?s=60" alt="{{ $member->name }}'s avatar'">
          @endforeach
          <a class="button" href="{{ $project->path() . '/edit' }}">Edit Project</a>
        </div>
      </div>
  </header>

  <main>
    <div class="lg:flex -mx-3">
      <div class="lg:w-3/4 px-3 mb-6">
        <div class="mb-8">
          <h2 class="text-grey text-lg font-normal mb-3">Tasks</h2>

            @foreach($project->tasks as $task)
              <div class="card mb-3">
                <form method="POST" action="{{ $project->path() . '/tasks/' . $task->id }}">
                  @method('PATCH')
                  @csrf
                  <div class="flex">
                    <input value="{{ $task->body }}" name="body" class="w-full {{ $task->completed ? 'text-grey' : ''}}" />
                    <input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}/>
                  </div>
                </form>
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

            <form method="POST" action="{{ $project->path() }}">
              @csrf
              @method('PATCH')
              <textarea
                name="notes"
                class="card w-full mb-4"
                style="min-height: 200px;"
                placeholder="Anything special that you want to make a note of?"
              >{{ $project->notes }}</textarea>

              <button type="submit" class="button">Save</button>
            </form>
        </div>
      </div>
      <div class="lg:w-1/3 px-3 py">
        <div class="mb-8"></div>
        @include ('projects.card')

        @include('projects.activity.card')
      </div>
    </div>
  </main>

@endsection