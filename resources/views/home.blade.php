@extends(auth()->check() && auth()->user()->userstatus == 2 ? 'layout.privatebook' : 'layout.publicbook')

@section('content')
<style>
    /* Custom Styles */
.alert {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
}

.logged-in-message {
    font-size: 18px;
    color: #333;
}

.welcome-heading {
    font-size: 36px;
    color: #007bff;
}

.site-name {
    font-weight: bold;
}
.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.legend-color {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
}

.legend-label {
    margin-bottom: 0;
}

</style>
@if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Books Available</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $booksAvailable }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Borrowed Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Books Booked</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $booksBooked }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Borrow Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Currently Borrow</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $currentlyBorrow }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Borrowed Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Books Borrowed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $booksBorrowed }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
        <!-- Status -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Return Status</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2 chart-container">
                        <?php if ($onTimeReturns == 0 && $lateReturns == 0): ?>
                            <div class="text-center">
                                <p class="text-muted mb-0" style="margin-top: 80px; font-size: 26px;">No books borrowed</p>
                            </div>
                        <?php else: ?>
                            <canvas id="returnStatusChart" style="display: block; width: 353px; height: 245px; margin: auto; margin-top: 0px;" width="353" height="245" class="chartjs-render-monitor"></canvas>
                        <?php endif; ?>

                        <div id="legend-container"></div>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"></span>
                    </div>
                </div>
            </div>
        </div>



        <!-- Recommended -->
        <div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Top Books</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-area">
                <div class="slider">
                    <div class="slides">
                        @foreach ($topBooks as $book)
                            <div class="slide">
                                <img src="{{ asset('images/' . $book->image) }}" alt="{{ $book->title }}">
                                <div class="details">
                                    <p><strong>Title:</strong> {{ $book->title }}</p>
                                    <div class="rating">
                                        <div class="stars">
                                            @for ($i = 0; $i < floor($book->totalrating); $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                            @if ($book->totalrating - floor($book->totalrating) >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @endif
                                        </div>
                                        <span>{{ $book->totalrating }}</span>
                                    </div>
                                    <p><strong>Genres:</strong> 
                                        @php
                                            $genres = array_filter([$book->genre1, $book->genre2, $book->genre3, $book->genre4], function($genre) {
                                                return $genre && $genre !== '-';
                                            });
                                        @endphp
                                        {{ implode(', ', $genres) }}
                                    </p>
                                    <a href="{{ route('book-single', $book->id) }}" class="btn btn-primary  custom-btn">View</a>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="prev" onclick="moveSlides(-1)">&#10094;</button>
                    <button class="next" onclick="moveSlides(1)">&#10095;</button>
                </div>
            </div>
        </div>
    </div>
</div>


</div>

<!-- <p>Title</p>
<br>
<p>rating</p>
<br>
<p>Genre</p> -->

<script>
    // Retrieve data from PHP variables
    var onTimeReturns = <?php echo $onTimeReturns; ?>;
    var lateReturns = <?php echo $lateReturns; ?>;

    <?php if ($onTimeReturns > 0 || $lateReturns > 0): ?>
        // Get context of the canvas element
        var ctx = document.getElementById('returnStatusChart').getContext('2d');

        // Create the donut chart
        var returnStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late'],
                datasets: [{
                    data: [onTimeReturns, lateReturns],
                    backgroundColor: ['#0000FF', '#28a745'],
                }]
            },
            options: {
                responsive: true,
                cutoutPercentage: 120, // Adjust the size of the hole in the middle
                legend: {
                    display: false // Hide default legend
                }
            }
        });

        // Update the legend to be interactive
        var legend = returnStatusChart.generateLegend();
        document.getElementById('legend-container').innerHTML = legend;
    <?php endif; ?>
</script>

<!-- Here -->

<style>
    .slider {
        position: relative;
        width: 100%;
        height: 300px; /* Adjust height as needed */
        overflow: hidden;
    }

    .slides {
        display: flex;
        transition: transform 0.5s ease-in-out;
    }

    .slide {
        display: flex;
        min-width: 100%;
        box-sizing: border-box;
    }

    .slide img {
        width: 50%; /* Make the image take half the width of the slide */
        height: auto; /* Maintain aspect ratio */
        max-height: 50%; /* Ensure it fits within the container */
        object-fit: contain; /* Ensure the whole image is visible */
    }

    .details {
        width: 50%;
        padding: 20px; /* Adjust padding as needed */
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #f8f9fc; /* Same background color as card body */
        font-family: Arial, sans-serif;
        margin-top: -300px;
    }
    .details p {
            margin: 5px 0;
            font-size: 20px; /* Adjust overall text size */
            color: #4e73df;
        }

        .details p.title {
            font-size: 18px; /* Adjust title text size */
            font-weight: bold;
        }

        .details p.rating {
            font-size: 16px; /* Adjust rating text size */
        }

        .details p.genres {
            font-size: 14px; /* Adjust genres text size */
        }

        .rating {
            display: flex;
            align-items: center;
        }

        .stars {
            color: #f39c12;
            margin-right: 10px;
        }

        .details .btn {
            margin-top: 10px;
            align-self: center; /* Center the button horizontally */
            margin-bottom: 50px;
        }

        .custom-btn {
            background-color: #4e73df; /* Custom background color */
            border-color: #4e73df; /* Custom border color */
            color: white; /* Custom text color */
            font-size: 14px; /* Custom text size */
            padding: 10px 20px; /* Custom padding */
            border-radius: 5px; /* Custom border radius */
        }

        .custom-btn:hover {
            background-color: #2e59d9; /* Custom hover background color */
            border-color: #2653d4; /* Custom hover border color */
        }

    .prev, .next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        user-select: none;
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }
</style>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    let currentSlide = 0;
    let autoSlideInterval;

    function showSlide(index) {
        const slides = document.querySelectorAll('.slide');
        if (index >= slides.length) {
            currentSlide = 0;
        } else if (index < 0) {
            currentSlide = slides.length - 1;
        } else {
            currentSlide = index;
        }

        const offset = -currentSlide * 100;
        document.querySelector('.slides').style.transform = `translateX(${offset}%)`;
    }

    function moveSlides(step) {
        showSlide(currentSlide + step);
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            moveSlides(1);
        }, 3000); // Change slide every 3 seconds
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Initialize the slider
    showSlide(currentSlide);
    startAutoSlide();

    // Optional: Stop auto sliding when user interacts with buttons
    document.querySelector('.prev').addEventListener('click', () => {
        stopAutoSlide();
        moveSlides(-1);
        startAutoSlide();
    });

    document.querySelector('.next').addEventListener('click', () => {
        stopAutoSlide();
        moveSlides(1);
        startAutoSlide();
    });
</script>











    
@endsection

<!-- <div><img src="https://cdn.pixabay.com/photo/2016/11/19/18/57/godafoss-1840758__340.jpg" alt="nature"></div>
                    <div><img src="https://cdn.pixabay.com/photo/2018/01/12/14/24/night-3078326__340.jpg" alt="nature"></div>
                    <div><img src="https://cdn.pixabay.com/photo/2013/11/28/10/03/river-219972__340.jpg" alt="nature"></div> -->









