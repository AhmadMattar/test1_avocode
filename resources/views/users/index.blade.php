@extends('layouts.master')
@section('content')
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped mb-4" id="dataTable">
        <thead>
            <tr>
                <th>ID</th>
            </tr>
        </thead>
    </table>
</div>
@stop
@section('script')
<script>
    $(function() {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax:
            {
                url: '{{route('users.data')}}',
            },
            columns:
            [
                {
                    data: 'id',
                    name: 'id',
                },

            ]
        });
    });
</script>
@stop
