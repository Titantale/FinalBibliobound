@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

@section('content')

@if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p class="alert alert-danger">{{ session('error') }}</p>
@endif

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
    <h4>Currently Borrowed Books</h4>
    <br>

    @if($borrowedBooks->count() > 0)

    <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Rating</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Extend Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowedBooks as $borrow)
                    <tr>
                        <td>{{ $borrow->book->title }}</td>
                        <!-- Stars -->
                        <td>
                            <div class="stars">
                                @if ($borrow->book->totalrating !== null)
                                    @php
                                        $roundedRating = floor($borrow->book->totalrating); // Round down the total rating
                                    @endphp
                                    @for ($i = 1; $i <= $roundedRating; $i++)
                                        <span class="fa fa-star checked"></span>
                                    @endfor
                                    @for ($i = $roundedRating + 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    ({{ number_format($borrow->book->totalrating, 1) }}) <!-- Display total rating to 2 decimal points -->
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="fa fa-star"></span>
                                    @endfor
                                    <br>
                                    (no ratings)
                                @endif
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($borrow->returndate)->format('d-m-Y') }}</td>
                        <td>
                            @if($borrow->extendreq == 1)
                                <span class="text-info"><strong>Waiting Extend</strong></span><br>
                                <span class="text-info"><strong>Request</strong></span>
                            @elseif(\Carbon\Carbon::parse($borrow->returndate)->isPast())
                                <span class="text-danger"><strong>Late</strong></span>
                            @else
                                <span class="text-success"><strong>On Time</strong></span>
                            @endif
                        </td>

                        <td>
                        <button class="btn btn-primary" id="extendButton<?php echo $borrow->id; ?>" onclick="toggleExtendForm(<?php echo $borrow->id; ?>, <?php echo $borrow->extendreq; ?>)">Extend</button>
                            <div id="extendForm<?php echo $borrow->id; ?>" style="display: none; margin-top: 10px;">
                                <form method="POST" action="{{ route('request-extension', ['id' => $borrow->id]) }}">
                                    <?php echo csrf_field(); ?>
                                    <div style="margin-bottom: 10px;">
                                        <label for="new_return_date">New Return Date:</label>
                                        <input type="date" id="new_return_date" name="new_return_date" min="<?php echo \Carbon\Carbon::now()->addDay()->format('Y-m-d'); ?>" required>
                                    </div>
                                    <div style="margin-bottom: 10px;">
                                        <label for="reasonextend">Reason:</label>
                                        <input type="text" id="reasonextend" name="reasonextend" placeholder="Enter reason" style="width: 300px;" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Extend</button>
                                </form>
                            </div>
                        </td>


                        <td>
                            <a href="{{ route('book-single', ['id' => $borrow->book->id]) }}" class="btn btn-primary">View</a>
                            <a href="{{ route('book-rateandreview', ['id' => $borrow->book->id]) }}" class="btn btn-secondary">Return</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No borrowed books</p>
    @endif
@endif

<script>
    function toggleExtendForm(borrowId, extendReq) {
    var extendButton = document.getElementById('extendButton' + borrowId);
    var extendForm = document.getElementById('extendForm' + borrowId);
    
    // Check if extendReq is 1 or 2
    if (extendReq == 1) {
        alert('You have already submitted a request to extend the return date.');
    } else if (extendReq == 2) {
        alert('You can only extend the return date once.');
    } else {
        // Hide the extend button and show the extend form
        extendButton.style.display = 'none';
        extendForm.style.display = 'block';
    }
}

</script>


<!-------------- ADMIN SECTION --------------->


@if(auth()->check() && auth()->user()->userstatus == 2)
    <div class="row">
        <div class="col">
            <h4>Admin Currently Borrowed Books</h4>
        </div>
        <div class="col-auto">
            <form action="{{ route('sendallnotification') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger mb-3" style="margin-right: 20px;">Notify All Late Users</button>
            </form>
        </div>
    </div>

    <br>

    <!-- Filter -->
    <form method="GET" action="{{ route('book-borrowed') }}">
        <div class="row mb-3">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Filter by name" value="{{ request('name') }}">
            </div>
            <div class="col">
                <select name="status" class="form-control">
                    <option value="">Filter by status</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                    <option value="on-time" {{ request('status') == 'on-time' ? 'selected' : '' }}>On Time</option>
                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting Extend Request</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if($borrowedBooks->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Notify Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowedBooks as $borrow)
                    <tr>
                        <td>{{ $borrow->username }}</td>
                        <td>{{ $borrow->book->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($borrow->returndate)->format('d-m-Y') }}</td>
                        <td>
                            @if($borrow->extendreq == 1)
                                <span class="text-info"><strong>Waiting Extend Request</strong></span><br>
                            
                            @elseif(\Carbon\Carbon::parse($borrow->returndate)->isPast())
                                <span class="text-danger"><strong>Late</strong></span>
                            @else
                                <span class="text-success"><strong>On Time</strong></span>
                            @endif
                        </td>
                        <td>{{ $borrow->notification_count }}</td>
                        <td>
                            @if(\Carbon\Carbon::parse($borrow->returndate)->isPast())

                                <button type="button" class="btn btn-secondary notify-btn" data-id="{{ $borrow->id }}" style="margin-bottom: 5px;">Notify</button>

                                <form action="{{ route('send-notification', ['id' => $borrow->id]) }}" method="POST" class="notify-actions" style="display: none; margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" style="margin-bottom: 5px;">Default</button>
                                </form>

                                <button type="button" class="btn btn-info custom-popup-btn notify-actions" data-id="{{ $borrow->id }}" style="display: none; margin-bottom: 5px;">Custom</button>
                                
                            @endif
                            <a href="{{ route('book-single', ['id' => $borrow->book->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No borrowed books</p>
    @endif
@endif

<div id="customPopup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close" style="position: absolute; top: 5px; right: 5px;">&times;</span>
        <form id="customNotificationForm" action="{{ route('custom-notification') }}" method="POST">
            @csrf
            <input type="hidden" name="borrow_id" id="borrowId">
            <textarea name="message" id="popupTextbox" placeholder="Enter your custom message here..."></textarea>
            <button type="submit" class="btn btn-primary">Send Custom Notification</button>
        </form>
    </div>
</div>


<style>
/* General body styles */


/* Button styles */
button {
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

/* Pop-up container */
.popup {
    display: none; /* Set to none by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
}


/* Pop-up content */
.popup-content {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    width: 90%;
    max-width: 500px;
    text-align: center;
    position: relative;
    max-height: 80%; /* Optionally limit the maximum height */
    overflow-y: auto; /* Add scrollbar if content overflows */
}

/* Close button */
.close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 30px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Textarea */
textarea {
    width: 100%;
    height: 150px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
    font-size: 16px;
    margin-bottom: 20px;
}

/* Submit button */
button[type="submit"] {
    background-color: #007bff;
    color: white;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const popup = document.getElementById('customPopup');
    const closeBtn = document.getElementsByClassName('close')[0];
    const customBtns = document.getElementsByClassName('custom-popup-btn');
    const notifyBtns = document.getElementsByClassName('notify-btn');
    const borrowIdInput = document.getElementById('borrowId');

    Array.from(notifyBtns).forEach(button => {
        button.onclick = function() {
            const notifyActions = this.parentElement.querySelectorAll('.notify-actions');
            notifyActions.forEach(action => action.style.display = 'inline-block');
            this.style.display = 'none';
        }
    });

    Array.from(customBtns).forEach(button => {
        button.onclick = function() {
            borrowIdInput.value = this.getAttribute('data-id');
            popup.style.display = 'flex';
        }
    });

    closeBtn.onclick = function() {
        popup.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == popup) {
            popup.style.display = 'none';
        }
    }
});
</script>






@endsection
