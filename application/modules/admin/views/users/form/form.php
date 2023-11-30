<div class="row">
    <div class="col-md-6">
        <div class="form-group ">
            <span class="required"><i class="mdi mdi-svg"></i></span>
            <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_name_th')) ?></label>
            <input type="text" class="form-control" name="name_th" placeholder="<?= mb_ucfirst($this->lang->line('form_roles_roles_name_placeholder')) ?>" value="" required>
        </div>
        <div class="form-group ">
            <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_lastname_th')) ?></label>
            <input type="text" class="form-control" name="lastname_th" placeholder="<?= mb_ucfirst($this->lang->line('form_roles_roles_name_placeholder')) ?>" value="">
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="form-group ">
            <span class="required"><i class="mdi mdi-svg"></i></span>
            <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_name_us')) ?></label>
            <input type="text" class="form-control" name="name_us" placeholder="<?= mb_ucfirst($this->lang->line('form_roles_roles_name_placeholder')) ?>" value="" required>
        </div>
        <div class="form-group ">
            <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_lastname_us')) ?></label>
            <input type="text" class="form-control" name="lastname_us" placeholder="<?= mb_ucfirst($this->lang->line('form_roles_roles_name_placeholder')) ?>" value="">
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('form_user_username')) ?></label>
        <input type="text" class="form-control" name="input_username" placeholder="<?= mb_ucfirst($this->lang->line('_username')) ?>" value="" required>
    </div>
    <div class="form-group col-md-6">
        <span class="required"><i class="mdi mdi-svg"></i></span>
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('form_user_password')) ?></label>
        <input type="text" class="form-control" name="input_password" placeholder="<?= mb_ucfirst($this->lang->line('_form_password_placeholder')) ?>" value="" required>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label for="">สิทธิ์ <small>เลือกได้มากกว่า 1</small></label>
        <select name="user_role[]" id="user_role" class="form-control" data-toggle="select2" multiple="multiple" data-placeholder="ระบุสิทธิ์ได้มากกว่า 1">
            <?php
            foreach ($role as $row_role) :
                $aria = mb_ucfirst($row_role->NAME);
            ?>
                <option value="<?= $row_role->ID; ?>"><?= $aria; ?></option>
            <?php
            endforeach;
            ?>
        </select>
    </div>
</div>

<style>
    .jstree-grid-container {
        display: grid;
        grid-template-columns: auto;
    }

    @media (min-width: 427px) {
        .jstree-grid-container {
            grid-template-columns: auto auto;
            grid-gap: 10px;
        }
    }

    @media (min-width: 992px) {
        .jstree-grid-container {
            grid-template-columns: auto auto auto;
            grid-gap: 10px;
        }
    }
</style>
<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('form_roles_label_permit')) ?></label>
        <div class="jstree-grid-container">
            <?php
            echo html_roles_jstree($permit, 'jstree_checkbox');
            ?>
        </div>

    </div>
</div>
<!-- 
    // selected
    li[aria-level=2][aria-selected=true]
    
    aria-level=1 : jstree-undetermined
 -->