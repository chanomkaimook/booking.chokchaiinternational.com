<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $sqlproject = $this->db->get('project');
                $project = $sqlproject->row();
                ?>
                2020 - <?= date('Y'); ?> &copy; <?= $project->TITLE_NAME ?>. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->