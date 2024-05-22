@extends('layout.publicbook')

@section('content')

<style>
    .fa-star.checked {
    color: gold; /* Yellow color */
}

/* Define the default style for the unchecked (empty) stars */
.fa-star {
    color: #ccc; /* Light gray color */
}

</style>

<h4>Pending Request</h4>

@if($pendingRequests->count() > 0)

<table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Rating</th>
                <th>Request Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingRequests as $pending)
                <tr>
                    <td>{{ $pending->book->title }}</td>
                    <td>
                            <div class="stars">
                                @if ($pending->book->totalrating !== null)
                                    @php
                                        $roundedRating = floor($pending->book->totalrating); // Round down the total rating
                                    @endphp
                                    @for ($i = 1; $i <= $roundedRating; $i++)
                                        <span class="fa fa-star checked"></span>
                                    @endfor
                                    @for ($i = $roundedRating + 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    ({{ number_format($pending->book->totalrating, 1) }}) <!-- Display total rating to 2 decimal points -->
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    (no ratings)
                                @endif
                            </div>
                        </td>
                    <td>{{ \Carbon\Carbon::parse($pending->created_at)->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('book-single', ['id' => $pending->book->id]) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p>No pending requests</p>
@endif



@endsection
