@extends('layouts.admin.app')

@section('content')
    <style>
        /* Kostumisasi agar senada dengan SprintZone */
        .btn-sprint {
            background-color: #FF4500;
            color: white;
            border: none;
            font-weight: bold;
        }

        .btn-sprint:hover {
            background-color: #e63e00;
            color: white;
        }

        .card-sprint {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            border-top: none;
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .badge-admin {
            background-color: #212529;
            color: white;
        }

        .badge-user {
            background-color: #ffc107;
            color: #212529;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-1xl md:text-2xl font-black italic uppercase tracking-tighter">USER <span
                    class="text-orange-500">MANAGEMENT</span></h2>
            <button class="btn btn-sprint px-4 shadow-sm" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus-circle mr-2"></i> NEW USER
            </button>
        </div>

        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4">Action</th>
                                <th class="pl-4">Full Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <button class="btn btn-outline-dark btn-sm rounded-pill px-3" data-toggle="modal"
                                            data-target="#modalEdit{{ $user->id }}">
                                            <i class="fas fa-pen mr-1"></i> Edit
                                        </button>
                                    </td>
                                    <td class="pl-4 align-middle font-weight-bold">{{ $user->name }}</td>
                                    <td class="align-middle text-muted">{{ $user->email }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light border px-2 py-1">
                                            {{ $user->company->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if ($user->role == 'admin')
                                            <span class="badge badge-admin px-3 py-2">ADMINISTRATOR</span>
                                        @else
                                            <span class="badge badge-user px-3 py-2">USER</span>
                                        @endif
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg">
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title font-weight-bold">Edit : {{ $user->name }}</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body px-4">
                                                    <div class="form-group mb-3">
                                                        <label class="small font-weight-bold">FULL NAME</label>
                                                        <input type="text" name="name"
                                                            class="form-control form-control-lg bg-light border-0"
                                                            value="{{ $user->name }}" required style="font-size: 15px;">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="small font-weight-bold">COMPANY</label>
                                                        <select name="company_id"
                                                            class="form-control form-control-lg bg-light border-0"
                                                            style="font-size: 15px;">
                                                            <option value=""
                                                                {{ is_null($user->company_id) ? 'selected' : '' }}>-- Select
                                                                Company --</option>
                                                            @foreach (\App\Models\Company::all() as $company)
                                                                <option value="{{ $company->id }}"
                                                                    {{ $user->company_id == $company->id ? 'selected' : '' }}>
                                                                    {{ $company->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="small font-weight-bold">ROLE</label>
                                                        <select name="role"
                                                            class="form-control form-control-lg bg-light border-0"
                                                            style="font-size: 15px;">
                                                            <option value="user"
                                                                {{ $user->role == 'user' ? 'selected' : '' }}>USER</option>
                                                            <option value="admin"
                                                                {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                                ADMINISTRATOR</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="small font-weight-bold">NEW PASSWORD <span
                                                                class="text-muted font-weight-normal">(Leave blank if not
                                                                replaced)</span></label>
                                                        <input type="password" name="password"
                                                            class="form-control form-control-lg bg-light border-0"
                                                            style="font-size: 15px;">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 px-4 pb-4">
                                                    <button type="button" class="btn btn-light rounded-pill px-4"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-sprint rounded-pill px-4">SAVE</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title font-weight-bold">NEW USER</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">FULL NAME</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0"
                                placeholder="Enter full name" required style="font-size: 15px;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">EMAIL</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0"
                                placeholder="email@example.com" required style="font-size: 15px;">
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">COMPANY</label>
                            <select name="company_id" class="form-control form-control-lg bg-light border-0"
                                style="font-size: 15px;">
                                <option value="" disabled selected>Select Company</option>
                                @foreach (\App\Models\Company::all() as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">ROLE</label>
                            <select name="role" class="form-control form-control-lg bg-light border-0"
                                style="font-size: 15px;">
                                <option value="user">USER</option>
                                <option value="admin">ADMINISTRATOR</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">PASSWORD</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0"
                                placeholder="Minimum 8 Characters" required style="font-size: 15px;">
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sprint rounded-pill px-4">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
