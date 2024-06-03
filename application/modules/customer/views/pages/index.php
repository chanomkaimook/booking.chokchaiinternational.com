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
                <?php require_once('application/views/partials/e_filter_base.php'); ?>
            </div>

        </div>
        <style>
            .truncate {
                 max-width: 200px;
             }
        </style>
        <div class="">
            <div class="card-box">
                <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?= mb_ucfirst($this->lang->line('_name')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_status')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_display')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_usernow')) ?></th>
                            <th><?= mb_ucfirst($this->lang->line('_datenow')) ?></th>
                            <th class="hidden-sm"><?= mb_ucfirst($this->lang->line('_action')) ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_item.php') ?>
<!-- End Modal -->

<script>
    $(document).ready(function() {
        getData()

        const inputInt = d.querySelectorAll('input.int_only')
        inputInt.forEach(function(item, index) {
            item.addEventListener("keyup", function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
            })
        })

        /* //	format number and float (.00) return string!! 
        function formatMoney(number, decPlaces, decSep, thouSep) {
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
                decSep = typeof decSep === "undefined" ? "." : decSep;
            thouSep = typeof thouSep === "undefined" ? "," : thouSep;
            var sign = number < 0 ? "-" : "";
            var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
            var j = (j = i.length) > 3 ? j % 3 : 0;

            return sign +
                (j ? i.substr(0, j) + thouSep : "") +
                i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
                (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
        } */
    })
</script>
<?php include('script.php') ?>
<?php include('script_crud.php') ?>
<?php include('script_datatable.php') ?>
<?php //require_once('application/views/partials/e_script_print.php'); ?>