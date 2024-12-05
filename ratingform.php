<?php
include 'admin/db_connect.php';
include_once 'admin/admin_class.php';
$crud = new Action();

try {
    $encryptedData = isset($_GET['rate']) ? $_GET['rate'] : throw new Exception("Invalid Request");
    
    $testData = json_encode(['bookid' => 13, 'email' => 'test@test.com', 'venuid' => 1]);
    
    $data = $crud->decryptData($encryptedData);
    if (!$data) {
        throw new Exception("Invalid Request");
    }

    $data = json_decode($data, true);
    extract($data);

    $chk = $conn->query("SELECT * FROM venue_booking where venue_id = '{$venuid}' and email = '{$email}' and id = '{$bookid}' ");

    if ($chk->num_rows <= 0) {
        throw new Exception("Invalid Request");
    }
    $booking = $chk->fetch_array();

    $venue = $conn->query("SELECT * FROM venue where id = '{$venuid}' ")->fetch_array();
    
} catch (Exception $e) {
    header('location:index.php?page=home');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the ratings from the form

    $cleanliness = $_POST['cleanliness'];
    $ambience = $_POST['ambience'];
    $facilities = $_POST['facilities'];
    $services = $_POST['services'];
    $comment = $_POST['comment'] ??  '';

    $bookId = $_POST['bookId'];
    $emailId = $_POST['email'];
    $venueId = $_POST['venueId'];

    $crud->insertRating($cleanliness, $ambience, $facilities, $services, $bookId, $emailId, $venueId, $comment);

    echo "<div class='alert alert-success'>Thank you for your feedback!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Venue</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .rating-container input {
            display: none;
        }
        .rating-container label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
        }
        .rating-container input:checked ~ label {
            color: #ffcc00;
        }
        .rating-container label:hover,
        .rating-container label:hover ~ label {
            color: #ffcc00;
        }
        .venue-info {
            padding-left: 20px;
        }
        .venue-image {
            width: 100%;
            height: auto;
        }
        .carousel-inner img {
            height: 250px;
            object-fit: cover;
        }

        .invalid-feedback {
            display: block;
            color: red;
            font-size: 0.9rem;
        }

        .is-invalid {
            border-color: red;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Left Side: Venue Image Carousel and Info -->
        <div class="col-md-6">
            <!-- Venue Card with Image Carousel -->
            <div class="card venue-list">
                <div id="imagesCarousel_<?php echo $venue['id'] ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $images = array();
                        $fpath = 'admin/assets/uploads/venue_' . $venue['id'];
                        $images = scandir($fpath);
                        $i = 1;
                        foreach ($images as $k => $v):
                            if (!in_array($v, array('.', '..'))):
                                $active = $i == 1 ? 'active' : '';
                        ?>
                                <div class="carousel-item <?php echo $active ?>">
                                    <img class="d-block w-100" src="<?php echo $fpath . '/' . $v ?>" alt="">
                                </div>
                        <?php
                                $i++;
                            else:
                                unset($images[$v]);
                            endif;
                        endforeach;
                        ?>
                        <!-- Carousel Controls -->
                        <a class="carousel-control-prev" href="#imagesCarousel_<?php echo $venue['id'] ?>" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#imagesCarousel_<?php echo $venue['id'] ?>" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <!-- Venue Info -->
                <div class="card-body venue-info">
                    <h5 class="card-title"><?php echo $venue['venue']; ?></h5>
                    <p><strong>Location:</strong> <?php echo $venue['address']; ?></p>
                    <p><strong>Rating by:</strong> <?php echo $booking['name']; ?> <i>[<?php echo $booking['email']; ?>]</i></p>
                </div>
            </div>
        </div>

        <!-- Right Side: Rating Form -->
        <div class="col-md-6">
            <h3>Rate the Venue</h3>
            <form method="post" id="rating-form">
                <input type="hidden" name="bookId" value="<?= $bookid ?>">
                <input type="hidden" name="email" value="<?= $email ?>">
                <input type="hidden" name="venueId" value="<?= $venuid ?>">
                <div class="form-group">
                    <label for="cleanliness">Cleanliness</label>
                    <div class="rating-container">
                        <input type="radio" id="cleanliness1" name="cleanliness" value="1">
                        <label for="cleanliness1">&#9733;</label>
                        <input type="radio" id="cleanliness2" name="cleanliness" value="2">
                        <label for="cleanliness2">&#9733;</label>
                        <input type="radio" id="cleanliness3" name="cleanliness" value="3">
                        <label for="cleanliness3">&#9733;</label>
                        <input type="radio" id="cleanliness4" name="cleanliness" value="4">
                        <label for="cleanliness4">&#9733;</label>
                        <input type="radio" id="cleanliness5" name="cleanliness" value="5">
                        <label for="cleanliness5">&#9733;</label>
                    </div>
                    <div class="cleanliness invalid-feedback d-none">Please rate cleanliness.</div>
                </div>

                <!-- Ambience Rating -->
                <div class="form-group">
                    <label for="ambience">Ambience</label>
                    <div class="rating-container">
                        <input type="radio" id="ambience1" name="ambience" value="1">
                        <label for="ambience1">&#9733;</label>
                        <input type="radio" id="ambience2" name="ambience" value="2">
                        <label for="ambience2">&#9733;</label>
                        <input type="radio" id="ambience3" name="ambience" value="3">
                        <label for="ambience3">&#9733;</label>
                        <input type="radio" id="ambience4" name="ambience" value="4">
                        <label for="ambience4">&#9733;</label>
                        <input type="radio" id="ambience5" name="ambience" value="5">
                        <label for="ambience5">&#9733;</label>
                    </div>
                    <div class="ambience invalid-feedback d-none">Please rate ambience.</div>
                </div>

                <!-- Facilities Rating -->
                <div class="form-group">
                    <label for="facilities">Facilities</label>
                    <div class="rating-container">
                        <input type="radio" id="facilities1" name="facilities" value="1">
                        <label for="facilities1">&#9733;</label>
                        <input type="radio" id="facilities2" name="facilities" value="2">
                        <label for="facilities2">&#9733;</label>
                        <input type="radio" id="facilities3" name="facilities" value="3">
                        <label for="facilities3">&#9733;</label>
                        <input type="radio" id="facilities4" name="facilities" value="4">
                        <label for="facilities4">&#9733;</label>
                        <input type="radio" id="facilities5" name="facilities" value="5">
                        <label for="facilities5">&#9733;</label>
                    </div>
                    <div class="facilities invalid-feedback d-none">Please rate facilities.</div>
                </div>

                <!-- Services Rating -->
                <div class="form-group">
                    <label for="services">Services</label>
                    <div class="rating-container">
                        <input type="radio" id="services1" name="services" value="1">
                        <label for="services1">&#9733;</label>
                        <input type="radio" id="services2" name="services" value="2">
                        <label for="services2">&#9733;</label>
                        <input type="radio" id="services3" name="services" value="3">
                        <label for="services3">&#9733;</label>
                        <input type="radio" id="services4" name="services" value="4">
                        <label for="services4">&#9733;</label>
                        <input type="radio" id="services5" name="services" value="5">
                        <label for="services5">&#9733;</label>
                    </div>
                    <div class="services invalid-feedback d-none">Please rate services.</div>
                </div>

                <!-- Comments Section -->
                <div class="form-group">
                    <label for="comment">Additional Comments</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Your feedback..."></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('rating-form').addEventListener('submit', function(event) {
        let isValid = true;

        // Clear previous error styles
        const ratingGroups = ['cleanliness', 'ambience', 'facilities', 'services'];
        ratingGroups.forEach(function(group) {
            const radioButtons = document.getElementsByName(group);
            const invalidFeedback = document.querySelector(`[for="${group}"]`).nextElementSibling;
            const selected = Array.from(radioButtons).some(radio => radio.checked);
            var selectedDiv = document.getElementsByClassName(group);

            if (!selected) {
                isValid = false;
                selectedDiv[0].classList.remove('d-none');
            } else {
                selectedDiv[0].classList.add('d-none');
            }
        });

        if (!isValid) {
            event.preventDefault();
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
