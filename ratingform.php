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

    $chk = $conn->query("SELECT * FROM venue_booking WHERE venue_id = '{$venuid}' AND email = '{$email}' AND id = '{$bookid}'");

    if ($chk->num_rows <= 0) {
        throw new Exception("Invalid Request");
    }
    $booking = $chk->fetch_array();

    $venue = $conn->query("SELECT * FROM venue WHERE id = '{$venuid}'")->fetch_array();
} catch (Exception $e) {
    header('location:index.php?page=home');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cleanliness = $_POST['cleanliness'];
    $ambience = $_POST['ambience'];
    $facilities = $_POST['facilities'];
    $services = $_POST['services'];
    $comment = $_POST['comment'] ?? '';

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .rating-container input {
            display: none;
        }

        .rating-container label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
        }

        .rating-container input:checked~label {
            color: #ffcc00;
        }

        .rating-container label:hover,
        .rating-container label:hover~label {
            color: #ffcc00;
        }

        .carousel-inner img {
            height: 300px;
            object-fit: cover;
        }

        .invalid-feedback {
            display: block;
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row">
            <!-- Venue Information Section -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div id="imagesCarousel_<?php echo $venue['id'] ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $images = array();
                            $fpath = 'admin/assets/uploads/venue_' . $venue['id'];
                            $images = scandir($fpath);
                            $i = 1;
                            foreach ($images as $v):
                                if (!in_array($v, array('.', '..'))):
                                    $active = $i == 1 ? 'active' : '';
                            ?>
                                    <div class="carousel-item <?php echo $active ?>">
                                        <img src="<?php echo $fpath . '/' . $v ?>" class="d-block w-100" alt="Venue Image">
                                    </div>
                            <?php
                                    $i++;
                                endif;
                            endforeach;
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imagesCarousel_<?php echo $venue['id'] ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imagesCarousel_<?php echo $venue['id'] ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $venue['venue']; ?></h5>
                        <p><strong>Location:</strong> <?php echo $venue['address']; ?></p>
                        <p><strong>Rating by:</strong> <?php echo $booking['name']; ?> <i>[<?php echo $booking['email']; ?>]</i></p>
                    </div>
                </div>
            </div>

            <!-- Rating Form Section -->
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0 text-center">Rate the Venue</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" id="rating-form">
                            <input type="hidden" name="bookId" value="<?= $bookid ?>">
                            <input type="hidden" name="email" value="<?= $email ?>">
                            <input type="hidden" name="venueId" value="<?= $venuid ?>">

                            <?php
                            $categories = ['cleanliness', 'ambience', 'facilities', 'services'];
                            foreach ($categories as $category): ?>
                                <div class="mb-3">
                                    <label for="<?= $category ?>" class="form-label"><?= ucfirst($category) ?></label>
                                    <div class="rating-container">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" id="<?= $category . $i ?>" name="<?= $category ?>" value="<?= $i ?>">
                                            <label for="<?= $category . $i ?>">&#9733;</label>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="invalid-feedback <?= $category ?> d-none">Please rate <?= $category ?>.</div>
                                </div>
                            <?php endforeach; ?>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Additional Comments</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Your feedback..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Submit Rating</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('rating-form').addEventListener('submit', function(event) {
            let isValid = true;
            const categories = ['cleanliness', 'ambience', 'facilities', 'services'];

            categories.forEach(function(category) {
                const radioButtons = document.getElementsByName(category);
                const invalidFeedback = document.querySelector(`.${category}`);
                const selected = Array.from(radioButtons).some(radio => radio.checked);

                if (!selected) {
                    isValid = false;
                    invalidFeedback.classList.remove('d-none');
                } else {
                    invalidFeedback.classList.add('d-none');
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>