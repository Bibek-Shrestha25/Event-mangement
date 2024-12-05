<?php
include 'admin/db_connect.php';
include_once 'admin/admin_class.php';
$crud = new Action();
?>
<style>
    #portfolio .img-fluid {
        width: calc(100%);
        height: 30vh;
        z-index: -1;
        position: relative;
        padding: 1em;
    }

    .venue-list {
        cursor: pointer;
        border: unset;
        flex-direction: inherit;
    }

    .venue-list .carousel,
    .venue-list .card-body {
        width: calc(50%)
    }

    .venue-list .carousel img.d-block.w-100 {
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        min-height: 50vh;
    }

    span.highlight {
        background: yellow;
    }

    .carousel,
    .carousel-inner,
    .carousel-item {
        min-height: calc(100%)
    }

    header.masthead,
    header.masthead:before {
        min-height: 50vh !important;
        height: 50vh !important
    }

    .row-items {
        position: relative;
    }

    .card-left {
        left: 0;
    }

    .card-right {
        right: 0;
    }

    .rtl {
        direction: rtl;
    }

    .ltr {
        direction: ltr;
    }

    .venue-text {
        justify-content: center;
        align-items: center;
    }

    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 5px;
        margin-top: 10px;
        position: relative;
        align-items: center;
    }



    .rating-value {
        margin-left: 10px;
        font-size: 14px;
        color: #555;
        font-weight: bold;
    }

    .action-buttons .book-venue {
        font-size: 16px;
        font-weight: bold;
        background-color: #007bff;
        color: #fff;
        border: none;
        transition: all 0.3s ease;

    }

    .action-buttons .book-venue:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }


    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        justify-content: center;
    }

    .comment-section {
        margin-top: 15px;
        display: none;
    }

    .comment-section textarea {
        width: 100%;
        height: 70px;
        margin-bottom: 10px;
        resize: none;
    }
</style>
<header class="masthead">
</header>
<div class="container-fluid mt-3 pt-2">
    <h4 class="text-center text-white">List of Our Event Venues</h4>
    <hr class="divider">
    <div class="row-items">
        <div class="col-lg-12">
            <div class="row">
                <?php
                $rtl = 'rtl';
                $ci = 0;
                $venue = $conn->query("SELECT * FROM venue ORDER BY rand()");
                while ($row = $venue->fetch_assoc()):
                    $ci++;
                    $rtl = ($ci < 3) ? '' : 'rtl';
                    $starDirection = ($ci < 3) ? '' : 'ltr';
                    $ci = ($ci == 4) ? 0 : $ci;
                ?>
                    <div class="col-md-6">
                        <div class="card venue-list <?php echo $rtl ?>" data-id="<?php echo $row['id'] ?>">
                            <div id="imagesCarousel_<?php echo $row['id'] ?> card-img-top" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php
                                    $images = array();
                                    $fpath = 'admin/assets/uploads/venue_' . $row['id'];
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
                                    <a class="carousel-control-prev" href="#imagesCarousel_<?php echo $row['id'] ?>" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#imagesCarousel_<?php echo $row['id'] ?>" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center justify-content-center text-center h-100">
                                    <div>
                                        <h3><b class="filter-txt"><?php echo ucwords($row['venue']) ?></b></h3>
                                        <small><i><?php echo $row['address'] ?></i></small>

                                        <!-- Rating Stars -->
                                        <div class="rating-stars <?= $starDirection ?>" data-id="<?php echo $row['id'] ?>">
                                            <?php
                                            $rating = $crud->showWeightRate($row['id'])['weighted_average_rating'];
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= floor($rating)) {
                                                    // Fully filled star
                                                    echo '<i class="fa fa-star filled"></i>';
                                                } elseif ($i == ceil($rating) && $rating - floor($rating) > 0) {
                                                    // Half-filled star
                                                    echo '<i class="fa fa-star-half-alt"></i>';
                                                } else {
                                                    // Empty star
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            endfor;
                                            ?>
                                            <span class="rating-value"><?php echo number_format($rating, 1); ?></span> <!-- Show the rating value -->
                                        </div>



                                        <!-- Action Buttons -->
                                        <div class="action-buttons">
                                            <button class="btn btn-success book-venue" type="button" data-id="<?php echo $row['id'] ?>">Book</button>
                                        </div>

                                        <!-- Comment Section -->
                                        <div class="comment-section" id="comment-section-<?php echo $row['id'] ?>">
                                            <textarea placeholder="Write your comment here..."></textarea>
                                            <button class="btn btn-primary submit-comment" data-id="<?php echo $row['id'] ?>">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<script>
    // $('.card.venue-list').click(function(){
    //     location.href = "index.php?page=view_venue&id="+$(this).attr('data-id')
    // })
    $('.book-venue').click(function() {
        uni_modal("Submit Booking Request", "booking.php?venue_id=" + $(this).attr('data-id'))
    })
    $('.venue-list .carousel img').click(function() {
        viewer_modal($(this).attr('src'))
    })
</script>