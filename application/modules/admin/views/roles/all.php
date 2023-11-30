<style>
    p.sub-header:empty::before {
        content: "";
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        display: -webkit-box;
    }

    .tab-content .card-body-head {
        min-height: 5rem;
    }

    .tab-content .sub-header {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .tab-content .card-title {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
<div class="section-tool d-flex flex-column flex-md-row justify-content-between">

    <div class="mb-1 mb-md-0">
        <div class="d-flex gap-2">
            <div class="tool-btn">
                <button type="button" class="btn-add btn"><?= mb_ucfirst($this->lang->line('_form_btn_add')) ?></button>
            </div>
        </div>
    </div>

    <div class="">
        <?php include('application/views/partials/e_filter_base.php'); ?>
    </div>

</div>
<div class="row">

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <div class="card-body-head">
                    <h5 class="card-title mb-0 text-info" title="asdasd">Primary Heading</h5>
                    <p class="sub-header" title="asdasd">test permition</p>
                </div>

                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="mb-0 text-danger">Ban</p>
                        <h3 class="text-center text-secondary">10</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0 text-info">Assign</p>
                        <h3 class="text-center text-secondary">5</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0">Total</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <div class="card-body-head">
                    <h5 class="card-title mb-0 text-info" title="asdasd">Administrator test role & permition </h5>
                    <p class="sub-header" title="asdasd">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi rem cum numquam, voluptatum est quo saepe nemo neque laudantium molestiae possimus et totam. Quam inventore pariatur illo? Delectus, quaerat facere?</p>
                </div>

                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="mb-0 text-danger">Ban</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0 text-info">Assign</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0">Total</p>
                        <h3 class="text-center text-secondary">27</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <div class="card-body-head">
                    <h5 class="card-title mb-0 text-info" title="asdasd">Primary Heading</h5>
                    <p class="sub-header" title="asdasd"></p>
                </div>

                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="mb-0 text-danger">Ban</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0 text-info">Assign</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0">Total</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="card">
            <div class="card-body">
                <div class="card-body-head">
                    <h5 class="card-title mb-0 text-info" title="asdasd">Primary Heading</h5>
                    <p class="sub-header" title="asdasd"></p>
                </div>

                <div class="d-flex">
                    <div class="flex-fill">
                        <p class="mb-0 text-danger">Ban</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0 text-info">Assign</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                    <div class="flex-fill">
                        <p class="mb-0">Total</p>
                        <h3 class="text-center text-secondary">0</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>