@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')


@section('content')


<h2 class="mb-4">Book Listing</h2>


<div class="row mb-3">
    <div class="col-md-6"> <!-- Adjust the column width as needed -->
        <!-- Search Bar -->
        <form action="{{ route('book-search') }}" method="GET" class="form-inline">
            <input type="text" name="query" class="form-control mr-sm-2" placeholder="Search books" aria-label="Search" value="{{ $query ?? '' }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search fa-sm"></i></button>
        </form>

    </div>

    @if(auth()->check() && (auth()->user()->userstatus == 2 ))
    <div class="col-md-6 text-right">
        <a href="{{ route('book-create') }}" class="btn btn-primary">Add New Books</a>
    </div>
    @endif

</div>

<style>
    .centered-image {
        display: block;
        margin: auto;
        padding-top: 10px;
        height: 300px;
    }
    .card-body p {
        margin-bottom: 0; /* Remove extra margin */
    }
    .status-available {
    background-color: #28a745; /* Green color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}
    .status-booked {
    background-color: #FFFF00; /* Yellow color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}
    .status-borrowed {
    background-color: #FF0000; /* Red color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}

</style>

<div class="row">
    @if(session('success'))
        <div class="col-md-12">
            <p class="alert alert-success">{{ session('success') }}</p>
        </div>
    @endif

    @foreach($books as $book)
    <div class="col-md-4">
        <div class="card">
            <!-- Display Image -->
            <img src="{{ asset('images/'.$book->image) }}" width="300" height="300" alt="" class="img-fluid centered-image">

            <div class="card-body text-center">
                <h4 class="card-title my-title">{{ $book->title }}</h4>
                <p class="card-text">Genres:
                    @if($book->genre1 != '-') {{ $book->genre1 }} @endif
                    @if($book->genre2 != '-') {{ $book->genre2 }} @endif
                    @if($book->genre3 != '-') {{ $book->genre3 }} @endif
                    @if($book->genre4 != '-') {{ $book->genre4 }} @endif
                </p> 
                <br>
                <p class="card-text">Status: 
                            @if($book->status == 1)
                                <span class="status-available">Available</span>
                            @elseif($book->status == 2)
                                <span class="status-booked">Booked</span>
                            @elseif($book->status == 3)
                                <span class="status-borrowed">Borrowed</span>
                            @endif
                </p> 
                <br>
                <p class="mb-0">

                @if(auth()->check() && (auth()->user()->userstatus == 1 ))
                        <a href="{{ route('book-single', $book->id) }}" class="btn btn-primary mx-2">View</a>
                @elseif(auth()->check() && (auth()->user()->userstatus == 2 ))
                
                <form action="{{ route('book-destroy', $book->id) }}" method="POST" class="d-inline" 
                        onsubmit="return confirm('Are you sure you want to delete {{ $book->title }}?')">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger mx-2" type="submit">Delete</button>
                </form>

                <a href="{{ route('book-edit', $book->id) }}" class="btn btn-secondary mx-2">Edit</a>
                <a href="{{ route('book-single', $book->id) }}" class="btn btn-primary mx-2">View</a>

                @endif

                    <!-- <form action="{{ route('book-destroy', $book->id) }}" method="POST" class="d-inline" 
                        onsubmit="return confirm('Are you sure you want to delete {{ $book->title }}?')">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger mx-2" type="submit">Delete</button>
                    </form>

                    <a href="{{ route('book-edit', $book->id) }}" class="btn btn-secondary mx-2">Edit</a>

                    

                    <a href="{{ route('book-single', $book->id) }}" class="btn btn-primary mx-2">View</a> -->
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>
<br>

        {{ $books->links() }} <!-- Pagination links -->


@endsection
