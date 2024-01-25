<script>
    // calendar.init
    let calendar = window.jQuery.CalendarApp;

    calendar.onMove = function(eventObj, revertFunc) {
        var d = new Date(eventObj.start);
        var dateString = d.toDateString();

        Swal.fire(
            swal_setConfirm("ยืนยันการจอง", "จองรายการในวันที่ " + dateString)
        ).then((result) => {
            if (result.value) {

                var currentDate = d;

                var dataDate = currentDate.toJSON().slice(0, 10);

                eventSubmit(eventObj.id, dataDate)
                    .then((resp) => {
                        if (resp.error) {
                            swalalert('error', resp.txt, {
                                auto: false
                            })
                            revertFunc()
                        } else {
                            updateCalendar();
                        }
                    })
            } else {
                revertFunc()
            }
        })
    }

    calendar.onDrop = function(eventObj, date) {
        var d = new Date(date);
        var dateString = d.toDateString();

        Swal.fire(
            swal_setConfirm("ยืนยันการจอง", "จองรายการในวันที่ " + dateString)
        ).then((result) => {
            if (result.value) {

                var currentDate = new Date(date);

                var dataDate = currentDate.toJSON().slice(0, 10);

                eventSubmit(eventObj.attr("data-id"), dataDate)
                    .then((resp) => {
                        if (resp.error) {
                            swalalert('error', resp.txt, {
                                auto: false
                            })
                        } else {
                            updateCalendar();
                        }
                    })
            }
        })
    }

    async function eventSubmit(item_id, date) {
        let url = new URL(
            "calendar/ctl_manage/update_bill_booking",
            window.origin
        );

        let data = new FormData();
        data.append("item_id", item_id);
        data.append("booking_date", date);

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }
</script>