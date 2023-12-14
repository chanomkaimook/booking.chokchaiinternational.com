<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <button type="button" class="btn-add btn"><?= mb_ucfirst($this->lang->line('_form_btn_add')) ?></button>
                    </div>
                </div>
            </div>
            <div class="">
                <button type="button" class="btn-print btn btn-pink" onclick="printDiv('document')"><i class="fas fa-print"></i> Print</button>
            </div>

        </div>
        <style>
            .truncate {
                max-width: 100px;
            }

            /* @page {
                size: A4;
                margin: 0;
            } */
            #document {
                width: 210mm;
                height: 148.5mm;
                margin: 2
            }

            @media print {

                html,
                body {
                    width: 210mm;
                    height: 148.5mm;
                    margin: 2
                }

                @page {
                    size: 210mm 148.5mm;
                    margin: 2;
                }
            }
        </style>
        <div class="">
            <div class="card-box">
                <div id="document" class="document">
                    <h2>Form Row/Grid</h2>
                    <p>In this example we will demonstrate the differences between .row and .form-row.</p>
                    <p>Create two form elements that appear side by side with .row and .col:</p>
                    <form>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="Enter password" name="pswd">
                            </div>
                        </div>
                    </form>
                    <br>

                    <p>Create two form elements that appear side by side with .form-row and .col:</p>
                    <form>
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="Enter password" name="pswd">
                            </div>
                        </div>
                    </form>

                    <h2>Form Row/Grid</h2>
                    <p>In this example we will demonstrate the differences between .row and .form-row.</p>
                    <p>Create two form elements that appear side by side with .row and .col:</p>
                    <form>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="Enter password" name="pswd">
                            </div>
                        </div>
                    </form>
                    <br>

                    <p>Create two form elements that appear side by side with .form-row and .col:</p>
                    <form>
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control" id="email" placeholder="Enter email" name="email">
                            </div>
                            <div class="col">
                                <input type="password" class="form-control" placeholder="Enter password" name="pswd">
                            </div>
                        </div>
                    </form>



                </div>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->
<?php require_once('application/views/partials/e_script_print.php'); ?>