<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="mb-1 mb-md-0">
                <div class="d-flex gap-2">
                    <div class="tool-btn">
                        <button type="button" id="load_report" class="btn ">Download</button>
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
            <div class="card-box d-none">
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
        <div class="">
            <div class="card-box">
                <table id="datatable_forload" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อลูกค้า</th>
                            <th>นทท. (จอง) ท่าน</th>
                            <th>ราคาจอง(บาท)</th>
                            <th>ว/ด/ป (POS)</th>
                            <th>ใบเสร็จ/<br>ใบกำกับภาษี</th>
                            <th>ใบเสร็จ/<br>ใบกำกับภาษีอย่างย่อ</th>
                            <th>ธนาคาร</th>
                            <th>เลขที่บัญชี</th>
                            <th>รับเงินมัดจำ</th>
                            <th>วันที่เข้าชมฟาร์ม</th>

                            <th>นทท. เข้าชมฟาร์ม ท่าน</th>
                            <th>ใบเสร็จ/<br>ใบกำกับภาษี</th>
                            <th>ใบเสร็จ/<br>ใบกำกับภาษีอย่างย่อ</th>
                            <th>ธนาคาร</th>
                            <th>เลขที่บัญชี</th>
                            <th>รับชำระหน้าฟาร์ม</th>

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
    let title_name = 'สรุปรายงานรับเงินมัดจำ (เงินโอน) ตะลอนฟาร์ม '
    $(document).ready(function() {
        getData()

        $(document).on('click', '.button_search ', function() {
            $('#datatable_forload').DataTable().ajax.reload();
        })
        $(document).on('click', '#load_report', function() {
            //
            // load report
            $('#datatable_forload').DataTable().button('0').trigger();

            $.toast({
                text: "ดำเนินการดาวน์โหลดรายงานจากระบบเรียบร้อย โปรดตรวจสอบที่โฟลเดอร์ Download",
                heading: "Download report",
                showHideTransition: "plan",
                position: "top-right",
                loaderBg: "#f96a74",
                hideAfter: 3e3,
                stack: 1,
            });
        })

        getDataForload()
        // 
        // Datatable for load
        function getDataForload() {
            if($('#datestart-autoclose').val()){
                
            }

            let datatable = $('#datatable_forload')

            let last_columntable = datatable.find('th').length - 1
            let last_defaultSort = last_columntable - 1

            let urlname = new URL(path(url_moduleControl + '/get_dataTable'), domain);

            let table = datatable.DataTable({
                scrollY: dataTableHeight(),
                scrollCollapse: false,
                autoWidth: false,
                // searchDelay: datatable_searchdelay_time,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                ajax: {
                    url: urlname,
                    type: 'get',
                    dataType: 'json',
                    data: dataFillterFunc()
                },
                order: [],
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },

                    {
                        responsivePriority: 2,
                        targets: last_columntable
                    },
                ],
                columns: [
                    {
                        "data": "NO",
                    },
                    {
                        "data": "CUSTOMER.display",
                    },
                    {
                        "data": "BILL.data.total_unit",
                    },
                    {
                        "data": "BILL.data.net",
                    },
                    {
                        "data": {
                            _: 'DATE_ORDER.display', // default show
                        }
                    },
                    {
                        "data": "DATA_NULL"
                    },
                    {
                        "data": "DEPOSIT.data.codetext"
                    },
                    {
                        "data": "BANK.data.scb"
                    },
                    {
                        "data": "BANK.data.scb_code"
                    },
                    {
                        "data": "DEPOSIT.display"
                    },
                    {
                        "data": {
                            _: 'BOOKING_DATE.display', // default show
                        }
                    },

                    {
                        "data": "DATA"
                    },
                    {
                        "data": "DATA"
                    },
                    {
                        "data": "DATA"
                    },
                    {
                        "data": "DATA"
                    },
                    {
                        "data": "DATA"
                    },
                    {
                        "data": "DATA"
                    },

                ],

                dom: datatable_dom,
                buttons: datatable_button,
            })

            // table.buttons(0, 1).remove();
            table.button().add(0, { extend: 'excel', text: 'Excel' , title: title_name},);
        }


        //	format number and float (.00) return string!! 
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
        }
    })
</script>
<?php include('script.php') ?>
<?php include('script_crud.php') ?>
<?php include('script_datatable.php') ?>
<?php //require_once('application/views/partials/e_script_print.php'); 
?>