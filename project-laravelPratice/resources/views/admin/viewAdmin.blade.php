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
            @php $Sno = ($data->currentPage() - 1) * $data->perPage() + 1; @endphp
            @forelse ($data as $user)
                <tr>
                    <td>{{ $Sno }}</td>
                    <td>{{ $user->f_name }} {{ $user->l_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>
                        <a href="{{ route('view.user', $user->uid) }}" class='btn btn-info'>View</a>
                        <button type='button' class='btn btn-danger'data-id='{{ $user->uid }}'>Delete</button>
                    </td>
                </tr>
                @php $Sno++ @endphp
            @empty
                <tr>
                    <td colspan="6">
                        <h2>No Records Found</h2>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-5">{{ $data->links() }}</div>
    {{-- </div>Total User:{{ $data->total() }}</div> --}}
</div>
