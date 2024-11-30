<?php
include 'admin/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Rating Form</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Stars -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        /* Global styling */
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
        }

        h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        /* Styling for blue, bold rating type labels */
        .form-label {
            font-weight: bold;
            color: #3498db;
            /* Blue color */
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        /* Star Rating Container */
        .rate {
            display: flex;
            justify-content: flex-start;
            /* Align stars to the left */
            align-items: center;
            margin-bottom: 20px;
        }

        .rate input {
            display: none;
        }

        .rate label {
            font-size: 36px;
            color: #ccc;
            margin: 0 5px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rate label:hover,
        .rate label:hover~label {
            color: #deb217;
        }

        .rate input:checked~label {
            color: #ffc700;
        }

        .rate input:checked+label:hover,
        .rate input:checked+label:hover~label,
        .rate input:checked~label:hover,
        .rate input:checked~label:hover~label,
        .rate label:hover~input:checked~label {
            color: #c59b08;
        }

        .rate {
            margin-bottom: 20px;
        }

        /* Submit Button Styling */
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
            padding: 10px 20px;
            font-size: 1.1rem;
            width: 100%;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        /* Tooltip for the rating labels */
        .rate label[for^="star"] {
            position: relative;
        }

        .rate label[for^="star"]:after {
            content: attr(title);
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #333;
            background-color: #f0f0f0;
            padding: 5px;
            border-radius: 4px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
        }

        .rate label[for^="star"]:hover::after {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>

<body>

    <!-- Venue Rating Form -->
    <div class="container">
        <h2>Rate the Venue</h2>

        <form action="/submit_rating" method="POST">
            <!-- Cleanliness Rating -->
            <div class="mb-4">
                <label for="cleanliness" class="form-label">Cleanliness</label>
                <div class="rate">
                    <input type="radio" id="star5-cleanliness" name="cleanliness" value="5" />
                    <label for="star5-cleanliness" title="Excellent">★</label>
                    <input type="radio" id="star4-cleanliness" name="cleanliness" value="4" />
                    <label for="star4-cleanliness" title="Good">★</label>
                    <input type="radio" id="star3-cleanliness" name="cleanliness" value="3" />
                    <label for="star3-cleanliness" title="Average">★</label>
                    <input type="radio" id="star2-cleanliness" name="cleanliness" value="2" />
                    <label for="star2-cleanliness" title="Below Average">★</label>
                    <input type="radio" id="star1-cleanliness" name="cleanliness" value="1" />
                    <label for="star1-cleanliness" title="Poor">★</label>
                </div>
            </div>

            <!-- Ambience Rating -->
            <div class="mb-4">
                <label for="ambience" class="form-label">Ambience</label>
                <div class="rate">
                    <input type="radio" id="star5-ambience" name="ambience" value="5" />
                    <label for="star5-ambience" title="Excellent">★</label>
                    <input type="radio" id="star4-ambience" name="ambience" value="4" />
                    <label for="star4-ambience" title="Good">★</label>
                    <input type="radio" id="star3-ambience" name="ambience" value="3" />
                    <label for="star3-ambience" title="Average">★</label>
                    <input type="radio" id="star2-ambience" name="ambience" value="2" />
                    <label for="star2-ambience" title="Below Average">★</label>
                    <input type="radio" id="star1-ambience" name="ambience" value="1" />
                    <label for="star1-ambience" title="Poor">★</label>
                </div>
            </div>

            <!-- Facilities Rating -->
            <div class="mb-4">
                <label for="facilities" class="form-label">Facilities</label>
                <div class="rate">
                    <input type="radio" id="star5-facilities" name="facilities" value="5" />
                    <label for="star5-facilities" title="Excellent">★</label>
                    <input type="radio" id="star4-facilities" name="facilities" value="4" />
                    <label for="star4-facilities" title="Good">★</label>
                    <input type="radio" id="star3-facilities" name="facilities" value="3" />
                    <label for="star3-facilities" title="Average">★</label>
                    <input type="radio" id="star2-facilities" name="facilities" value="2" />
                    <label for="star2-facilities" title="Below Average">★</label>
                    <input type="radio" id="star1-facilities" name="facilities" value="1" />
                    <label for="star1-facilities" title="Poor">★</label>
                </div>
            </div>

            <!-- Services Rating -->
            <div class="mb-4">
                <label for="services" class="form-label">Services</label>
                <div class="rate">
                    <input type="radio" id="star5-services" name="services" value="5" />
                    <label for="star5-services" title="Excellent">★</label>
                    <input type="radio" id="star4-services" name="services" value="4" />
                    <label for="star4-services" title="Good">★</label>
                    <input type="radio" id="star3-services" name="services" value="3" />
                    <label for="star3-services" title="Average">★</label>
                    <input type="radio" id="star2-services" name="services" value="2" />
                    <label for="star2-services" title="Below Average">★</label>
                    <input type="radio" id="star1-services" name="services" value="1" />
                    <label for="star1-services" title="Poor">★</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </div>
        </form>
    </div>

    <!-- Your existing home.php content begins here -->

    <header