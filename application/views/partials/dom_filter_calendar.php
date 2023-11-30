<input type="hidden" id="hidden_datestart" name="hidden_datestart">
<input type="hidden" id="hidden_dateend" name="hidden_dateend">

<div class="form-inline flex-fill">
    <div class="form-group w-100">

        <label class="d-none d-sm-block"><?= mb_ucfirst($this->lang->line('_fillter_calendar_day')) ?></label>
        <input type="text" class="form-control form-control-sm " placeholder="<?= mb_ucfirst($this->lang->line('_fillter_calendar_day_placeholder')) ?>" data-date-format='yy-mm-dd' id="datestart-autoclose" name="datestart-autoclose">
    </div>
</div>

<div class="form-inline flex-fill">
    <div class="form-group w-100">
        <label class="d-none d-sm-block"><?= mb_ucfirst($this->lang->line('_fillter_calendar_dayto')) ?></label>
        <input type="text" class="form-control form-control-sm " placeholder="<?= mb_ucfirst($this->lang->line('_fillter_calendar_dayto_placeholder')) ?>" id="dateend-autoclose" name="dateend-autoclose">

    </div>

</div>


<script>
    $(document).ready(function() {
        $(document).on('change', '#datestart-autoclose', function() {
            if ($(this).val()) {
                var date_start_obj = $(this).datepicker('getDate');
                let t = new Date(date_start_obj);
                let item_month = (t.getMonth() + 1).toString().padStart(2, "0");
                let item_day = t.getDate().toString().padStart(2, "0")

                var date_start = t.getFullYear() + "-" + item_month + "-" + item_day

                $('#hidden_datestart').val(date_start)
            } else {
                $('#hidden_datestart').val('')
            }
        })

        $(document).on('change', '#dateend-autoclose', function() {
            if ($(this).val()) {
                var date_end_obj = $(this).datepicker('getDate');
                let t = new Date(date_end_obj);
                let item_month = (t.getMonth() + 1).toString().padStart(2, "0");
                let item_day = t.getDate().toString().padStart(2, "0")

                var date_end = t.getFullYear() + "-" + item_month + "-" + item_day

                $('#hidden_dateend').val(date_end)
            } else {
                $('#hidden_dateend').val('')
            }

        })
    })
</script>