<div class="content">
    <input type="hidden" id="hidden_task_id">
    <input type="hidden" id="hidden_role_product_edit" value="<?php echo check_permit('product.edit') ? 1 : null ?>" >
    <input type="hidden" id="hidden_role_product_delete" value="<?php echo check_permit('product.delete') ? 1 : null ?>" >
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <?php
                        if (check_permit('product.insert')) :
                        ?>
                            <button type="button" class="btn-add btn ticket">เพิ่มตั๋วเข้าชม</button>
                            <button type="button" class="btn-add btn food">เพิ่มอาหาร</button>
                        <?php
                        endif;
                        ?>
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
                            <th>ชื่อ</th>
                            <th>code</th>
                            <th>ประเภท</th>
                            <th>รุ่น</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                            <th>โดย</th>
                            <th>วันล่าสุด</th>
                            <th class="hidden-sm">เพิ่มเติม</th>
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

        $(document).on('click', 'button.ticket', function() {
            $("select#ticket").removeAttr('disabled')
            $("select#division").removeAttr('disabled')
        })
        $(document).on('click', 'button.food', function() {
            $("select#ticket").attr('disabled', 'disabled')
            $("select#division").attr('disabled', 'disabled')
        })
    })
</script>
<?php include('script.php') ?>
<?php include('script_crud.php') ?>
<?php include('script_datatable.php') ?>