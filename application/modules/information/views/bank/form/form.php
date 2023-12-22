<div class="row">
    <div class="form-group col-md-12">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">ชื่อ</label>
        <input type="text" class="form-control" name="item_name" placeholder="ระบุ" value="" required>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize">เลขแบงค์</label>
        <input type="text" class="form-control" name="numbercard" placeholder="ระบุ" value="" required>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize">สถานะ</label>
        <select name="status_offview" class="form-control">
            <?php
            echo html_status_offview();
            ?>
        </select>
    </div>
</div>

<script>
    $(document).ready(function() {
        let form_time_start = '#time_start'
        let form_time_end = '#time_end'

        $(document).on('change', form_time_start, function() {
            let item_value = $(this).val()
            if (item_value) {
                $(form_time_end).find('option').each(function(index, item) {
                    if (parseInt(item.value) <= parseInt(item_value)) {
                        $(item).addClass('d-none')
                    } else {
                        $(item).removeClass('d-none')
                    }
                })
            }
        })

        $(document).on('change', form_time_end, function() {
            let item_value = $(this).val()
            if (item_value) {
                $(form_time_start).find('option').each(function(index, item) {
                    if (parseInt(item.value) > parseInt(item_value)) {
                        $(item).addClass('d-none')
                    } else {
                        $(item).removeClass('d-none')
                    }
                })
                // $(form_time_end).find('option[value=2]').addClass('d-none')
            }
        })
    })
</script>