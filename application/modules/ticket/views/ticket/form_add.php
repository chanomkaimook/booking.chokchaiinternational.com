<div class="content">

    <!-- Start Content-->
    <div class="container-fluid">
        <div class="section-tool d-flex gap-2">
            <?= anchor('ticket/ctl_ticket/', 'ข้อมูลใบงานของคุณ', 'class="btn btn-info"') ?>
        </div>

        <?php require_once('form/add.php') ?>

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<?php require_once('script_crud.php') ?>
<?php require_once('script.php') ?>

<script>
    const d = document
    const frm = d.getElementById('form_add')

    frm.addEventListener('submit', function(e) {
        e.preventDefault()

        insert_data()
    })

    // 
    // insert data form
    function insert_data() {

        let frm = $('#form_add')
        var dataArray = frm.serializeArray(),
            len = dataArray.length

        let data = new FormData();
        for (i = 0; i < len; i++) {
            data.append(dataArray[i].name, dataArray[i].value);
        }

        data.append('member_section', frm.find('select#member_section option:selected').text())

        async_insert_data(data)
            .then((resp) => {
                if (resp.error == 1) {
                    swalalert('error', resp.txt, {
                        auto: false
                    })
                } else {
                    Swal.fire({
                        type: 'success',
                        title: 'สำเร็จ',
                        text: resp.txt,
                        timer: swal_autoClose,
                    }).then((result) => {

                        resetForm()

                    })
                }
            });

    }

</script>