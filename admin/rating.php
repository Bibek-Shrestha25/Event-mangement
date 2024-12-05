<?php 
    include 'db_connect.php';
    $result = $conn->query("SELECT * FROM rating_weights");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Rating Weights</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .addRange {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Dynamic Rating Weights</h2>

        <!-- Section to display existing data -->
        <div class="mb-4" id="existingData">
            <h4>Existing Rating Weights</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Start Range (Days)</th>
                        <th>End Range (Days)</th>
                        <th>Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        while ($row) {
                    ?>
                            <tr>
                                <td><?php echo $row['days_range_start'] ?></td>
                                <td><?php echo $row['days_range_end'] ?></td>
                                <td><?php echo $row['weight'] ?></td>
                            </tr>
                    <?php
                            $row = $result->fetch_assoc();
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="3" class="text-center">No data found</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <form id="ratingForm" class="card p-4 shadow-sm" action="ajax.php?action=save_rating_weight" method="POST">
            <div id="formFields">
                <div class="row g-3 align-items-center mb-3" id="rangeGroup-0">
                    <div class="col-md-3">
                        <label for="startRange-0" class="form-label">Start Range (Days)</label>
                        <input type="number" class="form-control" id="startRange-0" name="startRange[]" value="0" required>
                    </div>
                    <div class="col-md-3">
                        <label for="endRange-0" class="form-label">End Range (Days)</label>
                        <input type="number" class="form-control" id="endRange-0" name="endRange[]" required>
                    </div>
                    <div class="col-md-3">
                        <label for="weight-0" class="form-label">Weight</label>
                        <input type="number" step="0.01" class="form-control" id="weight-0" name="weight[]" required>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-primary addRange">+</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-sm btn-block btn-success col-sm-2">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let rangeCount = 1;

        document.querySelector('.addRange').addEventListener('click', function() {
            const lastEndRange = parseInt(document.querySelector(`#endRange-${rangeCount - 1}`)?.value || '0', 10);
            const formFields = document.getElementById('formFields');

            const newRangeGroup = document.createElement('div');
            newRangeGroup.className = 'row g-3 align-items-center mb-3';
            newRangeGroup.id = `rangeGroup-${rangeCount}`;

            newRangeGroup.innerHTML = `
                <div class="col-md-3">
                    <label for="startRange-${rangeCount}" class="form-label">Start Range (Days)</label>
                    <input type="number" class="form-control" id="startRange-${rangeCount}" name="startRange[]" value="${lastEndRange + 1}" required>
                </div>
                <div class="col-md-3">
                    <label for="endRange-${rangeCount}" class="form-label">End Range (Days)</label>
                    <input type="number" class="form-control" id="endRange-${rangeCount}" name="endRange[]" required>
                </div>
                <div class="col-md-3">
                    <label for="weight-${rangeCount}" class="form-label">Weight</label>
                    <input type="number" step="0.01" class="form-control" id="weight-${rangeCount}" name="weight[]" required>
                </div>
                <div class="col-md-3 text-end">
                    <button type="button" class="btn btn-primary addRange">+</button>
                </div>
            `;

            formFields.appendChild(newRangeGroup);

            // Remove previous + button functionality
            const addButtons = document.querySelectorAll('.addRange');
            addButtons.forEach((button) => button.removeEventListener('click', arguments.callee));
            addButtons[addButtons.length - 1].addEventListener('click', arguments.callee);

            rangeCount++;
        });
    </script>
</body>

</html>