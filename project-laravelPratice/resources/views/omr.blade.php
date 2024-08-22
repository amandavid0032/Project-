@extends('layout')
@section('content')       
<tbody>
            {{-- @php $Sno = ($user->currentPage() - 1) * $data->perPage() + 1; @endphp --}}
            @forelse ($user as $user)
                <tr>
                    <td>{{ $Sno=1; }}</td>
                    <td>{{ $user->f_name }} {{ $user->l_name }}</td>
                    <td>{{ $user->emailId }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>
                        <a href="{{ route('view.user', $user->id) }}" class='btn btn-info'>View</a>
                        {{-- <button type='button' class='btn btn-danger'data-id='{{ $user->uid }}'>Delete</button> --}}
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
@endsection