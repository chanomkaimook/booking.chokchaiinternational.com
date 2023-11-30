<script>
    //  =========================
    //  CRUD
    //  =========================

    // 
    //  Get data ticket
    //

    // 
    //  Insert data ticket
    //
    async function async_insert_data(data = []) {
        let url = new URL(path('ticket/ctl_ticket/insert_data'), domain)

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    // 
    // revise data ticket
    // 
    async function async_revise_data(data = []) {
        let url = new URL(path('ticket/ctl_ticket/revise_data'), domain)

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    // 
    // comment data ticket
    // 
    async function async_comment_data(data = []) {
        let url = new URL(path('ticket/ctl_ticket/insert_comment_data'), domain)

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    // 
    //  Update data ticket
    //
    async function async_update_data(data = []) {
        let url = new URL(path('ticket/ctl_ticket/update_data'), domain)

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    // 
    //  Delete data ticket
    //
    async function async_delete_data(item_id = null, remark = null) {
        if (item_id) {

            let url = new URL(path('ticket/ctl_ticket/delete_data'), domain)

            var data = new FormData()
            data.append('item_id', item_id)
            data.append('item_remark', remark)

            let method = {
                'method': 'post',
                'body': data
            }

            let response = await fetch(url, method)
            let result = await response.json()

            return result
        }
    }

    // 
    //  Update data ticket defect
    //
    async function async_update_datadefect(data = []) {
        let url = new URL(path('ticket/ctl_ticket/update_ticketDefect'), domain)

        let method = {
            'method': 'post',
            'body': data
        }

        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }
</script>