<div class="content">
    <div class="container-fluid">
        <div class="card">

            <div class="card-body tool-btn">
                <h4>quotation</h4>
                <?php if (can(array("quotation.view","bill.view"))) { ?> <button class="btn">ดู quotation</button><?php } ?>
                    
                
                <?php if (can("quotation.insert")) { ?> <button class="btn">เพิ่ม quotation</button><?php } ?>
                <?php if (can("quotation.delete")) { ?> <button class="btn">ลบ quotation</button><?php } ?>
                <?php if (can("quotation.edit")) { ?> <button class="btn">แก้ไข quotation</button><?php } ?>
                <?php if (can("quotation.approve")) { ?> <button class="btn">อนุมัติ quotation</button><?php } ?>
                <?php if (can("quotation.revise")) { ?> <button class="btn">revise quotation</button><?php } ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body tool-btn">
                <h4>bill</h4>
                <?php if (can("bill.view")) { ?> <button class="btn">ดู bill</button><?php } ?>
                <?php if (can("bill.insert")) { ?> <button class="btn">เพิ่ม bill</button><?php } ?>
                <?php if (can("bill.delete")) { ?> <button class="btn">ลบ bill</button><?php } ?>
                <?php if (can("bill.edit")) { ?> <button class="btn">แก้ไข bill</button><?php } ?>
                <?php if (can("bill.approve")) { ?> <button class="btn">อนุมัติ bill</button><?php } ?>
                <?php if (can("bill.revise")) { ?> <button class="btn">revise bill</button><?php } ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body tool-btn">
                <h4>workorder</h4>
                <?php if (can("workorder.view")) { ?> <button class="btn truncate">ดู workorder</button><?php } ?>
                <?php if (can("workorder.insert")) { ?> <button class="btn">เพิ่ม workorder</button><?php } ?>
                <?php if (can("workorder.delete")) { ?> <button class="btn">ลบ workorder</button><?php } ?>
                <?php if (can("workorder.edit")) { ?> <button class="btn">แก้ไข workorder</button><?php } ?>
                <?php if (can("workorder.approve")) { ?> <button class="btn">อนุมัติ workorder</button><?php } ?>
                <?php if (can("workorder.revise")) { ?> <button class="btn">revise workorder</button><?php } ?>
            </div>
        </div>

    </div>

</div>