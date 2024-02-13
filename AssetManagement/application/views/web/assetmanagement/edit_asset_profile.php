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
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Update Asset ?');" action="<?= base_url('update_asset_profile'); ?>">
                                <div class="row" style="overflow:auto; height:500px;">
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <input type="hidden" name="asset_id" value="<?= $page_data->tid; ?> ">
                                        <label>Qr And Bar Code Number</label>
                                        <input type="text" name="qr_and_bar_code_number" class="form-control" placeholder="Enter qr_and_bar_code_number" required maxlength="150" value="<?= ucwords($page_data->qr_and_bar_code_number); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label class="form-control-label px-3">Rfid Or Id</label>
                                        <input type="text" name="rfid_or_id" class="form-control" placeholder="Enter rfid_or_id" required maxlength="150" value="<?= ucwords($page_data->rfid_or_id); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset</label>
                                        <input type="text" name="asset" class="form-control" placeholder="Enter asset" required maxlength="150" value="<?= ucwords($page_data->asset); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex ">
                                        <label>Sub number</label>
                                        <input type="text" name="subnumber" class="form-control" placeholder="Enter subnumber" required maxlength="150" value="<?= ucwords($page_data->subnumber); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex ">
                                        <label>Fa</label>
                                        <input type="text" name="fa" class="form-control" placeholder="Enter fa" required maxlength="150" value="<?= ucwords($page_data->fa); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Class</label>
                                        <input type="text" name="class" class="form-control" placeholder="Enter class" required maxlength="150" value="<?= ucwords($page_data->class); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset class</label>
                                        <input type="text" name="asset_class" class="form-control" placeholder="Enter asset_class" required maxlength="150" value="<?= ucwords($page_data->asset_class); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset status 1</label>
                                        <input type="text" name="asset_status_1" class="form-control" placeholder="Enter Asset Status" required maxlength="150" value="<?= ucwords($page_data->asset_status_1); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Profit Center </label>
                                        <input type="text" name="profit_center" class="form-control" placeholder="Enter profit center" required maxlength="150" value="<?= ucwords($page_data->profit_center); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>As Per Sap</label>
                                        <input type="text" name="as_per_sap" class="form-control" placeholder="Enter as_per_sap" required maxlength="150" value="<?= ucwords($page_data->as_per_sap); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Outlet Type</label>
                                        <input type="text" name="outlet_type" class="form-control" placeholder="Enter outlet_type" required maxlength="150" value="<?= ucwords($page_data->outlet_type); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Store Name</label>
                                        <input type="text" name="store_name" class="form-control" placeholder="Enter store_name" required maxlength="150" value="<?= ucwords($page_data->store_name); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Date</label>
                                        <input type="text" name="opening_date" class="form-control" placeholder="Enter opening_date" required maxlength="150" value="<?= ucwords($page_data->opening_date); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Status 2</label>
                                        <input type="text" name="asset_status_2" class="form-control" placeholder="Enter asset_status_2" required maxlength="150" value="<?= ucwords($page_data->asset_status_2); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Block</label>
                                        <input type="text" name="asset_block" class="form-control" placeholder="Enter asset_block" required maxlength="150" value="<?= ucwords($page_data->asset_block); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Brand</label>
                                        <input type="text" name="asset_brand" class="form-control" placeholder="Enter asset_brand" required maxlength="150" value="<?= ucwords($page_data->asset_brand); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Location Name</label>
                                            <select class="form-control" name="location_id" required>
                                                <option value="">Select Location </option>
                                                <?php foreach ($location_data as $data) : ?>
                                                    <option value="<?= $data->pid ?>" <?= selectedValue($page_data->location_id, $data->pid) ?>> <?= ucwords($data->location_name); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Description</label>
                                        <input type="text" name="asset_description" class="form-control" placeholder="Enter asset_description" required maxlength="150" value="<?= ucwords($page_data->asset_description); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Asset Name</label>
                                        <input type="text" name="asset_name" class="form-control" placeholder="Enter asset_name" required maxlength="150" value="<?= ucwords($page_data->asset_name); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Type Of Assets</label>
                                        <input type="text" name="type_of_Assets" class="form-control" placeholder="Enter type_of_Assets" required maxlength="150" value="<?= ucwords($page_data->type_of_Assets); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Capitalized On</label>
                                        <input type="text" name="capitalized_on" class="form-control" placeholder="Enter Capitalized On" required maxlength="150" value="<?= ucwords($page_data->type_of_Assets); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Life</label>
                                        <input type="text" name="life" class="form-control" placeholder="Enter life" required maxlength="150" value="<?= ucwords($page_data->life); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Balance Life</label>
                                        <input type="text" name="balance_life" class="form-control" placeholder="Enter balance_life" required maxlength="150" value="<?= ucwords($page_data->balance_life); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Days</label>
                                        <input type="text" name="days" class="form-control" placeholder="Enter days" required maxlength="150" value="<?= ucwords($page_data->days); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening</label>
                                        <input type="text" name="opening" class="form-control" placeholder="Enter opening" required maxlength="150" value="<?= ucwords($page_data->opening); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Cwip Capitalized</label>
                                        <input type="text" name="cwip_capitalized" class="form-control" placeholder="Enter cwip_capitalized" required maxlength="150" value="<?= ucwords($page_data->cwip_capitalized); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Add Asset</label>
                                        <input type="text" name="add_asset" class="form-control" placeholder="Enter add_asset" required maxlength="150" value="<?= ucwords($page_data->add_asset); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Transfer</label>
                                        <input type="text" name="transfer" class="form-control" placeholder="Enter transfer" required maxlength="150" value="<?= ucwords($page_data->transfer); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Del</label>
                                        <input type="text" name="del" class="form-control" placeholder="Enter del" required maxlength="150" value="<?= ucwords($page_data->del); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>W Off</label>
                                        <input type="text" name="w_off" class="form-control" placeholder="Enter w_off" required maxlength="150" value="<?= ucwords($page_data->w_off); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Net Block</label>
                                        <input type="text" name="net_block" class="form-control" placeholder="Enter net_block" required maxlength="150" value="<?= ucwords($page_data->net_block); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Fy Start</label>
                                        <input type="text" name="dep_fy_start" class="form-control" placeholder="Enter dep_fy_start" required maxlength="150" value="<?= ucwords($page_data->dep_fy_start); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep For The Year</label>
                                        <input type="text" name="dep_for_the_year" class="form-control" placeholder="Enter dep_for_the_year" required maxlength="150" value="<?= ucwords($page_data->dep_for_the_year); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Transfer</label>
                                        <input type="text" name="dep_transfer" class="form-control" placeholder="Enter dep_transfer" required maxlength="150" value="<?= ucwords($page_data->dep_transfer); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep Retir</label>
                                        <input type="text" name="dep_retir" class="form-control" placeholder="Enter dep_retir" required maxlength="150" value="<?= ucwords($page_data->dep_retir); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Accumul Dep</label>
                                        <input type="text" name="accumul_dep" class="form-control" placeholder="Enter accumul_dep" required maxlength="150" value="<?= ucwords($page_data->accumul_dep); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Impairment</label>
                                        <input type="text" name="opening_impairment" class="form-control" placeholder="Enter opening_impairment" required maxlength="150" value="<?= ucwords($page_data->opening_impairment); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Transfer</label>
                                        <input type="text" name="impairment_transfer" class="form-control" placeholder="Enter impairment_transfer" required maxlength="150" value="<?= ucwords($page_data->impairment_transfer); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Charges</label>
                                        <input type="text" name="impairment_charges" class="form-control" placeholder="Enter impairment_charges" required maxlength="150" value="<?= ucwords($page_data->impairment_charges); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Impairment Charges2</label>
                                        <input type="text" name="impairment_charges2" class="form-control" placeholder="Enter impairment_charges2" required maxlength="150" value="<?= ucwords($page_data->impairment_charges2); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Imapirment Reversal</label>
                                        <input type="text" name="imapirment_reversal" class="form-control" placeholder="Enter imapirment_reversal" required maxlength="150" value="<?= ucwords($page_data->imapirment_reversal); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Accumul Impairment</label>
                                        <input type="text" name="accumul_impairment" class="form-control" placeholder="Enter accumul_impairment" required maxlength="150" value="<?= ucwords($page_data->accumul_impairment); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Curr Bk Val</label>
                                        <input type="text" name="Curr_bk_val" class="form-control" placeholder="Enter Curr_bk_val" required maxlength="150" value="<?= ucwords($page_data->Curr_bk_val); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Wdv After Impairment</label>
                                        <input type="text" name="wdv_after_impairment" class="form-control" placeholder="Enter wdv_after_impairment" required maxlength="150" value="<?= ucwords($page_data->wdv_after_impairment); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening wdv </label>
                                        <input type="text" name="opening_wdv" class="form-control" placeholder="Enter opening_wdv" required maxlength="150" value="<?= ucwords($page_data->opening_wdv); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Opening Wdv After Impairment</label>
                                        <input type="text" name="opening_wdv_after_impairment" class="form-control" placeholder="Enter opening_wdv_after_impairment" required maxlength="150" value="<?= ucwords($page_data->opening_wdv_after_impairment); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Quantity</label>
                                        <input type="text" name="quantity" class="form-control" placeholder="Enter quantity" required maxlength="150" value="<?= ucwords($page_data->quantity); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Deactivation On</label>
                                        <input type="text" name="deactivation_on" class="form-control" placeholder="Enter deactivation_on" required maxlength="150" value="<?= ucwords($page_data->deactivation_on); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Life Used</label>
                                        <input type="text" name="life_used" class="form-control" placeholder="Enter life_used" required maxlength="150" value="<?= ucwords($page_data->life_used); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Dep</label>
                                        <input type="text" name="dep" class="form-control" placeholder="Enter Dep" required maxlength="150" value="<?= ucwords($page_data->dep); ?>">
                                    </div>
                                    <div class="form-group col-sm-6 flex-column d-flex">
                                        <label>Historical Wdv</label>
                                        <input type="text" name="historical_wdv" class="form-control" placeholder="Enter historical_wdv" required maxlength="150" value="<?= ucwords($page_data->historical_wdv); ?>">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success" title="Update" name="update">Update</button>
                                <a href="<?= base_url('current_excel'); ?>" class="btn btn-info" title="Cancle">Cancle</a>
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