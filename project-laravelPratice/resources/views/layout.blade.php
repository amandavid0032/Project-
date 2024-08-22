@include('admin.header')

<div class="modal" id="userData" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>
<div class="toast" role="alert" style="position: absolute; right: 80px; top: 30px; margin-right: 10px;"
    aria-live="assertive" aria-atomic="true">
    <div class="toast-header"></div>
    <div class="toast-body"></div>
</div>

<div id="dataContainer">
    <table class="table table-striped sortable">
        <thead>
            <tr>
                <th scope="col">Sno</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone No</th>
                <th scope="col">Gender</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
@yield('content')
        </tbody>
    </table>
</div>
