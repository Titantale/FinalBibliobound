@extends('layout.privatebook')

@section('content')
<div class="row">

    @if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
    @endif


    <form action="{{ route('book-update', $book->id) }}" method="POST" enctype="multipart/form-data" class="col-md-12">
        @csrf

        <a class="btn btn-primary mb-3" href="{{ route('book-listing') }}">BACK</a>

        <h4>Edit Book</h4>

        <!-- Image upload -->
        <div class="mb-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" onchange="previewImage(event)">
            <img id="imagePreview" src="{{ asset('images/'.$book->image) }}" alt="Preview" style="max-width: 100%; margin-top: 10px;">
        </div>


        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{$book->title}}">
        </div>

        <div class="mb-3">
            <label for="isbn">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" value="{{$book->isbn}}">
        </div>

        <div class="mb-3">
            <label for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="{{$book->author}}">
        </div>

        <div class="mb-3">
            <label for="status">Status</label>
            <input type="text" class="form-control" id="status" name="status" value="{{$book->status}}">
        </div>

        <div class="mb-3">
            <label for="synopsis">Synopsis</label>
            <textarea name="synopsis" id="synopsis" class="form-control" rows="10">{{$book->synopsis}}</textarea>
        </div>

        
        <!-- GENRE -->

        <div class="mb-3">
            <label for="genre1">Genre 1</label>
            <select class="form-control" id="genre1" name="genre1">
                <option value="-">-</option>
                <option value="Romance" {{ $book->genre1 == 'Romance' ? 'selected' : '' }}>Romance</option>
                <option value="Mystery" {{ $book->genre1 == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                <option value="Thriller" {{ $book->genre1 == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                <option value="Fantasy" {{ $book->genre1 == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                <option value="Historical" {{ $book->genre1 == 'Historical' ? 'selected' : '' }}>Historical</option>
                <option value="Horror" {{ $book->genre1 == 'Horror' ? 'selected' : '' }}>Horror</option>
                <option value="Comedy" {{ $book->genre1 == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                <option value="Young-Adult" {{ $book->genre1 == 'Young-Adult' ? 'selected' : '' }}>Young-Adult</option>
                <option value="Fiction" {{ $book->genre1 == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                <option value="Non-Fiction" {{ $book->genre1 == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre2">Genre 2</label>
            <select class="form-control" id="genre2" name="genre2">
                <option value="-">-</option>
                <option value="Romance" {{ $book->genre2 == 'Romance' ? 'selected' : '' }}>Romance</option>
                <option value="Mystery" {{ $book->genre2 == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                <option value="Thriller" {{ $book->genre2 == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                <option value="Fantasy" {{ $book->genre2 == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                <option value="Historical" {{ $book->genre2 == 'Historical' ? 'selected' : '' }}>Historical</option>
                <option value="Horror" {{ $book->genre2 == 'Horror' ? 'selected' : '' }}>Horror</option>
                <option value="Comedy" {{ $book->genre2 == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                <option value="Young-Adult" {{ $book->genre2 == 'Young-Adult' ? 'selected' : '' }}>Young-Adult</option>
                <option value="Fiction" {{ $book->genre2 == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                <option value="Non-Fiction" {{ $book->genre2 == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre3">Genre 3</label>
            <select class="form-control" id="genre3" name="genre3">
                <option value="-">-</option>
                <option value="Romance" {{ $book->genre3 == 'Romance' ? 'selected' : '' }}>Romance</option>
                <option value="Mystery" {{ $book->genre3 == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                <option value="Thriller" {{ $book->genre3 == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                <option value="Fantasy" {{ $book->genre3 == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                <option value="Historical" {{ $book->genre3 == 'Historical' ? 'selected' : '' }}>Historical</option>
                <option value="Horror" {{ $book->genre3 == 'Horror' ? 'selected' : '' }}>Horror</option>
                <option value="Comedy" {{ $book->genre3 == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                <option value="Young-Adult" {{ $book->genre3 == 'Young-Adult' ? 'selected' : '' }}>Young-Adult</option>
                <option value="Fiction" {{ $book->genre3 == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                <option value="Non-Fiction" {{ $book->genre3 == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre4">Genre 4</label>
            <select class="form-control" id="genre4" name="genre4">
                <option value="-">-</option>
                <option value="Romance" {{ $book->genre4 == 'Romance' ? 'selected' : '' }}>Romance</option>
                <option value="Mystery" {{ $book->genre4 == 'Mystery' ? 'selected' : '' }}>Mystery</option>
                <option value="Thriller" {{ $book->genre4 == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                <option value="Fantasy" {{ $book->genre4 == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                <option value="Historical" {{ $book->genre4 == 'Historical' ? 'selected' : '' }}>Historical</option>
                <option value="Horror" {{ $book->genre4 == 'Horror' ? 'selected' : '' }}>Horror</option>
                <option value="Comedy" {{ $book->genre4 == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                <option value="Young-Adult" {{ $book->genre4 == 'Young-Adult' ? 'selected' : '' }}>Young-Adult</option>
                <option value="Fiction" {{ $book->genre4 == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                <option value="Non-Fiction" {{ $book->genre4 == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
            </select>
        </div>
        

        <button class="btn btn-primary">SAVE</button>
    </form>
</div>

<script>
    // Function to preview image
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection


<!-- <div class="mb-3">
            <label for="genre1">Genre 1</label>
            <input type="text" class="form-control" id="genre1" name="genre1" value="{{ $book->genre1 }}">
        </div>

        <div class="mb-3">
            <label for="genre2">Genre 2</label>
            <input type="text" class="form-control" id="genre2" name="genre2" value="{{ $book->genre2 }}">
        </div>

        <div class="mb-3">
            <label for="genre3">Genre 3</label>
            <input type="text" class="form-control" id="genre3" name="genre3" value="{{ $book->genre3 }}">
        </div>

        <div class="mb-3">
            <label for="genre4">Genre 4</label>
            <input type="text" class="form-control" id="genre4" name="genre4" value="{{ $book->genre4 }}">
        </div>  -->
