<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>Add Asset</li>
            </ol>
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="margin-none">
                        <i class="fa fa-th fa-fw"></i> <?= strtoupper($title); ?>
                    </h4>
                </div>
                <div class="panel-body">
                    <?php $this->load->view('web/includes/message'); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Add Asset ?');" action="<?= base_url('save_asset'); ?>">
                                <div class="row" style="overflow:auto; height:400px;">
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Qr And Bar Code Number</label>
                                        <input type="text" name="qr_and_bar_code_number" class="form-control" placeholder="Enter qr and bar code number" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label class="form-control-label px-3">Rfid Or Id</label>
                                        <input type="text" name="rfid_or_id" class="form-control" placeholder="Enter rfid or id" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset</label>
                                        <input type="text" name="asset" class="form-control" placeholder="Enter asset" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex ">
                                        <label>Sub number</label>
                                        <input type="text" name="subnumber" class="form-control" placeholder="Enter subnumber" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex ">
                                        <label>Fa</label>
                                        <input type="text" name="fa" class="form-control" placeholder="Enter fa" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Class</label>
                                        <input type="text" name="class" class="form-control" placeholder="Enter class" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset class</label>
                                        <input type="text" name="asset_class" class="form-control" placeholder="Enter asset class" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset status</label>
                                        <input type="text" name="asset_status_1" class="form-control" placeholder="Enter Asset Status" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Profit Center </label>
                                        <input type="text" name="profit_center" class="form-control" placeholder="Enter profit center" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>As Per Sap</label>
                                        <input type="text" name="as_per_sap" class="form-control" placeholder="Enter as per sap" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Outlet Type</label>
                                        <input type="text" name="outlet_type" class="form-control" placeholder="Enter outlet type" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Store Name</label>
                                        <input type="text" name="store_name" class="form-control" placeholder="Enter store name" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Date</label>
                                        <input type="text" name="opening_date" class="form-control" placeholder="Enter opening date" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Status 2</label>
                                        <input type="text" name="asset_status_2" class="form-control" placeholder="Enter asset status_2" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Block</label>
                                        <input type="text" name="asset_block" class="form-control" placeholder="Enter asset block" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Brand</label>
                                        <input type="text" name="asset_brand" class="form-control" placeholder="Enter asset brand" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Location</label>
                                        <select class="form-control" name="location_id" required>
                                            <option value="">Select Location </option>
                                            <?php foreach ($page_data as $data) : ?>
                                                <option value="<?= $data->pid ?>"> <?= ucwords($data->location_name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Description</label>
                                        <input type="text" name="asset_description" class="form-control" placeholder="Enter asset description" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Name</label>
                                        <input type="text" name="asset_name" class="form-control" placeholder="Enter asset name" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Type Of Assets</label>
                                        <input type="text" name="type_of_Assets" class="form-control" placeholder="Enter type of Assets" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Capitalized On</label>
                                        <input type="text" name="capitalized_on" class="form-control" placeholder="Enter Capitalized On" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Life</label>
                                        <input type="text" name="life" class="form-control" placeholder="Enter Life" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Balance Life</label>
                                        <input type="text" name="balance_life" class="form-control" placeholder="Enter Balance life" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Days</label>
                                        <input type="text" name="days" class="form-control" placeholder="Enter days" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening</label>
                                        <input type="text" name="opening" class="form-control" placeholder="Enter opening" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Cwip Capitalized</label>
                                        <input type="text" name="cwip_capitalized" class="form-control" placeholder="Enter cwip capitalized" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Add Asset</label>
                                        <input type="text" name="add_asset" class="form-control" placeholder="Enter add asset" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Transfer</label>
                                        <input type="text" name="transfer" class="form-control" placeholder="Enter transfer" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Del</label>
                                        <input type="text" name="del" class="form-control" placeholder="Enter del" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>W Off</label>
                                        <input type="text" name="w_off" class="form-control" placeholder="Enter w off" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Net Block</label>
                                        <input type="text" name="net_block" class="form-control" placeholder="Enter net block" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Fy Start</label>
                                        <input type="text" name="dep_fy_start" class="form-control" placeholder="Enter dep fy start" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Transfer</label>
                                        <input type="text" name="dep_transfer" class="form-control" placeholder="Enter dep transfer" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep For The Year</label>
                                        <input type="text" name="dep_for_the_year" class="form-control" placeholder="Enter dep for the year" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Retir</label>
                                        <input type="text" name="dep_retir" class="form-control" placeholder="Enter dep retir" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Accumul Dep</label>
                                        <input type="text" name="accumul_dep" class="form-control" placeholder="Enter accumul dep" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Impairment</label>
                                        <input type="text" name="opening_impairment" class="form-control" placeholder="Enter opening impairment" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Transfer</label>
                                        <input type="text" name="impairment_transfer" class="form-control" placeholder="Enter impairment transfer" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Charges</label>
                                        <input type="text" name="impairment_charges" class="form-control" placeholder="Enter impairment charges" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Charges2</label>
                                        <input type="text" name="impairment_charges2" class="form-control" placeholder="Enter impairment charges2" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Imapirment Reversal</label>
                                        <input type="text" name="imapirment_reversal" class="form-control" placeholder="Enter imapirment reversal" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Accumul Impairment</label>
                                        <input type="text" name="accumul_impairment" class="form-control" placeholder="Enter accumul impairment" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Curr Bk Val</label>
                                        <input type="text" name="Curr_bk_val" class="form-control" placeholder="Enter Curr bk val" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Wdv After Impairment</label>
                                        <input type="text" name="wdv_after_impairment" class="form-control" placeholder="Enter wdv after impairment" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Wdv</label>
                                        <input type="text" name="opening_wdv" class="form-control" placeholder="Enter opening wdv" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Wdv After Impairment</label>
                                        <input type="text" name="opening_wdv_after_impairment" class="form-control" placeholder="Enter Opening Wdv After Impairment" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening wdv After Impairment</label>
                                        <input type="text" name="opening_wdv_after_impairment" class="form-control" placeholder="Enter opening wdv after impairment" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Quantity</label>
                                        <input type="text" name="quantity" class="form-control" placeholder="Enter quantity" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Deactivation On</label>
                                        <input type="text" name="deactivation_on" class="form-control" placeholder="Enter deactivation on" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Life Used</label>
                                        <input type="text" name="life_used" class="form-control" placeholder="Enter life used" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep</label>
                                        <input type="text" name="dep" class="form-control" placeholder="Enter dep" required maxlength="150">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Historical Wdv</label>
                                        <input type="text" name="historical_wdv" class="form-control" placeholder="Enter historical wdv" required maxlength="150">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success" name="add">Add</button>
                                <button type="reset" class="btn btn-info">Clear</button>
                            </form>
                        </div>

                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->