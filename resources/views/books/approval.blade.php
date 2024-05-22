@extends('layout.privatebook')

@section('content')

<h3>Book Approval</h3>

@if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
@endif

@if($borrows->isEmpty())
    <p>No borrow requests found.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Book Title</th>
                <th>Request Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrows as $borrow)
                <tr>
                    <td>{{ $borrow->username }}</td>
                    <td>{{ $borrow->useremail }}</td>
                    <td>{{ $borrow->book->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($borrow->created_at)->format('d-m-Y') }}</td>
                    <td>
                        <a href="{{ route('book-single', ['id' => $borrow->book->id]) }}" class="btn btn-primary">View</a>
                        <form action="{{ route('approve-borrow', ['id' => $borrow->id]) }}" method="post" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>

                        <button class="btn btn-danger reject-btn" data-borrow-id="{{ $borrow->id }}" onclick="showRejectReasonInput(this)">Reject</button>
                        
                        <form id="rejectForm_{{ $borrow->id }}" class="reject-form" action="{{ route('reject-borrow', ['id' => $borrow->id]) }}" method="post" style="display: none;">
                            @csrf
                            <br>
                            <div class="input-group mb-3">
                                <input id="reasonInput_{{ $borrow->id }}" type="text" name="reason" class="form-control" placeholder="Enter reason" required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </div>
                            </div>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
    function showRejectReasonInput(button) {
        // Hide all other reject forms
        $('.reject-form').hide();
        // Show the reject form for the clicked borrow
        var borrowId = button.getAttribute('data-borrow-id');
        $('#rejectForm_' + borrowId).show();
    }
</script>


@endsection
