<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

        </div>
        <div class="">
            <div class="card-box">

                <!-- Modal -->
                <?php require_once('form/view.php'); ?>

            </div>
        </div>

        <!-- end row -->

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Script -->
<?php require_once('application/views/partials/e_script_print.php'); ?>
<!-- End Script -->

<script>
    $(document).ready(function() {

        let user_id = document.getElementById('hidden_user_id').value
        /* async_get_data(user_id)
            .then((resp) => {
                modalActive(resp, 'view')
            })
            .then(() => {
                modalLoading_clear()
            }) */
        // async_get_data(user_id)
        async_get_data(user_id)
        .then((resp)=>{
            // console.log(resp)
            modalActive(resp, 'view')
        })

        
    })

</script>
<?php include('script.php') ?>
<?php include('script_crud.php') ?>