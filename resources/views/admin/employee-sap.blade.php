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
                    <h5>Data Employee SAP</h5>
                    <p class="text-muted mb-0">Data karyawan yang di-sync dari sistem SAP</p>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Show
                                <select id="perPageSelect" class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select> entries
                            </label>
                        </div>
                        <div class="col-md-4 text-center">
                            <label class="d-inline-block">Go to page:
                                <input type="number" id="gotoPageInput" class="form-control form-control-sm d-inline-block"
                                    style="width: 80px;" min="1" placeholder="Page">
                                <button class="btn btn-sm btn-primary" onclick="gotoPage()">Go</button>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control form-control-sm"
                                placeholder="Search...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="tableData1">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">SAP</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Unit Organisasi</th>
                                    <th class="text-dark">Employee Group</th>
                                    <th class="text-dark">Level</th>
                                    <th class="text-dark">Region</th>
                                    <th class="text-dark">Gender</th>
                                    <th class="text-dark">Email</th>
                                    <th class="text-dark">Phone</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="11" class="text-center">Loading...</td>
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
        let searchTimeout;
        let totalPages = 1;

        function loadEmployeeData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="11" class="text-center">Loading...</td></tr>';

            fetch(`{{ route('admin.employee-sap.data') }}?page=${currentPage}&per_page=${perPage}&search=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                    renderInfo(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML =
                        '<tr><td colspan="11" class="text-center text-danger">Error loading data</td></tr>';
                });
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="11" class="text-center">No data found</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach((item, index) => {
                const no = ((data.current_page - 1) * data.per_page) + index + 1;
                html += `
                    <tr class="text-center align-middle">
                        <th>${no}</th>
                        <td>${item.sap || '-'}</td>
                        <td>${item.name || '-'}</td>
                        <td>${item.desc_position || '-'}</td>
                        <td>${item.desc_org_unit || '-'}</td>
                        <td>${item.desc_employee_group || '-'}</td>
                        <td>${item.level || '-'}</td>
                        <td>${item.region || '-'}</td>
                        <td>${item.gender || '-'}</td>
                        <td>${item.email || '-'}</td>
                        <td>${item.phone || '-'}</td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;
        }

        function renderPagination(data) {
            totalPages = data.last_page;
            const pagination = document.getElementById('pagination');
            let html = '';
            const maxPages = 10;
            const current = data.current_page;
            const last = data.last_page;

            if (current > 1) {
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(1); return false;">First</a>
                </li>`;
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${current - 1}); return false;">Prev</a>
                </li>`;
            }

            if (last <= maxPages) {
                for (let i = 1; i <= last; i++) {
                    html += `<li class="page-item ${i === current ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
                }
            } else {
                let startPage, endPage;

                if (current <= 5) {
                    startPage = 1;
                    endPage = maxPages;
                } else if (current >= last - 4) {
                    startPage = last - maxPages + 1;
                    endPage = last;
                } else {
                    startPage = current - 4;
                    endPage = current + 5;
                }

                if (startPage > 1) {
                    html += `<li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(1); return false;">1</a>
                    </li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `<li class="page-item ${i === current ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
                }

                if (endPage < last) {
                    if (endPage < last - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html += `<li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(${last}); return false;">${last}</a>
                    </li>`;
                }
            }

            if (current < last) {
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${current + 1}); return false;">Next</a>
                </li>`;
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${last}); return false;">Last</a>
                </li>`;
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
            loadEmployeeData();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function gotoPage() {
            const input = document.getElementById('gotoPageInput');
            const page = parseInt(input.value);

            if (page && page >= 1 && page <= totalPages) {
                changePage(page);
                input.value = '';
            } else {
                alert(`Please enter a valid page number between 1 and ${totalPages}`);
            }
        }

        document.getElementById('gotoPageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                gotoPage();
            }
        });

        document.getElementById('perPageSelect').addEventListener('change', function() {
            perPage = parseInt(this.value);
            currentPage = 1;
            loadEmployeeData();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadEmployeeData();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadEmployeeData();
        });
    </script>
@endsection
