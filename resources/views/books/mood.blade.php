@extends('layout.publicbook')

@section('content')

<h3>Mood Based Recommendation system</h3>

@if(session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif


<style>
    .testing {
        /* Add margin-right to move content to the left */
        margin-right: 50px;
    }
    .content-container {
        height: 80vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        /* Add margin-left to adjust the content position */
        margin-left: -100px; /* Adjust the value as needed */
    }
    input[type="range"] {
        width: 50vw; /* Adjust the width as per your preference */
        height: 6px;
        background-color: white;
        border: none;
        border-radius: 6px;
        outline: none;
        margin-top: 20px; /* Adjust the margin as per your preference */
    }
    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 30px;
        width: 30px;
        background-color: white;
        border-radius: 50%;
        box-shadow: 10px 15px 10px rgba(0, 0, 0, 0.2);
    }
    /* Remove material-icons class */
    .emoji {
        font-size: 130px;
        color: #3fcad8;
    }
    .emoji-description {
        font-size: 20px;
        margin-top: 10px;
        text-align: center;
    }
</style>

<div class="testing">
    <div class="content-container">
        <!-- Remove material-icons class and directly use the Font Awesome class -->
        <i class="emoji" id="emoji">
            <!-- Place the initial emoji code here -->
            <i class="far fa-grin"></i>
        </i>
        <!-- Add a container for emoji description -->
        <div class="emoji-description" id="emojiDescription">Romantic</div>
        <input type="range" min="0" max="4" value="2" id="slider">
        
        <!-- Add the form for submitting the mood selection -->
        <br>
        <form action="{{ route('recommend-book') }}" method="POST">
        @csrf
        <input type="hidden" name="mood" id="selectedMood" value="2"> <!-- Assuming initial mood value is 2 -->
        <button type="submit" class="btn btn-primary">Recommend Book</button>
    </form>

    </div>
</div>



<script>
    var slider = document.getElementById("slider");
    var emoji = document.getElementById("emoji");
    var emojiDescription = document.getElementById("emojiDescription");
    var emoticons = [
        "far fa-grin-hearts",      // Romantic
        "far fa-grimace",          // Suspenseful
        "far fa-grin",             // Fantastical
        "far fa-frown",            // Melancholic
        "far fa-grin-squint"       // Light-hearted
    ];
    var descriptions = [
        "Romantic",
        "Suspenseful",
        "Fantastical",
        "Melancholic",
        "Light-hearted"
    ];

    // Initialize emoji display
    emoji.innerHTML = `<i class="${emoticons[slider.value]}"></i>`;
    emojiDescription.textContent = descriptions[slider.value];

    // Update emoji and description when slider value changes
    slider.oninput = function() {
        emoji.innerHTML = `<i class="${emoticons[slider.value]}"></i>`;
        emojiDescription.textContent = descriptions[slider.value];

        // Update the value of the hidden input field
        document.getElementById("selectedMood").value = slider.value;
    }
</script>


@endsection

<!-- <form id="recommendForm">
            @csrf
            <input type="hidden" name="mood" id="selectedMood" value="2"> 
            <button type="button" class="btn btn-primary" id="recommendButton">Recommend Book</button>
</form>

<script>
slider.oninput = function() {
        emoji.innerHTML = `<i class="${emoticons[slider.value]}"></i>`;
        emojiDescription.textContent = descriptions[slider.value];

        
        selectedMood.value = slider.value;
    }

    
    document.getElementById("recommendButton").addEventListener("click", function() {
        var selectedMoodValue = document.getElementById("selectedMood").value;
        selectedMoodDisplay.textContent = "Selected Mood: " + (parseInt(selectedMoodValue)); // Add 1 to make it 1-5 instead of 0-4
    }); -->
