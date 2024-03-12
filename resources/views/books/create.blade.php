@extends('layout.privatebook')

@section('content')
<div class="row">

    <form action="{{ route('book-store') }}" method="POST" enctype="multipart/form-data" class="col-md-12">


        @csrf

        <a class="btn btn-primary mb-3" href="{{ route('book-listing') }}">BACK</a>

        <h4>Add New Book</h4>

        <!-- NI SAVE IMAGE -->
        <div class="mb-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" onchange="previewImage(event)">
            <!-- Display uploaded image preview -->
            <img id="imagePreview" src="#" alt="Preview" style="display: none; max-width: 100%; margin-top: 10px;">
        </div>
        <!-- _____________ -->

        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="">
        </div>

        <div class="mb-3">
            <label for="isbn">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" value="">
        </div>

        <div class="mb-3">
            <label for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="">
        </div>

        <input type="hidden" id="status" name="status" value="1">

        <div class="mb-3">
            <label for="synopsis">Synopsis</label>
            <textarea name="synopsis" id="synopsis" class="form-control" rows="10"></textarea>
        </div>

        <!-- GENRE -->

        <div class="mb-3">
            <label for="genre1">Genre 1</label>
            <select class="form-control" id="genre1" name="genre1">
                <option value="-">-</option>
                <option value="Romance">Romance</option>
                <option value="Mystery">Mystery</option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Historical">Historical</option>
                <option value="Horror">Horror</option>
                <option value="Comedy">Comedy</option>
                <option value="Yound-Adult">Young-Adult</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre2">Genre 2</label>
            <select class="form-control" id="genre2" name="genre2">
                <option value="-">-</option>
                <option value="Romance">Romance</option>
                <option value="Mystery">Mystery</option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Historical">Historical</option>
                <option value="Horror">Horror</option>
                <option value="Comedy">Comedy</option>
                <option value="Yound-Adult">Young-Adult</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre3">Genre 3</label>
            <select class="form-control" id="genre3" name="genre3">
                <option value="-">-</option>
                <option value="Romance">Romance</option>
                <option value="Mystery">Mystery</option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Historical">Historical</option>
                <option value="Horror">Horror</option>
                <option value="Comedy">Comedy</option>
                <option value="Yound-Adult">Young-Adult</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="genre4">Genre 4</label>
            <select class="form-control" id="genre4" name="genre4">
                <option value="-">-</option>
                <option value="Romance">Romance</option>
                <option value="Mystery">Mystery</option>
                <option value="Thriller">Thriller</option>
                <option value="Fantasy">Fantasy</option>
                <option value="Historical">Historical</option>
                <option value="Horror">Horror</option>
                <option value="Comedy">Comedy</option>
                <option value="Yound-Adult">Young-Adult</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
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
            output.style.display = 'block'; // Display the image once it's loaded
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
