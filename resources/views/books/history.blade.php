@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

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

<h3>Borrowed History</h3>

@if(auth()->check() && auth()->user()->userstatus == 2)
    <!-- Display filter inputs only for userstatus 2 -->
    <form method="GET" action="{{ route('book-history') }}">
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Filter by name" value="{{ request('name') }}">
            </div>
            <div class="col">
                <select name="status" class="form-control">
                    <option value="">Filter by status</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                    <option value="on-time" {{ request('status') == 'on-time' ? 'selected' : '' }}>On Time</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
@endif

@if($requesthistory->count() > 0)
    <table class="table">
        <thead>
            <tr>
                @if(auth()->check() && auth()->user()->userstatus == 2)
                    <th>Name</th> <!-- Display Name column if userstatus is 2 -->
                @endif
                <th>Title</th>
                <th>Rating Given</th> 
                <th>Comment</th>
                <th>Date Returned</th> 
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requesthistory as $history)
                <tr>
                    @if(auth()->check() && auth()->user()->userstatus == 2)
                        <td>{{ $history->user->name }}</td> <!-- Display user's name if userstatus is 2 -->
                    @endif
                    <td>{{ $history->book->title }}</td>
                    <td>
                        @for ($i = 1; $i <=  $history->rate; $i++)
                            <span class="fa fa-star checked"></span>
                        @endfor
                        @for ($i = $history->rate + 1; $i <= 5; $i++)
                            <span class="fa fa-star"></span>
                        @endfor
                        <!-- @for ($i = 1; $i <= $history->rate; $i++)
                            <span class="fa fa-star checked"></span>
                        @endfor  -->
                    </td>
                    <td>{{ $history->review }}</td>
                    <td>{{ $history->actualreturndate }}</td> <!-- Display actual return date -->
                    <td>
                        @if ($history->actualreturndate < $history->returndate)
                            <span class="text-success"><strong>On Time</strong></span>
                        @else
                            <span class="text-danger"><strong>Late</strong></span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('book-single', ['id' => $history->book->id]) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No history requests</p>
@endif

@endsection
