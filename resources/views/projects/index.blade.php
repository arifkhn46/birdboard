@extends('layouts.app')


@section('content')
  <div class="flex item-center mb-4">
    <h1 class="mr-auto">Birdboard</h1>
    <a href="/projects/create">New Project</a>
  </div>

  <div class="flex">
    @forelse($projects as $project)
      <div class="bg-white mr-4 p-5 shadow w-1/3" style="height: 200px;">
        <h3 class="text-xl font-normal py-4">{{ str_limit($project->title, 10) }}</h3>

        <div class="text-grey-dark">{{ str_limit($project->description, 50) }}</div>
      </div>
    @empty
      <div>No Projects yet.</div>
    @endforelse
  </div>
@endsection