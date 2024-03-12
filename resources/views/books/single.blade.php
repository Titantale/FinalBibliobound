@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

@section('content')
<style>
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

<div class="container mt-5">

    <div class="row">
        <div class="col">
            <a class="btn btn-primary mb-3" href="{{ route('book-listing') }}">BACK</a>
        </div>
    </div>
    
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
</div>
@endsection
