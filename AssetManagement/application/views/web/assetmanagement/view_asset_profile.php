<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('view_audit') ?>">View Asset</a></li>
                <li>View Asset</li>
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
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive" style="overflow:auto; height:500px;">
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <td>Qr and Bar Code Number</td>
                                <td><b><?= ucfirst($page_data->qr_and_bar_code_number); ?></b></td>
                            </tr>
                            <tr>
                                <td>Rfid or id</td>
                                <td><b><?= ucfirst($page_data->rfid_or_id); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset</td>
                                <td><b><?= ucfirst($page_data->asset); ?></b></td>
                            </tr>
                            <tr>
                                <td>Subnumber</td>
                                <td><b><?= ucfirst($page_data->subnumber); ?></b></td>
                            </tr>
                            <tr>
                                <td>Fa</td>
                                <td><b><?= ucfirst($page_data->fa); ?></b></td>
                            </tr>
                            <tr>
                                <td>Class</td>
                                <td><b><?= ucfirst($page_data->class); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Class</td>
                                <td><b><?= ucfirst($page_data->asset_class); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Status 1</td>
                                <td><b><?= ucfirst($page_data->asset_status_1); ?></b></td>
                            </tr>
                            <tr>
                                <td>Profit Center</td>
                                <td><b><?= ucfirst($page_data->profit_center); ?></b></td>
                            </tr>
                            <tr>
                                <td>As Per Sap</td>
                                <td><b><?= ucfirst($page_data->as_per_sap); ?></b></td>
                            </tr>
                            <tr>
                                <td>Store Name</td>
                                <td><b><?= ucfirst($page_data->store_name); ?></b></td>
                            </tr>
                            <tr>
                                <td>Opening Date</td>
                                <td><b><?= ucfirst($page_data->opening_date); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Status 2</td>
                                <td><b><?= ucfirst($page_data->asset_status_2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Block</td>
                                <td><b><?= ucfirst($page_data->asset_block); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Brand</td>
                                <td><b><?= ucfirst($page_data->asset_brand); ?></b></td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td><b><?= ucfirst($page_data->location_name); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Description</td>
                                <td><b><?= ucfirst($page_data->asset_description); ?></b></td>
                            </tr>
                            <tr>
                                <td>Asset Name</td>
                                <td><b><?= ucfirst($page_data->asset_name); ?></b></td>
                            </tr>
                            <tr>
                                <td>Type of Assets</td>
                                <td><b><?= ucfirst($page_data->type_of_Assets); ?></b></td>
                            </tr>
                            <tr>
                                <td>Capitalized On</td>
                                <td><b><?= ucfirst($page_data->capitalized_on); ?></b></td>
                            </tr>
                            <tr>
                                <td>Life</td>
                                <td><b><?= ucfirst($page_data->life); ?></b></td>
                            </tr>
                            <tr>
                                <td>Balance Life</td>
                                <td><b><?= ucfirst($page_data->balance_life); ?></b></td>
                            </tr>
                            <tr>
                                <td>Days</td>
                                <td><b><?= ucfirst($page_data->days); ?></b></td>
                            </tr>
                            <tr>
                                <td>Opening</td>
                                <td><b><?= ucfirst($page_data->opening); ?></b></td>
                            </tr>
                            <tr>
                                <td>Cwip Capitalized</td>
                                <td><b><?= ucfirst($page_data->cwip_capitalized); ?></b></td>
                            </tr>
                            <tr>
                                <td>Add Asset</td>
                                <td><b><?= ucfirst($page_data->add_asset); ?></b></td>
                            </tr>
                            <tr>
                                <td>Transfer</td>
                                <td><b><?= ucfirst($page_data->transfer); ?></b></td>
                            </tr>
                            <tr>
                                <td>Del</td>
                                <td><b><?= ucfirst($page_data->del); ?></b></td>
                            </tr>
                            <tr>
                                <td>W Off</td>
                                <td><b><?= ucfirst($page_data->w_off); ?></b></td>
                            </tr>
                            <tr>
                                <td>Net Block</td>
                                <td><b><?= ucfirst($page_data->net_block); ?></b></td>
                            </tr>
                            <tr>
                                <td>Dep Fy Start</td>
                                <td><b><?= ucfirst($page_data->dep_fy_start); ?></b></td>
                            </tr>
                            <tr>
                                <td>Dep Transfer</td>
                                <td><b><?= ucfirst($page_data->dep_transfer); ?></b></td>
                            </tr>
                            <tr>
                                <td>Dep For The Year</td>
                                <td><b><?= ucfirst($page_data->dep_for_the_year); ?></b></td>
                            </tr>
                            <tr>
                                <td>Dep Retir</td>
                                <td><b><?= ucfirst($page_data->dep_retir); ?></b></td>
                            </tr>
                            <tr>
                                <td>Accumul Dep</td>
                                <td><b><?= ucfirst($page_data->accumul_dep); ?></b></td>
                            </tr>
                            <tr>
                                <td>Opening Impairment</td>
                                <td><b><?= ucfirst($page_data->opening_impairment); ?></b></td>
                            </tr>
                            <tr>
                                <td>Impairment Transfer</td>
                                <td><b><?= ucfirst($page_data->impairment_transfer); ?></b></td>
                            </tr>
                            <tr>
                                <td>Impairment Charges</td>
                                <td><b><?= ucfirst($page_data->impairment_charges); ?></b></td>
                            </tr>
                            <tr>
                                <td>Impairment charges2</td>
                                <td><b><?= ucfirst($page_data->impairment_charges2); ?></b></td>
                            </tr>
                            <tr>
                                <td>Imapirment Reversal</td>
                                <td><b><?= ucfirst($page_data->imapirment_reversal); ?></b></td>
                            </tr>
                            <tr>
                                <td>Accumul Impairment</td>
                                <td><b><?= ucfirst($page_data->accumul_impairment); ?></b></td>
                            </tr>
                            <tr>
                                <td>Curr Bk Val</td>
                                <td><b><?= ucfirst($page_data->Curr_bk_val); ?></b></td>
                            </tr>
                            <tr>
                                <td>Wdv After Impairment</td>
                                <td><b><?= ucfirst($page_data->wdv_after_impairment); ?></b></td>
                            </tr>
                            <tr>
                                <td>Opening Wdv</td>
                                <td><b><?= ucfirst($page_data->opening_wdv); ?></b></td>
                            </tr>
                            <tr>
                                <td>Quantity</td>
                                <td><b><?= ucfirst($page_data->quantity); ?></b></td>
                            </tr>
                            <tr>
                                <td>Deactivation On</td>
                                <td><b><?= ucfirst($page_data->deactivation_on); ?></b></td>
                            </tr>
                            <tr>
                                <td>Life used</td>
                                <td><b><?= ucfirst($page_data->life_used); ?></b></td>
                            </tr>
                            <tr>
                                <td>Dep</td>
                                <td><b><?= ucfirst($page_data->dep); ?></b></td>
                            </tr>
                            <tr>
                                <td>Historical Wdv</td>
                                <td><b><?= ucfirst($page_data->historical_wdv); ?></b></td>
                            </tr>
                            <tr>
                                <td>Created By</td>
                                <td><b><?= $page_data->created_by; ?></b></td>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <td><b><?= $page_data->created_dt; ?></b></td>
                            </tr>
                        </table>
                    </div>
                    <tr>
                        <td colspan="2"><button onclick="goBack()" class="btn btn-success btn-md" title="Go Back to Previous Page">Go Back</button></td>
                    </tr>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->