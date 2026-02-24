@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Laporan Sisa Cuti Karyawan</h5>
                    <p class="text-muted mb-0">Rekap sisa cuti karyawan dengan data SAP</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Filter Unit</label>
                            <select id="unitFilter" class="form-select form-select-sm">
                                <option value="">Semua Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->personnel_subarea }}">{{ $unit->desc_personnel_subarea }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Show</label>
                            <select id="perPageSelect" class="form-select form-select-sm">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Search</label>
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                        <div class="col-md-3">
                            <label>Export</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-sm btn-success" onclick="exportExcel()">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="exportPdf()">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover text-nowrap" id="tableData1">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Personnel Subarea</th>
                                    <th class="text-dark">Desc Personnel Subarea</th>
                                    <th class="text-dark">Org. Unit</th>
                                    <th class="text-dark">Desc Org Unit</th>
                                    <th class="text-dark">Employee Group</th>
                                    <th class="text-dark">Sisa Cuti Tahunan</th>
                                    <th class="text-dark">Sisa Cuti Panjang</th>
                                    <th class="text-dark">Tgl Jatuh Tempo Tahunan</th>
                                    <th class="text-dark">Tgl Jatuh Tempo Panjang</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="13" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="tableInfo"></div>
                        </div>
                        <div class="col-md-6">
                            <nav>
                                <ul class="pagination pagination-sm justify-content-end" id="pagination"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        let currentPage = 1;
        let perPage = 25;
        let searchQuery = '';
        let unitFilter = '';
        let searchTimeout;
        let totalPages = 1;

        function loadReportData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="13" class="text-center">Loading...</td></tr>';

            fetch(`{{ route('leave-balance-report.data') }}?page=${currentPage}&per_page=${perPage}&search=${searchQuery}&unit_filter=${unitFilter}`)
                .then(response => response.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                    renderInfo(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="13" class="text-center text-danger">Error loading data</td></tr>';
                });
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="13" class="text-center">No data found</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach((item, index) => {
                const no = ((data.current_page - 1) * data.per_page) + index + 1;
                html += `
                    <tr class="align-middle">
                        <td class="text-center">${no}</td>
                        <td class="text-center">${item.nik || '-'}</td>
                        <td>${item.nama || '-'}</td>
                        <td>${item.jabatan || '-'}</td>
                        <td class="text-center">${item.personnel_subarea || '-'}</td>
                        <td>${item.desc_personnel_subarea || '-'}</td>
                        <td class="text-center">${item.org_unit || '-'}</td>
                        <td>${item.desc_org_unit || '-'}</td>
                        <td>${item.employee_group || '-'}</td>
                        <td class="text-center">${item.sisa_cuti_tahunan || '0'}</td>
                        <td class="text-center">${item.sisa_cuti_panjang || '0'}</td>
                        <td class="text-center">${item.tgl_jatuh_tempo_tahunan || '-'}</td>
                        <td class="text-center">${item.tgl_jatuh_tempo_panjang || '-'}</td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;
        }

        function renderPagination(data) {
            totalPages = data.last_page;
            const pagination = document.getElementById('pagination');
            let html = '';
            const current = data.current_page;
            const last = data.last_page;

            if (current > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">First</a></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${current - 1}); return false;">Prev</a></li>`;
            }

            for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
                html += `<li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>`;
            }

            if (current < last) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${current + 1}); return false;">Next</a></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${last}); return false;">Last</a></li>`;
            }

            pagination.innerHTML = html;
        }

        function renderInfo(data) {
            const info = document.getElementById('tableInfo');
            const start = ((data.current_page - 1) * data.per_page) + 1;
            const end = Math.min(data.current_page * data.per_page, data.total);
            info.textContent = `Showing ${start} to ${end} of ${data.total} entries`;
        }

        function changePage(page) {
            currentPage = page;
            loadReportData();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        document.getElementById('perPageSelect').addEventListener('change', function() {
            perPage = parseInt(this.value);
            currentPage = 1;
            loadReportData();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadReportData();
            }, 500);
        });

        document.getElementById('unitFilter').addEventListener('change', function() {
            unitFilter = this.value;
            currentPage = 1;
            loadReportData();
        });

        function exportExcel() {
            window.location.href = `{{ route('leave-balance-report.export-excel') }}?unit_filter=${unitFilter}&search=${searchQuery}`;
        }

        function exportPdf() {
            window.location.href = `{{ route('leave-balance-report.export-pdf') }}?unit_filter=${unitFilter}&search=${searchQuery}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadReportData();
        });
    </script>
@endsection
