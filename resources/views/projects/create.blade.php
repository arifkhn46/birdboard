@extends('layouts.app')

@section('content')

  <form method="POST" action="/projects">
    @csrf
    <h1 class="heading">Create a project</h1>
    <input type="text" name="title" id="title">
    <textarea name="description" id="description"></textarea>
    <button type="submit">Create</button>
    <a href="/projects">Cancel</a>
  </form>
@endsection