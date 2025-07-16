@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>Create a New Project</h1>
    <form action="{{ route('projet.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="name">Project Name</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
    </div>
    <div class="form-group">
      <label for="start_date">Start Date</label>
      <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="end_date">End Date</label>
      <input type="date" name="end_date" id="end_date" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
  </div>
@endsection