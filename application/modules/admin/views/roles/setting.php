<style>
    #accordion .card-header {
        min-height: 7.5rem;
    }

    #accordion .sub-header,
    #accordion .card-title {
        margin-bottom: 0px;
    }

    #accordion .sub-header {
        font-weight: 400 !important;
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
<div id="accordion" class="row">
    <div class="col-md-3">

        <div class="mb-3">
            <div class="card mb-0">
                <div class="card-header" id="block1">
                    <h5 class="m-0">
                        <a href="#ac1" class="text-info collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="ac1">
                            <div class="card-body-head">
                                <p class="card-title">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta quia officia at excepturi nisi reprehenderit id quis ea dignissimos, culpa doloribus, inventore, optio nostrum est deleniti repellendus maiores quas pariatur?
                                </p>
                                <p class="sub-header">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi eos cum obcaecati officiis ipsam, dolorem nulla? Iure ipsam quisquam alias modi saepe minus nulla distinctio voluptates iusto eos, vel cupiditate.</p>
                            </div>
                            <p class="sub-header pt-2">พบ 4 รายการ</p>
                        </a>
                    </h5>
                </div>

                <div id="ac1" class="collapse" aria-labelledby="block1" data-parent="#accordion" style="">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-3">


        <div class="mb-3">
            <div class="card mb-0">
                <div class="card-header" id="block2">
                    <h5 class="m-0">
                        <a href="#ac2" class="text-info collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="ac2">
                            <div class="card-body-head">
                                <p class="card-title">
                                    Lorem ipsum dolor sit
                                </p>
                                <p class="sub-header">Lorem ipsum dolor sit,</p>
                            </div>

                            <p class="sub-header pt-2">พบ 4 รายการ</p>
                        </a>
                    </h5>
                </div>

                <div id="ac2" class="collapse" aria-labelledby="block2" data-parent="#accordion" style="">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">


        <div class="mb-3">
            <div class="card mb-0">
                <div class="card-header" id="block3">
                    <h5 class="m-0">
                        <a href="#ac3" class="text-info collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="ac3">
                            <div class="card-body-head">
                                <p class="card-title">
                                    Lorem ipsum dolor sit
                                </p>
                                <p class="sub-header">Lorem ipsum dolor sit,</p>
                            </div>

                            <p class="sub-header pt-2">พบ 1 รายการ</p>
                        </a>
                    </h5>
                </div>

                <div id="ac3" class="collapse" aria-labelledby="block3" data-parent="#accordion" style="">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-3">

        <div class="mb-3">
            <div class="card mb-0">
                <div class="card-header" id="block4">
                    <h5 class="m-0">
                        <a href="#ac4" class="text-info collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="ac4">
                            <div class="card-body-head">
                                <p class="card-title">
                                    Lorem ipsum dolor sit
                                </p>
                                <p class="sub-header"></p>
                            </div>

                            <p class="sub-header pt-2">พบ 0 รายการ</p>
                        </a>
                    </h5>
                </div>

                <div id="ac4" class="collapse" aria-labelledby="block4" data-parent="#accordion" style="">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-3">

        <div class="mb-3">
            <div class="card mb-0">
                <div class="card-header" id="block4">
                    <h5 class="m-0">
                        <a href="#ac4" class="text-info collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="ac4">
                            <div class="card-body-head">
                                <p class="card-title">
                                    Lorem ipsum dolor sit
                                </p>
                                <p class="sub-header"></p>
                            </div>

                            <p class="sub-header pt-2">พบ 0 รายการ</p>
                        </a>
                    </h5>
                </div>

                <div id="ac4" class="collapse" aria-labelledby="block4" data-parent="#accordion" style="">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>