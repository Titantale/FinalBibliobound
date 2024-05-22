@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')


@section('content')


<h2 class="mb-4">Book Recommended</h2>


<style>
    .centered-image {
        display: block;
        margin: auto;
        padding-top: 10px;
        width: 250px;
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
    background-color: #FFBE33; /* Yellow color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}
    .status-borrowed {
    background-color: #E70F0F; /* Red color */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-weight: bold;
}

.fa-star.checked {
    color: gold; /* Yellow color */
}

/* Define the default style for the unchecked (empty) stars */
.fa-star {
    color: #ccc; /* Light gray color */
}


</style>

<div class="row">
    @if(session('success'))
        <div class="col-md-12">
            <p class="alert alert-success">{{ session('success') }}</p>
        </div>
    @endif

    @foreach ($books as $book)
    <div class="col-md-4">
        <div class="card">
            <!-- Display Image -->
            <img src="{{ asset('images/'.$book->image) }}" width="300" height="300" alt="" class="img-fluid centered-image">

            <div class="card-body text-center">
                <h4 class="card-title my-title">{{ $book->title }}</h4>

                <div class="stars">
                    @if ($book->totalrating !== null)
                        <div class="stars">
                            @php
                                $roundedRating = floor($book->totalrating); // Round down the total rating
                            @endphp
                            @for ($i = 1; $i <= $roundedRating; $i++)
                                <span class="fa fa-star checked"></span>
                            @endfor
                            @for ($i = $roundedRating + 1; $i <= 5; $i++)
                                <span class="fa fa-star"></span>
                            @endfor
                            <br>
                            ({{ number_format($book->totalrating, 1) }}) <!-- Display total rating to 2 decimal points -->
                        </div>
                    @else
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="fa fa-star"></span>
                            @endfor
                            <br>
                            (no ratings)
                    @endif
                </div>

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
        <br>
    </div>
    @endforeach
    
</div>
<br>

<div class="row">
    <div class="col-md-12 text-center">
        <a href="{{ route('next_recommendation') }}" class="btn btn-primary">Next</a>
    </div>
</div>





@endsection
