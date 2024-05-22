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


@if(auth()->check() && auth()->user()->userstatus == 1)

    <div style="text-align: center; margin-bottom: 20px;">
        <button id="rejectRequestBtn" class="btn btn-danger" style="margin-right: 10px;">Reject Request</button>
        <button id="rejectExtendBtn" class="btn btn-danger">Reject Extend</button>
    </div>

    <div id='req1'>
    <h4>Rejected Request</h4>
    @if($rejectedRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Rating</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rejectedRequests as $rejected)
                    <tr>
                        <td>{{ $rejected->book->title }}</td>
                        <td>
                            <div class="stars">
                                @if ($rejected->book->totalrating !== null)
                                    @php
                                        $roundedRating = floor($rejected->book->totalrating); // Round down the total rating
                                    @endphp
                                    @for ($i = 1; $i <= $roundedRating; $i++)
                                        <span class="fa fa-star checked"></span>
                                    @endfor
                                    @for ($i = $roundedRating + 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    ({{ number_format($rejected->book->totalrating, 1) }}) <!-- Display total rating to 2 decimal points -->
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    (no ratings)
                                @endif
                            </div>
                        </td>
                        <td>{{ $rejected->reason }}</td>
                        <td>
                            <a href="{{ route('book-single', ['id' => $rejected->book->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
    @else
        <p>No rejected requests</p>
    @endif
    </div>
<!-- ___________________ -->

    <div id='extend1' style="display: none;">
    <h4>Rejected Date Extend Request</h4>
    @if($rejectedExtendRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Rating</th>
                    <th>Extend Date</th>
                    <th>Reject Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rejectedExtendRequests as $rejectedExtendRequest)
                    <tr>
                        <td>{{ $rejectedExtendRequest->book->title }}</td>
                        <td>
                            <div class="stars">
                                @if ($rejectedExtendRequest->book->totalrating !== null)
                                    @php
                                        $roundedRating = floor($rejectedExtendRequest->book->totalrating); // Round down the total rating
                                    @endphp
                                    @for ($i = 1; $i <= $roundedRating; $i++)
                                        <span class="fa fa-star checked"></span>
                                    @endfor
                                    @for ($i = $roundedRating + 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    ({{ number_format($rejectedExtendRequest->book->totalrating, 1) }}) <!-- Display total rating to 2 decimal points -->
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    (no ratings)
                                @endif
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($rejectedExtendRequest->extenddate)->format('d-m-Y') }}</td>
                        <td>{{ $rejectedExtendRequest->rejectextend }}</td>
                        <td>
                            <a href="{{ route('book-single', ['id' => $rejectedExtendRequest->book->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
    @else
        <p>No rejected requests</p>
    @endif
    </div>
@endif

<style>
        .btn.active {
            background-color: #dc3545; /* Bright color for active button */
            color: white; /* Text color for active button */
        }

        .btn:not(.active) {
            background-color: #f8f9fa; /* Dull color for inactive button */
            color: #6c757d; /* Text color for inactive button */
        }

        .btn.active:hover {
            background-color: #c82333; /* Darker color for active button on hover */
        }

        .btn:not(.active):hover {
            background-color: #e9ecef; /* Lighter color for inactive button on hover */
        }
    </style>

<!-- ___________ADMIN___________ -->

@if(auth()->check() && auth()->user()->userstatus == 2)

    <div style="text-align: center; margin-bottom: 20px;">
        <button id="rejectRequestBtn" class="btn btn-danger" style="margin-right: 10px;">Reject Hisory</button>
        <button id="rejectExtendBtn" class="btn btn-danger">Reject Extend History</button>
    </div>

    <div id='req1'>

    <h4>Reject History</h4>
    @if($rejectedRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rejectedRequests as $rejected)
                    <tr>
                        <td>{{ $rejected->username }}</td>
                        <td>{{ $rejected->book->title }}</td>
                        <td>{{ $rejected->reason }}</td>
                        <td>
                            <a href="{{ route('book-single', ['id' => $rejected->book->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No rejected requests</p>
    @endif
    </div>

    <div id='extend1' style="display: none;">
    <h4>Rejected Date Extend Request</h4>
    @if($rejectedExtendRequests->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Extend Date</th>
                    <th>Reject Reason</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rejectedExtendRequests as $rejectedExtendRequest)
                    <tr>
                        <td>{{ $rejectedExtendRequest->username }}</td>
                        <td>{{ $rejectedExtendRequest->book->title }}</td>   
                        <td>{{ \Carbon\Carbon::parse($rejectedExtendRequest->extenddate)->format('d-m-Y') }}</td>
                        <td>{{ $rejectedExtendRequest->rejectextend }}</td>
                        <td>
                            <a href="{{ route('book-single', ['id' => $rejectedExtendRequest->book->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr> 
                @endforeach
            </tbody>
        </table>
    @else
        <p>No rejected requests</p>
    @endif
    </div>
@endif

<script>
        // Trigger click event for "Reject Request" button
        window.onload = function() {
            document.getElementById("rejectRequestBtn").click();
        };

        // Add event listener for "Reject Request" button
        document.getElementById("rejectRequestBtn").addEventListener("click", function() {
            document.getElementById("req1").style.display = "block";
            document.getElementById("extend1").style.display = "none";
        });

        // Add event listener for "Reject Extend" button
        document.getElementById("rejectExtendBtn").addEventListener("click", function() {
            document.getElementById("req1").style.display = "none";
            document.getElementById("extend1").style.display = "block";
        });
</script>

@endsection
