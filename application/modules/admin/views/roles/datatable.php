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
<div class="">
    <table id="datatable" class="table table-hover m-0 table-actions-bar dt-responsive dataTable no-footer dtr-inline" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th><?= mb_ucfirst($this->lang->line('_code')) ?></th>
                <th><?= mb_ucfirst($this->lang->line('roles_name')) ?></th>
                <th><?= mb_ucfirst($this->lang->line('_total')) ?></th>
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