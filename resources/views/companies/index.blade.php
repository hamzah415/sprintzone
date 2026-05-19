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
            <h2 class="text-1xl md:text-2xl font-black italic uppercase tracking-tighter">COMPANY <span
                    class="text-orange-500">MANAGEMENT</span></h2>

            <button class="btn btn-sprint px-4 shadow-sm" data-toggle="modal" data-target="#modalCreateCompany">
                <i class="fas fa-plus-circle me-2"></i> NEW COMPANY
            </button>
        </div>

        <div class="card card-sprint">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="pl-4">Action</th>
                                <th class="ps-4">ID</th>
                                <th>Company Name</th>
                                <th>Email Address</th>
                                <th>Industry</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $company)
                                <tr>
                                    <td class="pe-4">
                                        <a href="#" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold"
                                            style="font-size: 12px;">
                                            <i class="fas fa-pencil-alt me-1"></i> Edit
                                        </a>
                                    </td>
                                    <td class="ps-4 fw-bold text-dark">{{ $company->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark text-uppercase" style="font-size: 15px;">
                                            {{ $company->name }}</div>
                                        <small class="text-muted"><i
                                                class="fas fa-link me-1"></i>{{ $company->website ?? 'No Website' }}</small>
                                    </td>
                                    <td class="text-muted">{{ $company->email }}</td>
                                    <td>
                                        <span class="badge bg-dark text-white px-3 py-2 text-uppercase"
                                            style="font-size: 10px; border-radius: 4px;">

                                            {{ $company->industry ?? 'General' }}

                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted opacity-50">
                                            <i class="fas fa-building fa-3x mb-3"></i>
                                            <p class="mb-0 fw-bold">There is no company data yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreateCompany" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                <div class="modal-header modal-header-sprint py-3 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-square me-2"></i> CREATE NEW COMPANY
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form action="{{ route('companies.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label form-label-custom">Company Name</label>
                                <input type="text" name="name" class="form-control form-control-sprint"
                                    placeholder="Enter company name" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label form-label-custom">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-sprint"
                                    placeholder="company@mail.com" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label form-label-custom">Industry</label>
                                <select name="industry" class="form-select form-control-sprint">
                                    <option value="">Select Industry</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Education">Education</option>
                                    <option value="Healthcare">Healthcare</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-custom">Website</label>
                                <input type="url" name="website" class="form-control form-control-sprint"
                                    placeholder="https://example.com">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label form-label-custom">Address</label>
                                <textarea name="address" class="form-control form-control-sprint" rows="3" placeholder="Jl. Raya Sprint No. 1..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-3 px-4">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal"
                            style="border-radius: 8px;">CLOSE</button>
                        <button type="submit" class="btn btn-orange px-4 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-save me-1"></i> SAVE COMPANY
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
