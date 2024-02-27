<div class="content">
    <input type="hidden" id="hidden_task_id">
    <!-- Start Content-->
    <div class="container-fluid">

        <div class="section-tool d-flex flex-column flex-md-row justify-content-between">

            <div class="">
                <div class="">
                    <p class="text-muted">รายการที่ยังไม่ลงวันจอง
                        <br>
                        <span class="text-secondary">
                            <i class="mdi mdi-checkbox-blank-circle mr-1 vertical-middle"></i>รอโอน
                        </span>
                        <span class="text-warning">
                            <i class="mdi mdi-checkbox-blank-circle mr-1 vertical-middle"></i>มัดจำแล้ว
                        </span>
                        <span class="text-success">
                            <i class="mdi mdi-checkbox-blank-circle mr-1 vertical-middle"></i>โอนแล้ว
                        </span>
                    </p>
                </div>
                <div id="external-events" class="order_list">
                </div>

            </div>

        </div>

        <div class="row">
            <!-- Begin calendar -->
            <div class="col-lg-12">
                <div id="calendar"></div>
            </div>
            <!-- End calendar -->
        </div>

    </div> <!-- end container-fluid -->

</div> <!-- end content -->

<!-- Modal -->
<?php require_once('component/modal_public.php') ?>
<!-- End Modal -->

<?php require_once('script.php') ?>
<script>
    input_int_only()

    function input_int_only() {
        let inputInt = document.querySelectorAll('input.int_only')
        inputInt.forEach(function(item, index) {
            item.addEventListener("keyup", function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
            })
        })
    }

    function updateCalendar() {
        let s = window.jQuery.CalendarApp;
        $('#calendar').fullCalendar('destroy')
        s.init()
    }

    function effect_after_event(string = null) {
        switch (string) {
            case 'update':
                updateCalendar()
                break;
            case 'insert':
                updateCalendar()
                break;
        }

        modalHide()
    }

    $(document).ready(function() {
        $("[name=bookingdate]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
            beforeShow: function(input, inst) {
                    setDatepickerPos(input, inst)
                }
        })
        $("[name=date_order]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
            beforeShow: function(input, inst) {
                    setDatepickerPos(input, inst)
                }
        })
        $('[name=date_order]').datepicker("setDate", new Date());

        $("[name=deposit_date]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
            beforeShow: function(input, inst) {
                    setDatepickerPos(input, inst)
                }
        })

        $("[name=pos_date]").datepicker({
            autoclose: !0,
            todayHighlight: !0,
            dateFormat: 'dd/mm/yy',
            beforeShow: function(input, inst) {
                    setDatepickerPos(input, inst)
                }
        })

        $(".touchspin").TouchSpin({
                min: 1,
                max: 100,
            })

            !(function($) {
                "use strict";

                var CalendarApp = function() {
                    this.$body = $("body");
                    // (this.$modal = $("#event-modal")),
                    (this.$modal = $("#modal_view")),
                    (this.$event = "#external-events div.external-event"),
                    (this.$calendar = $("#calendar")),
                    (this.$saveCategoryBtn = $(".save-category")),
                    (this.$categoryForm = $("#add-category form")),
                    (this.$extEvents = $("#external-events")),
                    (this.$calendarObj = null);
                };

                /* on drop */

                //
                // modify
                /* on Move */
                (CalendarApp.prototype.onMove = function(eventObj, date) {
                    return false
                }),

                /* on click on event */
                (CalendarApp.prototype.onEventClick = function(calEvent, jsEvent, view) {
                    let id = calEvent.ID
                    $(form_name).find(form_hidden_id).val(id)

                    if (calEvent) {
                        modalActive_quotation('view', calEvent)
                    }

                    cal_item_list_view()
                    var $this = this;
                    $this.$modal.modal({
                        backdrop: "false",
                    });

                    return false;

                }),
                /* on select */
                (CalendarApp.prototype.onSelect = function(start, end, allDay) {
                    /* modalActive_quotation('edit', "")
                    var $this = this;
                    $this.$modal.modal({
                        backdrop: "false",
                    });
                    var d = new Date(start);
                    var month = d.getMonth() + 1;
                    var booking_date = `${d.getDate().toString().padStart(2, "0")}/${month.toString().padStart(2, "0")}/${d.getFullYear()}`;

                    $("[name=bookingdate]").val(booking_date);

                    updateCalendar()
                    $this.$calendarObj.fullCalendar("unselect");
                    return false; */
                }),
                (CalendarApp.prototype.enableDrag = function() {
                    //init events
                    $(this.$event).each(function() {
                        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                        // it doesn't need to have a start or end
                        var eventObject = {
                            title: $.trim($(this).text()), // use the element's text as the event title
                        };
                        // store the Event Object in the DOM element so we can get to it later
                        $(this).data("eventObject", eventObject);
                        // make the event draggable using jQuery UI
                        $(this).draggable({
                            zIndex: 999,
                            revert: true, // will cause the event to go back to its
                            revertDuration: 0, //  original position after the drag
                        });
                    });
                });

                function get_calendar_data() {
                    let url = new URL("bill/event_calendar/get_dataCalendar", window.origin);
                    return new Promise((resolve, reject) => {
                        fetch(url)
                            .then((res) => res.json())
                            .then((resp) => {
                                resolve(resp);
                            });
                    });
                }

                /* Initializing */
                (CalendarApp.prototype.init = function() {
                    this.enableDrag();
                    /*  Initialize the calendar  */
                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();
                    var form = "";
                    var today = new Date($.now());

                    var $this = this;

                    // async
                    displayShow();
                    async function displayShow() {
                        let dataBill = await get_calendar_data();

                        let dateDefault = [];
                        if (dataBill) {
                            dataBill.forEach(function(item, index) {
                                if (item.booking_list && item.booking_list.length) {
                                    item.booking_list.forEach(function(b_item, b_index) {

                                        let setArray = [];

                                        let start = item.BOOKING_DATE;
                                        let end = item.BOOKING_DATE;
                                        let booking_dateShow = null;
                                        if (item.BOOKING_DATE || item.BOOKING_DATE != null) {
                                            let dsplit = item.BOOKING_DATE.split("-");
                                            booking_dateShow = dsplit[2] + "/" + dsplit[1] + "/" + dsplit[0];
                                        }

                                        let typeing;
                                        switch (item.PAYMENT_ID) {
                                            case "6":
                                                typeing = "secondary";
                                                break;
                                            case "7":
                                                typeing = "warning";
                                                break;
                                            case "8":
                                                typeing = "success";
                                                break;
                                        }

                                        // setArray = item
                                        setArray['ID'] = item.ID
                                        setArray['AGENT_CONTACT'] = item.AGENT_CONTACT
                                        setArray['AGENT_NAME'] = item.AGENT_NAME
                                        setArray['CODE'] = item.CODE
                                        setArray['COMPLETE_ALIAS'] = item.COMPLETE_ALIAS
                                        setArray['COMPLETE_ID'] = item.COMPLETE_ID
                                        setArray['CUSTOMER_ADDRESS_ADDRESS'] = item.CUSTOMER_ADDRESS_ADDRESS
                                        setArray['CUSTOMER_ADDRESS_ID'] = item.CUSTOMER_ADDRESS_ID
                                        setArray['CUSTOMER_ID'] = item.CUSTOMER_ID
                                        setArray['CUSTOMER_NAME'] = item.CUSTOMER_NAME
                                        setArray['DATE_ORDER'] = item.DATE_ORDER
                                        setArray['DATE_STARTS'] = item.DATE_STARTS
                                        setArray['DATE_UPDATE'] = item.DATE_UPDATE
                                        setArray['DEPOSIT'] = item.DEPOSIT
                                        setArray['DISCOUNT'] = item.DISCOUNT
                                        setArray['ID'] = item.ID
                                        setArray['NET'] = item.NET
                                        setArray['NET_PURE'] = item.NET_PURE
                                        setArray['PAYMENT_ALIAS'] = item.PAYMENT_ALIAS
                                        setArray['PAYMENT_ID'] = item.PAYMENT_ID
                                        setArray['PRICE'] = item.PRICE
                                        setArray['PRICE_NOVAT'] = item.PRICE_NOVAT
                                        setArray['REMARK'] = item.REMARK
                                        setArray['REMARK_DELETE'] = item.REMARK_DELETE
                                        setArray['STATUS'] = item.STATUS
                                        setArray['TOTAL_UNIT'] = item.TOTAL_UNIT
                                        setArray['USER_STARTS'] = item.USER_STARTS
                                        setArray['USER_UPDATE'] = item.USER_UPDATE
                                        setArray['VAT'] = item.VAT
                                        setArray['VATNUM'] = item.VATNUM
                                        setArray['booking_list'] = item.booking_list
                                        setArray['item_list'] = item.item_list

                                        setArray['start'] = b_item.BOOKING_DATE
                                        setArray['end'] = b_item.BOOKING_DATE
                                        setArray['title'] = item.CUSTOMER_NAME
                                        setArray['agent_name'] = item.AGENT_NAME
                                        setArray['agent_contact'] = item.AGENT_CONTACT
                                        setArray['payment_id'] = item.PAYMENT_ID
                                        setArray['booking_date'] = b_item.BOOKING_DATE
                                        setArray['booking_dateShow'] = booking_dateShow
                                        setArray['remark'] = item.REMARK
                                        setArray['round_id'] = b_item.ROUND_ID
                                        setArray['time_start'] = ""
                                        setArray['time_end'] = ""
                                        setArray['className'] = "bg-" + typeing
                                        dateDefault.push(setArray);
                                    })
                                }
                            });
                        }
                        // code working
                        $this.$calendarObj = $this.$calendar.fullCalendar({
                            slotDuration: "00:15:00" /* If we want to split day time each 15minutes */ ,
                            minTime: "08:00:00",
                            maxTime: "19:00:00",
                            defaultView: "month",
                            handleWindowResize: true,
                            height: "auto",
                            // height: $(window).height() - 200,
                            header: {
                                left: "prev,next today",
                                center: "title",
                                right: "month,agendaWeek,agendaDay",
                            },
                            events: function(start, end, tz, callback) {
                                callback(dateDefault);
                            },
                            timeFormat: "H:mm",

                            editable: true,
                            droppable: true, // this allows things to be dropped onto the calendar !!!
                            eventLimit: true, // allow "more" link when too many events
                            selectable: true,
                            drop: function(date) {
                                $this.onDrop($(this), date);
                            },
                            select: function(start, end, allDay) {
                                $this.onSelect(start, end, allDay);
                            },
                            eventClick: function(calEvent, jsEvent, view) {
                                $this.onEventClick(calEvent, jsEvent, view);
                            },
                            eventDrop: function(calEvent, delta, revertFunc) {
                                $this.onMove(calEvent, revertFunc);
                            },
                            eventResize: function(info) {
                                console.log(info);
                            },
                        });
                    }

                    /* var defaultEvents = [
                      {
                        title: "See John Deo",
                        start: today,
                        end: today,
                        className: "bg-success",
                      },
                      {
                        title: "Meet John Deo",
                        start: new Date($.now() + 168000000),
                        className: "bg-info",
                      },
                      {
                        title: "Buy a Theme",
                        start: new Date($.now() + 338000000),
                        className: "bg-primary",
                      },
                    ]; */

                }),
                //init CalendarApp
                ($.CalendarApp = new CalendarApp()),
                ($.CalendarApp.Constructor = CalendarApp);
            })(window.jQuery),
            //initializing CalendarApp
            (function($) {
                "use strict";

                $.CalendarApp.init();
            })(window.jQuery);



        calendar.onMove = function(eventObj, revertFunc) {
            console.log('move')
        }
        calendar.onDrop = function(eventObj, revertFunc) {
            console.log('Drop')
        }
    })
</script>