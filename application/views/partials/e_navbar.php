         <style>
             .gap-1 {
                 gap: 10px;
             }
             .gap-2 {
                 gap: 20px;
             }

             .gap-3 {
                 gap: 30px;
             }

             .gap-4 {
                 gap: 40px;
             }

             /* section toolbar for button */
             .section-tool {
                 margin-bottom: 0.5rem;
             }

             .content-page {
                 min-height: 88vh
             }

             /* form */
             .required {
                 color: red;
             }

             .bg-information {
                 background: #64c5b1;
                 color: #fff;
             }

             body {
                 padding-bottom: 15px !important;
             }

             body.enlarged {
                 min-height: unset;
             }

             .truncate {
                 max-width: inherit;
                 white-space: nowrap;
                 overflow: hidden;
                 text-overflow: ellipsis;
             }
         </style>

         <input type="hidden" id="hidden_user_id" value="<?= $this->session->userdata('user_code'); ?>">

         <!-- Topbar Start -->
         <div class="navbar-custom">
             <ul class="list-unstyled topnav-menu float-right mb-0">
                 <li class="dropdown notification-list dropdown d-lg-inline-block ml-2">
                     <a class="nav-link dropdown-toggle mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                         <?php if ($_COOKIE['langadmin'] == 'english') { ?>
                             <img src="<?= base_url() ?>/asset/images/flags/1x1/us.svg" alt="lang-image" height="20">
                         <?php } else {  ?>
                             <img src="<?= base_url() ?>/asset/images/flags/1x1/th.svg" alt="lang-image" height="20">
                         <?php } ?>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                         <!-- item-->
                         <a onclick="setCookieLanguage('langadmin','thai',365);" data-lang="thailand" class="dropdown-item notify-item">
                             <img src="<?= base_url() ?>/asset/images/flags/1x1/th.svg" alt="lang-image" class="mr-1" height="20"> <span class="align-middle text-capitalize">thailand</span>
                         </a>

                         <!-- item-->
                         <a onclick="setCookieLanguage('langadmin','english',365);" data-lang="english" class="dropdown-item notify-item">
                             <img src="<?= base_url() ?>/asset/images/flags/1x1/us.svg" alt="lang-image" class="mr-1" height="20"> <span class="align-middle text-capitalize">united states</span>
                         </a>

                     </div>
                 </li>

                 <li class="dropdown notification-list d-none">
                     <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                         <i class="dripicons-bell noti-icon"></i>
                         <span class="badge badge-pink rounded-circle noti-icon-badge">4</span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                         <div class="dropdown-header noti-title">
                             <h5 class="text-overflow m-0"><span class="float-right">
                                     <span class="badge badge-danger float-right">5</span>
                                 </span>Notification</h5>
                         </div>

                         <div class="slimscroll noti-scroll">

                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                                 <p class="notify-details">Robert S. Taylor commented on Admin<small class="text-muted">1 min ago</small></p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-primary">
                                     <i class="mdi mdi-settings-outline"></i>
                                 </div>
                                 <p class="notify-details">New settings
                                     <small class="text-muted">There are new settings available</small>
                                 </p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-warning">
                                     <i class="mdi mdi-bell-outline"></i>
                                 </div>
                                 <p class="notify-details">Updates
                                     <small class="text-muted">There are 2 new updates available</small>
                                 </p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon">
                                     <img src="<?= base_url('asset/images/users/avatar-4.jpg') ?>" class="img-fluid rounded-circle" alt="" />
                                 </div>
                                 <p class="notify-details">Karen Robinson</p>
                                 <p class="text-muted mb-0 user-msg">
                                     <small>Wow ! this admin looks good and awesome design</small>
                                 </p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-danger">
                                     <i class="mdi mdi-account-plus"></i>
                                 </div>
                                 <p class="notify-details">New user
                                     <small class="text-muted">You have 10 unread messages</small>
                                 </p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-info">
                                     <i class="mdi mdi-comment-account-outline"></i>
                                 </div>
                                 <p class="notify-details">Caleb Flakelar commented on Admin
                                     <small class="text-muted">4 days ago</small>
                                 </p>
                             </a>

                             <!-- item-->
                             <a href="javascript:void(0);" class="dropdown-item notify-item">
                                 <div class="notify-icon bg-secondary">
                                     <i class="mdi mdi-heart"></i>
                                 </div>
                                 <p class="notify-details">Carlos Crouch liked
                                     <b>Admin</b>
                                     <small class="text-muted">13 days ago</small>
                                 </p>
                             </a>
                         </div>

                         <!-- All-->
                         <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                             View all
                             <i class="fi-arrow-right"></i>
                         </a>

                     </div>
                 </li>

                 <style>
                     .icon-user {
                         font-size: 1.2rem;
                     }
                 </style>
                 <li class="dropdown notification-list">
                     <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                         <!-- <img src="<?= base_url('asset/images/users/avatar-1.jpg') ?>" alt="user-image" class="rounded-circle"> -->
                         <span class="icon-user"></span>
                         <span class="pro-user-name ml-1">
                             <?php
                                echo $this->session->userdata('user_name');
                                ?> <i class="mdi mdi-chevron-down"></i>
                         </span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                         <!-- item-->
                         <div class="dropdown-header noti-title">
                             <h6 class="text-overflow m-0">Welcome</h6>
                         </div>

                         <!-- item-->
                         <a href="<?= site_url('profile/ctl_page/') ?>" class="dropdown-item notify-item">
                             <i class="fe-user"></i>
                             <span>Profile</span>
                         </a>


                         <div class="dropdown-divider"></div>

                         <!-- item-->
                         <a href="<?= site_url('login/ctl_logout/') ?>" class="dropdown-item notify-item">
                             <i class="fe-log-out"></i>
                             <span>Logout</span>
                         </a>

                     </div>
                 </li>
             </ul>

             <!-- LOGO -->

             <div class="logo-box">
                 <div class="logo text-center">
                     <span class="logo-lg">
                         <span style="font-size: 22px;"></span>
                     </span>
                     <span class="logo-sm">
                     </span>
                 </div>
             </div>

             <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                 <li>
                     <button class="button-menu-mobile waves-effect waves-light" onclick="setCookieToggleMenu()">
                         <i class="fe-menu"></i>
                     </button>
                 </li>
             </ul>


         </div>
         <!-- end Topbar -->

         <script>
             // check toggle menu
             checkCookieToggleMenu()

             // =======
             let logoText = "Backend"
             let menuTitle = "menu bar" // e_sidebar

             // default language
             let setlang = 'thai'
             if (getCookie("langadmin") != setlang) {
                 setlang = getCookie("langadmin")
             }

             //
             // Language
             let table_column_view = {
                 'thai': 'รายละเอียด',
                 'english': 'view'
             }
             let table_column_edit = {
                 'thai': 'แก้ไข',
                 'english': 'edit'
             }
             let table_column_del = {
                 'thai': 'ลบรายการ',
                 'english': 'delete'
             }
             //
             //
             function textCapitalize(text) {
                 let result = ""
                 if (text) {
                     result = text.charAt(0).toUpperCase() + text.slice(1)
                 }
                 return result
             }

             document.getElementsByClassName('logo-lg')[0].getElementsByTagName('span')[0].innerHTML = logoText
             // =======
             // =======
             let url_host = window.location.host;
             let url_current = window.location.href;
             let url_length = url_current.indexOf(url_host);
             let url_newCurrent = url_current.slice(url_length);
             let url_split = url_newCurrent.split("/");

             // url module/controller
             let url_moduleControl = `${url_split[1]}/${url_split[2]}`;
             // =======
             // =======
             let domain = window.location.origin
             let table_toolbar_name = 'toolbar'
             let table_toolbar = '#datatable_wrapper div.' + table_toolbar_name
             let datatable_dom = "<'row'<'col-sm-6 btn-sm'B><'col-sm-6 'f>>" +
                 "<'row'<'col-sm-12 small'tr>>" +
                 "<'row'<'col-sm-4 small'i><'col-sm-4 d-flex justify-content-center small'l><'col-sm-4 small'p>>"
             let datatable_button = [{
                     extend: 'print',
                     exportOptions: {
                         columns: ':visible:not(:last-child)'
                         //  columns: [1, 3]
                     }
                 },
                 {
                     extend: 'collection',
                     text: 'Export',
                     buttons: ['excel', 'pdf', 'copy'],
                     fade: true,
                     style: 'padding-top:0px'
                 },
                 {
                     extend: 'collection',
                     text: 'Tool',
                     buttons: ['columnsToggle', 'colvisRestore'],
                     fade: true
                 },
                 {
                     text: '<i class="fas fa-redo-alt"></i>',
                     className: '',
                     titleAttr: 'reload',
                     action: function(e, dt, node, config) {
                         //
                         //	API reload(callback,resetPaging [default true,false])
                         //
                         dt.ajax.reload();
                         // dt.ajax.reload(null, false);
                     }
                 },
             ]

             let loading = `<div class="sk-circle loading">
                                        <div class="sk-circle1 sk-child"></div>
                                        <div class="sk-circle2 sk-child"></div>
                                        <div class="sk-circle3 sk-child"></div>
                                        <div class="sk-circle4 sk-child"></div>
                                        <div class="sk-circle5 sk-child"></div>
                                        <div class="sk-circle6 sk-child"></div>
                                        <div class="sk-circle7 sk-child"></div>
                                        <div class="sk-circle8 sk-child"></div>
                                        <div class="sk-circle9 sk-child"></div>
                                        <div class="sk-circle10 sk-child"></div>
                                        <div class="sk-circle11 sk-child"></div>
                                        <div class="sk-circle12 sk-child"></div>
                                    </div>`

             let swal_autoClose = 2000
             let swal_confirmButton = '#64c5b1'
             let swal_cancelButton = '#f96a74'
             let swal_confirmText = 'ยืนยัน'
             let swal_cancelText = 'ยกเลิก'
             // =======
             // =======
             function swal_setConfirm(title = 'ยืนยันการลบ', text = 'ต้องการลบข้อมูลนี้') {
                 return {
                     title: title,
                     text: text,
                     type: 'question',
                     showCancelButton: true,
                     confirmButtonColor: swal_confirmButton,
                     cancelButtonColor: swal_cancelButton,
                     confirmButtonText: swal_confirmText,
                     cancelButtonText: swal_cancelText,
                     allowOutsideClick: () => !Swal.isLoading()
                 }
             }

             function swal_setConfirmInput(title = 'ยืนยันการลบ', text = 'ระบุหมายเหตุเพื่อลบข้อมูลนี้') {
                 return {
                     title: title,
                     text: text,
                     type: 'question',
                     input: 'textarea',
                     inputAttributes: {
                         autocapitalize: 'off'
                     },
                     showCancelButton: true,
                     confirmButtonColor: swal_confirmButton,
                     cancelButtonColor: swal_cancelButton,
                     confirmButtonText: swal_confirmText,
                     cancelButtonText: swal_cancelText,
                     allowOutsideClick: () => !Swal.isLoading()
                 }
             }

             function swalalert(type = 'success', text = 'ทำรายการสำเร็จ', optional = {
                 auto: true
             }) {

                 let timeclose_total = swal_autoClose
                 let title = setlang == 'thai' ? 'สำเร็จ' : 'Success'

                 if (optional.auto == false) {
                     timeclose_total = null
                 }

                 if (type == 'warning') {
                     title =  setlang == 'thai' ? 'แจ้งเตือน' : 'Warning'
                 }

                 if (type == 'error') {
                     title =  setlang == 'thai' ? 'ไม่สำเร็จ' : 'Fail'
                 }

                 return Swal.fire({
                     type: type,
                     title: title,
                     text: text,
                     timer: timeclose_total,
                 })
             }

             let datatable_searchdelay_time = 1200
             //	convert thai date
             //	@param	date	@date = date yyyy-mm-dd
             //	@param	typereturn	@text = [date , datetime]
             //	return datetime TH
             //
             function toThaiDateTimeString(dateset, typereturn) {
                 let monthNames = [
                     "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน",
                     "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม.",
                     "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
                 ];
                 let monthNamesIndent = [
                     "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.",
                     "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.",
                     "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค."
                 ];


                 let date = new Date(dateset)
                 let year = date.getFullYear() + 543;

                 let month = monthNames[date.getMonth()];
                 let monthIndent = monthNamesIndent[date.getMonth()];
                 let numOfDay = date.getDate();
                 // console.log(date + "--" + typereturn);
                 let hour = date.getHours().toString().padStart(2, "0");
                 let minutes = date.getMinutes().toString().padStart(2, "0");
                 let second = date.getSeconds().toString().padStart(2, "0");

                 switch (typereturn) {
                     case 'datetime':
                         return `${numOfDay} ${month} ${year} ` +
                             `${hour}:${minutes}:${second} น.`;
                         break;
                     case 'date':
                         return `${numOfDay} ${month} ${year} `;
                         break;
                     case 'dateindent':
                         return `${numOfDay} ${monthIndent} ${year} `;
                         break;
                     case 'datetimeindent':
                         return `${numOfDay} ${monthIndent} ${year} ` +
                             `${hour}:${minutes}:${second} น.`;
                         break;
                     default:
                         return `${numOfDay} ${month} ${year} ` +
                             `${hour}:${minutes}:${second} น.`;
                         break;
                 }

             }


             //
             // data system update
             /* updateSystem()

             function updateSystem() {
                 update_doc_waite()
                     .then(res => res.json())
                     .then((resp) => {
                         if (resp.total > 0) {
                             document.querySelector('.total_doc_waite').innerHTML = resp.total
                             document.querySelector('.total_doc_waite').classList.remove("d-none")
                         }else{
                              document.querySelector('.total_doc_waite').classList.add("d-none")
                         }
                     })
             }

             async function update_doc_waite() {
                 let url = new URL('realdata/ctl_data/get_doc_waite', domain);
                 let result = await fetch(url);

                 return result
             } */

             // return path

             function path(name = null) {
                 let pathname = window.location.origin;
                 if (name) {
                     pathname = pathname + '/' + name
                 }

                 return pathname
             }

             // height value data table
             // fix for datatable height to fit
             function dataTableHeight() {

                 let screenHeight = parseInt($('.content-page').height())
                 let topbarHeight = parseInt($('#topbar').height())
                 let sectionHeight = parseInt($('.section-tool').height())
                 let dataTableHeight = screenHeight - topbarHeight - sectionHeight - 190

                 return dataTableHeight + 'px'
             }

             //  
             //  SET COOKIE
             // 
             function setCookie(cname, exdays, cvalue = null) {
                 const d = new Date();
                 d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
                 //  d.setTime(d.getTime() + 10000);
                 let expires = "expires=" + d.toUTCString();
                 document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                 console.log('create')
             }

             function getCookie(cname) {
                 let name = cname + "=";
                 let decodedCookie = decodeURIComponent(document.cookie);
                 let ca = decodedCookie.split(";");
                 for (let i = 0; i < ca.length; i++) {
                     let c = ca[i];
                     while (c.charAt(0) == " ") {
                         c = c.substring(1);
                     }

                     if (c.indexOf(name) == 0) {
                         return c.substring(name.length, c.length);
                     }
                 }

                 return "";
             }

             function checkCookieToggleMenu() {
                 let toggleSidebar = document.body.className ?
                     document.body.className :
                     "fullshow";

                 let user = getCookie("toggleMenu");
                 if (user && user != "fullshow") {
                     // class="enlarged" data-keep-enlarged="true"

                     document.body.classList.add("enlarged");
                     document.body.setAttribute("data-keep-enlarged", "true");
                 } else {
                     /* user = prompt("Please enter your name:", "");
                         if (user != "" && user != null) {
                             setCookie("toggleMenu", 30, user);
                         } */
                 }
             }

             function setCookieToggleMenu() {
                 let toggleSidebar = document.body.className ? "fullshow" : "enlarged";
                 setCookie("toggleMenu", 30, toggleSidebar);
             }

             function setCookieLanguage(name, value, days) {
                 if (days) {
                     const d = new Date();
                     d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
                     //  d.setTime(d.getTime() + 10000);
                     let expires = "expires=" + d.toUTCString();
                     document.cookie = name + "=" + value + ";" + expires + ";path=/";
                 }
                 location.reload();
             }
             //  
             //
         </script>