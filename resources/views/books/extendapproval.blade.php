@extends('layout.privatebook')

@section('content')

<h3>Extend Approval</h3>

@if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
@endif

@if($extends->isEmpty())
    <p>No extend requests found.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Book Title</th>
                <th>Request Date</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($extends as $extend)
                <tr> 
                    <td>{{ $extend->username }}</td>
                    <td>{{ $extend->useremail }}</td>
                    <td>{{ $extend->book->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($extend->extenddate)->format('d-m-Y') }}</td>
                    <td>{{ $extend->reasonextend }}</td>
                    <td>
                        <a href="{{ route('book-single', ['id' => $extend->book->id]) }}" class="btn btn-primary">View</a>
                        <form action="{{ route('extend-approval', ['id' => $extend->id]) }}" method="post" style="display: inline;">

                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>

                        <button class="btn btn-danger reject-btn" data-borrow-id="{{ $extend->id }}" onclick="showRejectReasonInput(this)">Reject</button>
                        
                        <form id="rejectForm_{{ $extend->id }}" class="reject-form" action="{{ route('reject-extend', ['id' => $extend->id]) }}" method="post" style="display: none;">
                            @csrf
                            <br>
                            <div class="input-group mb-3">
                                <input id="reasonInput_{{ $extend->id }}" type="text" name="rejectreason" class="form-control" placeholder="Enter reason" required>
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
