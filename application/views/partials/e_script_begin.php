<script src="<?= base_url('') ?>asset/js/vendor.min.js"></script>

<script src="<?= base_url('') ?>asset/libs/sweetalert2/sweetalert2.min.js"></script>

<script src="<?= base_url('') ?>asset/js/pages/scrollbar.init.js"></script>

<script src="<?= base_url('') ?>asset/libs/select2/select2.min.js"></script>

<script src="<?= base_url('') ?>asset/libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>

<!-- Boostrap select  -->
<script src="<?= base_url('') ?>asset/libs/bootstrap-select/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function() {
        // inisialize datepicker
        $("#datestart-autoclose").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            format: 'dd-mm-yyyy',
        })
        $("#dateend-autoclose").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            format: 'dd-mm-yyyy'
        })
        /* var dateTypeVar = $('#datestart-autoclose').datepicker('getDate');
        $.datepicker.formatDate('Y-m-d', dateTypeVar); */

        $(document).on('click', '.button_search', function(reload=true) {
            if(reload == false){
                $('#datatable').DataTable().ajax.reload(false);
            }else{
                $('#datatable').DataTable().ajax.reload();
            }
        })
    })


    function dataFillterFunc(dataarray = []) {
        let d = []

        return function(d) {
            d.hidden_datestart = document.getElementById('hidden_datestart').value;
            d.hidden_dateend = document.getElementById('hidden_dateend').value;

            if ($('#item_operator_id').length) {
                d.hidden_operator_id = document.getElementById('item_operator_id').value

            }

            if ($('#item_statusbill').length) {
                d.hidden_statusbill = document.getElementById('item_statusbill').value

            }
        }
    }

    function convertDateDatabase(dateDMY) {
        let result

        if (dateDMY) {
            var dateData = dateDMY
            dateData = dateData.split('-')
            result = dateData[2] + "-" + dateData[1] + "-" + dateData[0]
        }

        return result
    }
</script>