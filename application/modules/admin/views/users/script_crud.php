<script>
    //  =========================
    //  CRUD
    //  =========================

    //  *
    //  * CRUD
    //  * read
    //  * 
    //  * get data
    //  *
    async function async_get_data(id = null) {
        let url = new URL(path(url_moduleControl + '/get_user'), domain)
        if (id) {
            url.searchParams.append('id', id)
        }

        let response = await fetch(url)
        let result = await response.json()

        return result
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data 
    //  *
    async function async_insert_data(data = []) {
        let url = new URL(path(url_moduleControl + '/insert_data'), domain)

        let body = new FormData();
        if(data.length){
            data.forEach(function(item,index){
                body.append(item.name, item.value)
            })
        }

        let method = {
            'method': 'post',
            'body': body
        }
        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    //  *
    //  * CRUD
    //  * update
    //  * 
    //  * update data 
    //  *
    async function async_update_data(item_id = null, data = []) {
        let url = new URL(path(url_moduleControl + '/update_data'), domain)

        let body = new FormData();
        body.append('item_id', item_id)

        if(data.length){
            data.forEach(function(item,index){
                body.append(item.name, item.value)
            })
        }

        let method = {
            'method': 'post',
            'body': body
        }
        let response = await fetch(url, method)
        let result = await response.json()

        return result
    }

    //  *
    //  * CRUD
    //  * delete
    //  * 
    //  * delete data
    //  *
    async function async_delete_data(item_id = null, remark = null) {
        if (item_id) {

            let url = new URL(path(url_moduleControl + '/delete_data'), domain)

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
</script>