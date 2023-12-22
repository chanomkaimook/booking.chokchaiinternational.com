<!-- start page title -->
<style>
    .page-title-box .page-title {
        font-size: 15px;
        font-weight: unset;
    }
</style>
<div id="topbar" class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);"></a></li>
                    <li class="breadcrumb-item active"></li>
                </ol>
            </div>
            <h4 class="page-title text-capitalize"></h4>
        </div>
    </div>
</div>
<!-- end page title -->

<script>
    $(document).ready(function() {
        let menu = $('.nav-second-level li.mm-active a')
        let main = $('.metismenu > li.mm-active span:first')
        let menuShow = menu.attr('data-show')

        if (menuShow) {
            document.getElementsByClassName('breadcrumb-item')[0].innerHTML = 'Backend'
            document.getElementsByClassName('breadcrumb-item')[1].innerHTML = main.text()
            document.getElementsByClassName('breadcrumb-item')[2].innerHTML = menu.text()
            document.getElementsByClassName('page-title')[0].innerHTML = menuShow
        } else {
            if ($("[name=theme-page-title]").val()) {
                document.getElementsByClassName('page-title')[0].innerHTML = $("[name=theme-page-title]").val()
            }

            if ($("[name=theme-breadcrumb]").val()) {
                document.getElementsByClassName('breadcrumb-item')[0].innerHTML = 'Backend'
                document.getElementsByClassName('breadcrumb-item')[1].innerHTML = $("[name=theme-breadcrumb]").eq(0).val()
                document.getElementsByClassName('breadcrumb-item')[2].innerHTML = $("[name=theme-breadcrumb]").eq(1).val()
            }
        }
    })


    document.getElementsByClassName('breadcrumb-item')[0].innerHTML = '.'
    document.getElementsByClassName('breadcrumb-item')[1].innerHTML = '.'
    document.getElementsByClassName('breadcrumb-item')[2].innerHTML = '.'
    document.getElementsByClassName('page-title')[0].innerHTML = '.'
</script>