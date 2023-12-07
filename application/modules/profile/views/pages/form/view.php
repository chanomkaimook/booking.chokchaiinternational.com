<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_name_th')) ?></label>
        <p class="card-text name_th"></p>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_lastname_th')) ?></label>
        <p class="card-text lastname_th"></p>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_name_us')) ?></label>
        <p class="card-text name_us"></p>
    </div>
    <div class="form-group col-md-6">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('_lastname_us')) ?></label>
        <p class="card-text lastname_us"></p>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('form_user_username')) ?></label>
        <p class="card-text username"></p>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label for=""><?= mb_ucfirst($this->lang->line('form_roles_label_rolechild')) ?></label>
        <p class="card-text user_role d-flex gap-1">
        </p>
    </div>
</div>


<div class="row">
    <div class="form-group col-md-12">
        <label class="text-capitalize"><?= mb_ucfirst($this->lang->line('form_roles_label_permit')) ?></label>

        <!-- <div data-plugin="jstree">
            <ul>
                <li data-jstree='{"opened":true}'>Plugins
                    <ul>
                        <li data-jstree='{"selected":true,"type":"file"}' data-id="5">Plugin one</li>
                        <li data-jstree='{"type":"file"}' data-id="2">Plugin two</li>
                    </ul>
                </li>
            </ul>
        </div> -->

        <div class="jstree-grid-container">
        </div>

    </div>
</div>