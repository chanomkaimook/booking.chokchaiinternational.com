<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Begin SEO -->
    <!-- 
        ** english typing uppercase first word
        ** should be search content only
        ** no less word or junk word
        meta:description (max=160 word)
        meta:keywords (comma)
        meta:robots
            - index = web crawlers pass to keep data
            - noindex = web crawlers not pass come keep data
            - follow = search engine follow link on website (backlink)
            - nofollow = search engine not follow link on website
            - noarchive = not passing search engine
        meta:viewport
        meta:author
        title (max:60 word)
        link:rel:canonical (reference webUrl same to same)
     -->
    <!-- End SEO -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to farmchokchai. This is booking talonfarm จองตั๋วตะลอนฟาร์มกับฟาร์มโชคชัย">
    <meta name="keywords" content="Farmchokchai,Farmchokchai,Talonfarm,Farmchokchai Booking">

    <!-- robot google passing to get data (content="noindex" not passing)-->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- author -->
    <meta name="author" content="Farmchokchai.com">
    <title>จองเข้าชมตะลอนฟาร์ม ฟาร์มโชคขัย Farmchokchai Talonfarm</title>

    <link rel="canonical" href="">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="asset/style.css">
    <link rel="stylesheet" href="asset/fontawesome-free-6.5.2-web/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container-fluid px-0 bg_hero">

        <div class="d-flex">
            <!-- Begin Hero -->
            <div class="d-none d-lg-block col-lg">
                <div class="row align-items-center vh-100 text-center text-white">
                    <div class="col">
                        <div class="titleside_hero" style="display:none">
                            <p class="h2">ตะลอนฟาร์ม</p>
                            <p class="h2">Talon Farm</p>
                            <p class="fs-4">แบบฟอร์มจองเข้าชมออนไลน์</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Hero -->

            <!-- Begin Form -->
            <div class="col">
                <div class="container">

                    <div class="title_hero text-center text-white" style="display:none">
                        <p class="h2">ตะลอนฟาร์ม</p>
                        <p class="h2">Talon Farm</p>
                        <p class="fs-6">แบบฟอร์มจองเข้าชมออนไลน์</p>
                    </div>

                    <!-- Begin block from -->
                    <div class="lead">
                        <div class="row align-items-center vh-100">
                            <div class="col-md-9 offset-md-1">

                                <form action="" method="post" id="frm_booking">
                                    <div class="card border-0 shadow-lg" style="display:none">
                                        <div class="card-body">

                                            <!-- Begin Data contact -->
                                            <div class="mb-4">
                                                <div class="input-group rounded-pill bg-white p-2">
                                                    <span class="input-group-append">
                                                        <button class="btn border-0 rounded-pill btn-success" type="button">
                                                            <i class="fa-solid fa-user"></i>
                                                        </button>
                                                    </span>
                                                    <input class="form-control border-0 rounded-pill" type="search" value="" id="cus_contact_name" placeholder="ชื่อผู้ติดต่อ" required>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="input-group rounded-pill bg-white p-2">
                                                    <span class="input-group-append">
                                                        <button class="btn border-0 rounded-pill btn-success" type="button">
                                                            <i class="fa-solid fa-phone"></i>
                                                        </button>
                                                    </span>
                                                    <input class="form-control border-0 rounded-pill" type="search" value="" id="cus_contact_phone" placeholder="เบอร์ติดต่อกลับ" required>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="input-group rounded-pill bg-white p-2">
                                                    <span class="input-group-append">
                                                        <button class="btn border-0 rounded-pill btn-success" type="button">
                                                            <i class="fa-brands fa-line"></i>
                                                        </button>
                                                    </span>
                                                    <input class="form-control border-0 rounded-pill" type="search" value="" id="cus_contact_line" placeholder="Line ติดต่อกลับ" required>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="input-group rounded-pill bg-white p-2">
                                                    <span class="input-group-append">
                                                        <button class="btn border-0 rounded-pill btn-success" type="button">
                                                            <i class="fa-solid fa-envelope"></i>
                                                        </button>
                                                    </span>
                                                    <input class="form-control border-0 rounded-pill" type="search" value="" id="cus_contact_line" placeholder="E-mail ติดต่อกลับ">
                                                </div>
                                            </div>
                                            <!-- End Data contact -->

                                            <!-- Begin Additional data contact-->
                                            <div class="row mb-4">
                                                <div class="col-12">

                                                    <div class="card">
                                                        <div class="card-body ">
                                                            <p class="small">ระบุข้อมูลเพิ่มเติมการจอง</p>
                                                            <div class="mb-2">
                                                                <div class="input-group ">
                                                                    <select class="form-select border-0 rounded-pill" id="cus_organize_1">
                                                                        <option value="" selected>เลือกองค์กร</option>
                                                                        <option value="1">สถานศึกษา</option>
                                                                        <option value="2">หน่วยงานราชการ</option>
                                                                        <option value="3">บริษัทเอกชน</option>
                                                                        <option value="4">กรุ๊ปทัวร์</option>
                                                                        <option value="5">อื่นๆ</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="mb-2">
                                                                <div class="row">
                                                                    <div class="col-12 text-center">
                                                                        <div class="fs-2 text-white btn_add_booking">
                                                                            <i id="btn_add_booking" class="fa-solid fa-circle-plus" role="button"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Begin Customer bookings -->
                                                            <!-- Being Booking 1 -->
                                                            <div class="cus_booking cus_booking" data-block="1">
                                                                <div class="mb-4">
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <select class="form-select border-0 rounded-pill" id="cus_type_1">
                                                                                <option value="" selected>ประเภทผู้เข้าชม</option>
                                                                                <option value="1">ครู</option>
                                                                                <option value="2">นักเรียน</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-4">
                                                                            <input class="form-control border-0 rounded-pill" type="text" value="" id="cus_total_1" placeholder="จำนวน">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Booking 1 -->

                                                            <!-- Being Booking 2 -->
                                                            <div class="cus_booking cus_booking_hide" data-block="2" style="display:none">
                                                                <div class="mb-4">
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <select class="form-select border-0 rounded-pill" id="cus_type_2">
                                                                                <option value="" selected>ประเภทผู้เข้าชม</option>
                                                                                <option value="1">ครู</option>
                                                                                <option value="2">นักเรียน</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-4">
                                                                            <input class="form-control border-0 rounded-pill" type="text" value="" id="cus_total_2" placeholder="จำนวน">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Booking 2 -->

                                                            <!-- Being Booking 3 -->
                                                            <div class="cus_booking cus_booking_hide" data-block="3" style="display:none">
                                                                <div class="mb-4">
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <select class="form-select border-0 rounded-pill" id="cus_type_3">
                                                                                <option value="" selected>ประเภทผู้เข้าชม</option>
                                                                                <option value="1">ครู</option>
                                                                                <option value="2">นักเรียน</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-4">
                                                                            <input class="form-control border-0 rounded-pill" type="text" value="" id="cus_total_3" placeholder="จำนวน">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Booking 3 -->
                                                            <!-- End Customer bookings -->

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- End Additional data contact-->

                                            <button class="btn btn-success btn_submit btn-secondary rounded-pill w-100" type="submit">ส่งแบบฟอร์ม</button>

                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- End block from -->

            </div>
        </div>
        <!-- End Form -->
    </div>
    </div>

    <script>
        $(function() {

            // lg = >= 992
            if($( document ).width() <= 991){
                $(".title_hero").fadeIn('slow');
            }else{
                $(".titleside_hero").fadeIn('slow');
            }
            

            setTimeout(() => {

                $(".card").fadeIn('slow');
            }, 200);

            let btnAddBooking = "#btn_add_booking"
            let customerBookingBlock = ".cus_booking"

            $(btnAddBooking).on("click", function() {
                showAdditionalBookings()
            });

            function showAdditionalBookings() {
                let blockShow = $(".cus_booking_hide")
                let countBlockShow = blockShow.length

                if (countBlockShow) {
                    if (countBlockShow == 2) {
                        method_show_addi_booking(2)
                    } else {
                        method_show_addi_booking(3)

                        method_close_btn_add_booking()
                    }
                }
                console.log(countBlockShow)
            }

            function method_show_addi_booking(dataid) {
                $(customerBookingBlock + '[data-block=' + dataid + ']')
                    .fadeIn()
                    .removeClass('cus_booking_hide')
            }

            function method_close_btn_add_booking() {
                $('.btn_add_booking').addClass('d-none')
                // $('.btn_add_booking').fadeOut('fast') 
            }
        })
    </script>
</body>

</html>