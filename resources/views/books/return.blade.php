@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

@section('content')
<style>
    .status-available {
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    .status-booked {
        background-color: #FFBE33;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    .status-borrowed {
        background-color: #E70F0F;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    .stars {
        unicode-bidi: bidi-override;
        direction: rtl;
    }

    .stars input[type="radio"] {
        display: none;
    }

    .stars label {
        font-size: 2em;
        color: #ccc;
        padding: 0 0.1em;
        float: right;
        cursor: pointer;
    }

    .stars label:hover,
    .stars label:hover ~ label,
    .stars input[type="radio"]:checked ~ label {
        color: #f90;
    }

    .comment textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>

<div class="container mt-5">

    <h3>Return</h3>

    @if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col">
            <h2>Title: {{ $book->title }}</h2>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <!-- Display uploaded image -->
            @if($book->image)
            <img src="{{ asset('images/' . $book->image) }}" alt="Book Image" class="img-fluid">
            @else
            <img src="https://via.placeholder.com/400x600.png" alt="Placeholder" class="img-fluid">
            @endif
        </div>

        <div class="col-md-8">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><strong>ISBN</strong></td>
                        <td>{{ $book->isbn }}</td>
                    </tr>
                    <tr>
                        <td><strong>Location</strong></td>
                        <td>{{ $book->location }}</td>
                    </tr>
                    <tr>
                        <td><strong>Author</strong></td>
                        <td>{{ $book->author }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($book->status == 1)
                            <span class="status-available">Available</span>
                            @elseif($book->status == 2)
                            <span class="status-booked">Booked</span>
                            @elseif($book->status == 3)
                            <span class="status-borrowed">Borrowed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Genres</strong></td>
                        <td>
                            @if($book->genre1 != '-')
                            {{ $book->genre1 }}
                            @endif
                            @if($book->genre2 != '-')
                            {{ $book->genre2 }}
                            @endif
                            @if($book->genre3 != '-')
                            {{ $book->genre3 }}
                            @endif
                            @if($book->genre4 != '-')
                            {{ $book->genre4 }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Synopsis</strong></td>
                        <td>{{ nl2br($book->synopsis) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <form method="POST" action="{{ route('book-updaterate', ['id' => $book->id]) }}">
            @csrf
            <br>
            <div>
                <span style="float: left;">Rate this Book : </span><br>
                <div class="stars" style="float: left; margin-left: 0px;">
                    <input type="radio" id="rated-1" name="rating" value="5">
                    <label for="rated-1">★</label>
                    <input type="radio" id="rated-2" name="rating" value="4">
                    <label for="rated-2">★</label>
                    <input type="radio" id="rated-3" name="rating" value="3">
                    <label for="rated-3">★</label>
                    <input type="radio" id="rated-4" name="rating" value="2">
                    <label for="rated-4">★</label>
                    <input type="radio" id="rated-5" name="rating" value="1">
                    <label for="rated-5">★</label>
                </div>
            </div>
            <br style="clear:both;">
            <span>Comment about the Book : </span>
            <p class="comment">
                <textarea id="review" name="review" cols="45" rows="8"></textarea>
            </p>
            <br>
            <button type="submit" class="btn btn-primary">Return</button>
        </form>
    </div>


</div>
@endsection
