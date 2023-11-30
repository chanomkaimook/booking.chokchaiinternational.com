<div id="accordion">
    <div class="mb-0">

        <?php include('application/views/partials/dom_filter_collaspe.php'); ?>

        <div id="collapseOne" class="collapse show d-md-block" aria-labelledby="headingOne" data-parent="#accordion">

            <div class="d-sm-flex">
                <div class="filter">
                    <?php include('application/views/partials/dom_filter_calendar.php'); ?>
                </div>
                <div class="filter-add">
                    <?php include('application/views/partials/dom_filter_doc.php'); ?>
                </div>

                <?php include('application/views/partials/dom_filter_button.php'); ?>
            </div>

        </div>
    </div>
</div>