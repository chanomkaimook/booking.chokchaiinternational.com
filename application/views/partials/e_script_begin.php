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

        $(document).on('click', '.button_search', function(reload = true) {
            if (reload == false) {
                $('#datatable').DataTable().ajax.reload(false);
            } else {
                $('#datatable').DataTable().ajax.reload();
            }
        })
    })


    function dataFillterFunc(dataarray = []) {
        let d = []

        return function(d) {
            d.hidden_datestart = document.getElementById('hidden_datestart').value;
            d.hidden_dateend = document.getElementById('hidden_dateend').value;

            if (dataarray) {
                dataarray.forEach(function(item, index) {
                    let item_name = item.name
                    if(item_name == 'column'){
                        d.item_name = item.value
                    }else{
                        d[item_name] = document.getElementById(item_name).value
                    }
                })
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

    //
    // fixed datepicker position when work on scolling
    function setDatepickerPos(input, inst) {
        var rect = input.getBoundingClientRect();
        // use 'setTimeout' to prevent effect overridden by other scripts
        setTimeout(function() {
            var scrollTop = $("body").scrollTop();
            inst.dpDiv.css({
                top: rect.top + input.offsetHeight + scrollTop
            });
        }, 0);
    }
</script>