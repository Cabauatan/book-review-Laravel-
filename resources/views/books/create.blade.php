@extends('layouts.app')

@section('content')
  <h1 class="mb-10 text-2xl">Add Book </h1>

  <form method="POST" action="{{ route('books.store') }}">
    @csrf
    <label for="title">Title</label>
    <input name="title" id="title" required class="input mb-4"></input>
    <label for="author">Author</label>
    <input name="author" id="author" required class="input mb-4"></input>

    <button type="submit" class="btn">Add Book</button>
  </form>
@endsection