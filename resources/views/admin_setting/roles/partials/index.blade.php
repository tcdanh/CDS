<h4>Danh sách users</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-hover">
    <thead class="thead-dark">
    
        <tr>
            <th>Tên (quyền)</th>
            <th>Thay quyền</th>
            <th>Được phép đăng nhập?</th>
            @if(in_array(Auth::user()->role_id, [1, 2]))
                <th>Hành động</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <form method="POST" action="{{ route('admin_setting.update_user', $user->id) }}">
                @csrf
                @method('PUT')
                <td>{{ $user->name }} ({{$user->role->name }})</td>
                
                <td>
                    <select name="id_role" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->id_role == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="invisible" class="form-control">
                        <option value="1" {{ $user->invisible ? 'selected' : '' }}>✔ Có</option>
                        <option value="0" {{ !$user->invisible ? 'selected' : '' }}>✖ Không</option>
                    </select>
                </td>
                <td>
                    <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                </td>
            </form>
        </tr>
        @endforeach
    </tbody>
</table>
