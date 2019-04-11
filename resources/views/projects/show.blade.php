@extends('layouts.app')

@section('content')

  <header class="flex item-end mb-3">
      <div class="flex justify-between w-full items-center">
        <h2 class="text-grey text-sm font-normal">
          <a href="/projects" class="text-grey text-sm font-normal no-underline">My Projects</a> / {{ $project->title }}
        </h2>

        <div class="flex items-center">
          @foreach ($project->members as $member)
              <img
                  src="{{ gravatar_url($member->email) }}"
                  alt="{{ $member->name }}'s avatar"
                  class="rounded-full w-8 mr-2">
          @endforeach

          <img
              src="{{ gravatar_url($project->owner->email) }}"
              alt="{{ $project->owner->name }}'s avatar"
              class="rounded-full w-8 mr-2">

          <a href="{{ $project->path().'/edit' }}" class="button ml-4">Edit Project</a>
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
      <div class="lg:w-1/3 px-3 lg:py-8">
        @include ('projects.card')
        @include('projects.activity.card')
        <div class="card flex flex-col">
          <h3 class="text-xl font-normal py-4 -ml-5 mb-3 border-l-4 border-blue-light pl-4">
            Invite User
          </h3>
          <form method="POST" action="{{ $project->path() . '/invitations' }}">
            @csrf
            <div class="mb-3">
              <input type="text" name="email" class="border border-grey-light rounded w-full py-2 px-3" placeholder="Email address" />
            </div>
            <button type="submit" class="button">Invite</button>
          </form>
          @include('errors', ['bag' => 'invitations'])
        </div>
      </div>
    </div>
  </main>

@endsection