<?php
include 'admin/db_connect.php';
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

    .venue-text {
        justify-content: center;
        align-items: center;
    }

    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .rating-stars i {
        font-size: 24px;
        border: 2px solid black;
        border-radius: 50%;
        padding: 5px;
        color: transparent;
        background-color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .rating-stars i.active {
        background-color: blue;
        color: white;
    }

    .rating-label {
        font-weight: bold;
        margin-top: 10px;
        text-align: center;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
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
                                        <div class="rating-section mt-3">
                                            <div class="rating-label">Cleanliness</div>
                                            <div class="rating-stars" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-star" data-type="cleanliness" data-value="1"></i>
                                                <i class="fa fa-star" data-type="cleanliness" data-value="2"></i>
                                                <i class="fa fa-star" data-type="cleanliness" data-value="3"></i>
                                                <i class="fa fa-star" data-type="cleanliness" data-value="4"></i>
                                                <i class="fa fa-star" data-type="cleanliness" data-value="5"></i>
                                            </div>
                                            <div class="rating-label">Ambience</div>
                                            <div class="rating-stars" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-star" data-type="ambience" data-value="1"></i>
                                                <i class="fa fa-star" data-type="ambience" data-value="2"></i>
                                                <i class="fa fa-star" data-type="ambience" data-value="3"></i>
                                                <i class="fa fa-star" data-type="ambience" data-value="4"></i>
                                                <i class="fa fa-star" data-type="ambience" data-value="5"></i>
                                            </div>
                                            <div class="rating-label">Facilities</div>
                                            <div class="rating-stars" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-star" data-type="facilities" data-value="1"></i>
                                                <i class="fa fa-star" data-type="facilities" data-value="2"></i>
                                                <i class="fa fa-star" data-type="facilities" data-value="3"></i>
                                                <i class="fa fa-star" data-type="facilities" data-value="4"></i>
                                                <i class="fa fa-star" data-type="facilities" data-value="5"></i>
                                            </div>
                                            <div class="rating-label">Service</div>
                                            <div class="rating-stars" data-id="<?php echo $row['id'] ?>">
                                                <i class="fa fa-star" data-type="service" data-value="1"></i>
                                                <i class="fa fa-star" data-type="service" data-value="2"></i>
                                                <i class="fa fa-star" data-type="service" data-value="3"></i>
                                                <i class="fa fa-star" data-type="service" data-value="4"></i>
                                                <i class="fa fa-star" data-type="service" data-value="5"></i>
                                            </div>
                                        </div>
                                        <div class="action-buttons">
                                            <button class="btn btn-success book-venue" type="button" data-id='<?php echo $row['id'] ?>'>Book</button>
                                            <button class="btn btn-secondary comment-btn" data-id='<?php echo $row['id'] ?>'><i class="fa fa-comment"></i> Comment</button>
                                            <button class="btn btn-primary like-btn" data-id='<?php echo $row['id'] ?>'><i class="fa fa-thumbs-up"></i> Like</button>
                                        </div>
                                        <div class="comment-section" id="comment-section-<?php echo $row['id'] ?>">
                                            <textarea placeholder="Write your comment here..."></textarea>
                                            <button class="btn btn-primary submit-comment" data-id='<?php echo $row['id'] ?>'>Submit</button>
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

    document.querySelectorAll('.rating-stars').forEach(container => {
        container.querySelectorAll('i').forEach(star => {
            star.addEventListener('click', function() {
                const stars = Array.from(this.parentElement.querySelectorAll('i'));
                stars.forEach(s => s.classList.remove('active')); // Remove active class from all stars
                this.classList.add('active'); // Add active class to the clicked star
                // Mark all previous stars as active
                let markActive = true;
                stars.forEach(s => {
                    if (s === this) markActive = false;
                    if (!markActive) s.classList.add('active');
                });
            });
        });
    });
</script>