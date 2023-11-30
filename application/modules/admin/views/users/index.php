<div class="content">
    <?php
    if (is_Mobile()) {
    ?>
        <style>
            .dropdown-menu-right {
                right: auto !important;
                left: 0px !important;
            }
        </style>
    <?php
    }
    ?>
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="card-box">


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
            <div class="table-responsive">


                <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>username</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th><?= mb_ucfirst($this->lang->line('_display')) ?></th>
                            <th>วันที่สมัคร</th>
                            <th><?= mb_ucfirst($this->lang->line('_datenow')) ?></th>
                            <th class="hidden-sm"><?= mb_ucfirst($this->lang->line('_action')) ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal.php'); ?>

<script>
    let data_1_ready = 0,
        data_2_ready = 0;
    //  =========================
    //  =========================
    //  Setting
    //  =========================
    //  =========================

    //  *
    //  * Dom
    //  * setting variable
    //  *
    const d = document
    const datatable_name = '#datatable'

    //  *
    //  * Button
    //  * setting variable
    //  *
    const btn_view = ".btn-view"
    const btn_add = '.btn-add'
    const btn_edit = '.btn-edit'
    const btn_del = '.btn-del'
    const btn_submit = 'button[type=submit]'
    const btn_print = '.btn-print'

    //  *
    //  * Form
    //  * setting variable
    //  *
    const form_name = '#frm'
    const form_hidden_id = '[name=frm_hidden_id]'

    //  *
    //  * Modal
    //  * setting variable
    //  *
    const modal_roles = '#modal_user'
    const modal = '.modal'
    const modal_body = '.modal .modal-body'
    const modal_body_view = '.modal .modal-body-view'
    const modal_body_form = '.modal .modal-body-form'

    //  =========================
    //  =========================
    //  End Setting
    //  =========================
    //  =========================

    $('body .content:first').append(loading)
    $('body .container-fluid').css('display', 'none')

    checkReady()

    function checkReady() {
        if (data_1_ready) {
            $('body .container-fluid').fadeIn()
            $('body .loading').remove()
        }
    }

    $(function() {

        // 
        // function to process data
        // loading data
        readyData()

        async function readyData() {
            let result1 = await new Promise((resolve, reject) => {
                resolve(getData())
            })
            let result2 = await new Promise((resolve, reject) => {
                resolve(data_1_ready = 1)
            });

            checkReady()
        }

    })
</script>
<?php include('script.php') ?>
<?php include('script_crud.php') ?>
<?php include('script_datatable.php') ?>