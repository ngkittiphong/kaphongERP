<div class="tab-pane" id="tab-stock-card">
    {{-- Stoccard --}}
    stock card detail statement

    @push('scripts')
        <script src="{{ asset('js/forms/picker.js') }}"></script>
        <script src="{{ asset('js/forms/picker.date.js') }}"></script>
        <script src="{{ asset('js/pages/pickers.js') }}"></script>
        <script src="{{ asset('js/tables/datatables/extensions/buttons.min.js') }}"></script>
    @endpush

    <div class="panel panel-flat">
        <div class="panel-heading">
            <h4 class="panel-title">
                Condition
            </h4>
        </div>
        <div class="panel-body">
            <div class="col-md-4 col-xs-4">
                <label>date start</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                    <input type="text" class="form-control pickadate" placeholder="Select">
                </div>

            </div>

            <div class="col-md-4 col-xs-4">
                <label>date end</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                    <input type="text" class="form-control pickadate" placeholder="Select">
                </div>

            </div>

            <div class="col-md-4 col-xs-4">

                <div class="form-group">
                    <label class="display-block">เลือกคลัง</label>
                    <select class="form-control">
                        <optgroup label="Mountain Time Zone">
                            <option value="AZ">Arizona</option>
                            <option value="CO">Colorado</option>
                            <option value="ID">Idaho</option>
                            <option value="WY">Wyoming</option>
                        </optgroup>
                        <optgroup label="Central Time Zone">
                            <option value="AL">Alabama</option>
                            <option value="AR">Arkansas</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                        </optgroup>
                        <optgroup label="Eastern Time Zone">
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="FL">Florida</option>
                            <option value="WV">West Virginia</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="col-md-12 col-xs-12 text-right">
                <!--<div class="elements">-->
                <!--<button type="button" class="btn bg-amber btn-sm">Button</button>-->
                <button class="btn bg-info">view stockcard</button>
                <!--</div>-->

            </div>
        </div>

        <div class="panel panel-flat">
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="panel panel-flat">
                            <div class="panel-body p-b-10">
                                <div class="row">
                                    <div class="col-md-8 col-xs-8">
                                        <h1
                                            class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                            200 ชิ้น</h2>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <i class="icon-cube2 icon-4x text-blue-lighter"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer bg-blue-lighter">
                                <div class="elements">
                                    <span class="text-size-extralarge">สินค้าคงเหลือ</span>
                                    <!--<a href="#" class="pull-right no-padding-right text-white">View all <i class="icon-arrow-right6 position-right"></i></a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-flat">
                            <div class="panel-body p-b-10">
                                <div class="row">
                                    <div class="col-md-8 col-xs-8">
                                        <h1
                                            class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                            540 ชิ้น</h2>
                                            <!--<span class="">ชิ้น</span>-->
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <i class="icon-download4 icon-4x" style="color:#D0F1CF"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="background-color:#D0F1CF">
                                <div class="elements">
                                    <span class="text-size-extralarge">จำนวนสินค้าเข้า</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-flat">
                            <div class="panel-body p-b-10">
                                <div class="row">
                                    <div class="col-md-8 col-xs-8">
                                        <h1
                                            class="text-size-huge text-regular text-semibold no-padding no-margin m-t-5 m-b-10">
                                            340 ชิ้น</h2>
                                    </div>
                                    <div class="col-md-4 col-xs-4">
                                        <i class="icon-upload4 icon-4x icon-normal"
                                            style="color:#F1CFCF"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer" style="background-color:#F1CFCF">
                                <div class="elements">
                                    <span class="text-size-extralarge">จำนวนสินค้าออก</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="table-responsive">
                <table class="table datatable-stock-card">
                    <thead>
                        <tr>
                            <th>Move</th>
                            <th>วันที่</th>
                            <th>Document No.</th>
                            <th>Detail</th>
                            <th>Warehouse</th>
                            <th>จำนวนเข้า</th>
                            <th>จำนวนออก</th>
                            <th>หน่วย</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="" style="background-color:#D0F1CF">
                            <td>เข้า</td>
                            <td>3/12/2024</td>
                            <td><a href="#">TF092904022</a></td>
                            <td>ซื้อสินค้าเข้า</td>
                            <td>คลังหลัก</td>
                            <td>120</td>
                            <td> - </td>
                            <td>แพค</td>

                        </tr>
                        <tr style="background-color:#F1CFCF">
                            <td>ออก</td>
                            <td>5/12/2024</td>
                            <td><a href="#">TF092904023</a></td>
                            <td>ซื้อสินค้าออก</td>
                            <td>คลังภูเก็ต</td>
                            <td> - </td>
                            <td> 12 </td>
                            <td>กล่อง</td>

                        </tr>
                        <tr style="background-color:#D0F1CF">
                            <td>เข้า</td>
                            <td>20/12/2024</td>
                            <td><a href="#">TF092904032</a></td>
                            <td>ซื้อสินค้าเข้า</td>
                            <td>คลังหลัก</td>
                            <td>500</td>
                            <td> - </td>
                            <td> ชิ้น </td>

                        </tr>
                        <tr style="background-color:#D0F1CF">
                            <td>เข้า</td>
                            <td>3/12/2024</td>
                            <td><a href="#">TF092904122</a></td>
                            <td>ซื้อสินค้าเข้า</td>
                            <td>คลังหลัก</td>
                            <td>120</td>
                            <td> - </td>
                            <td>แพค</td>

                        </tr>
                        <tr style="background-color:#F1CFCF">
                            <td>ออก</td>
                            <td>3/12/2024</td>
                            <td><a href="#">TF092904222</a></td>
                            <td>เบิกขาย</td>
                            <td>คลังหลัก</td>
                            <td> - </td>
                            <td> 35 </td>
                            <td>แพค</td>

                        </tr>
                        <tr style="background-color:#D0F1CF">
                            <td>เข้า</td>
                            <td>3/12/2024</td>
                            <td><a href="#">TF092904022</a></td>
                            <td>ซื้อสินค้าเข้า</td>
                            <td>คลังหลัก</td>
                            <td>12</td>
                            <td> - </td>
                            <td>แพค</td>

                        </tr>

                        <tr>
                            <td></td>
                            <td>จำนวนรวม</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>540</td>
                            <td>340</td>
                            <td>ชิ้น</td>

                        </tr>
                        <tr>
                            <td></td>

                            <td>คงเหลือ</td>
                            <td>200</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>ชิ้น</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>