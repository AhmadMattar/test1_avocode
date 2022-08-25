<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <!-- Vertically centered scrollable modal -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('general.order_details')}}</h5>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body">
                <div class="card shadow mb-4">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>{{__('general.name')}}</th>
                                    <td id="customer_name"></td>
                                </tr>
                                <tr>
                                    <th>{{__('general.coupoun')}}</th>
                                    <td id="coupoun_code"></td>
                                </tr>
                                <tr>
                                    <th>{{__('general.coupoun_type')}}</th>
                                    <td id="coupoun_type"></td>
                                </tr>

                                <tr>
                                    <th>{{__('general.coupoun_value')}}</th>
                                    <td id="coupoun_value"></td>
                                </tr>
                                <tr>
                                    <th>{{__('general.payment_status')}}</th>
                                    <td id="payment_status"></td>
                                </tr>
                                <tr>
                                    <th>{{__('general.created_date')}}</th>
                                    <td colspan="3" id="created_at"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody id="products_details">
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>{{__('general.total')}}</th>
                                    <td colspan="3" id="total"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


