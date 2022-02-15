@extends('layouts.default')
@section('title', 'LibraryFinder')

@section('vue')
@endsection


@section('content')

<form action="search" method="get">
  <div class="mb-3">
    <label for="search" class="form-label">Search</label>
    <input type="text" class="form-control" id="search" name="search" aria-describedby="searchHelp">
    <div id="searchHelp" class="form-text">Help.</div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection

@section('scripts')
@endsection