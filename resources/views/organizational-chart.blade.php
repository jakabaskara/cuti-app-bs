@extends('admin.layout.main')

@section('css')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<!-- Custom org chart styles -->
<style>
    .orgchart-container {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
    }

    #chartContainer {
        width: 100%;
        overflow-x: auto;
        padding: 20px;
        background: #f8f9fa;
    }

    .org-chart {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
    }

    .org-level {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .org-node {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        min-width: 200px;
        max-width: 250px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .org-node:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .org-node.brm-1 {
        border-color: #dc3545;
        background: linear-gradient(135deg, #fff 0%, #ffe5e8 100%);
    }

    .org-node.brm-2 {
        border-color: #ffc107;
        background: linear-gradient(135deg, #fff 0%, #fff8e1 100%);
    }

    .org-node.brm-3 {
        border-color: #28a745;
        background: linear-gradient(135deg, #fff 0%, #e8f5e9 100%);
    }

    .org-node.empty-position {
        border-style: dashed;
        border-color: #6c757d;
        background: #f8f9fa;
        cursor: pointer;
        opacity: 0.7;
    }

    .org-node.empty-position:hover {
        opacity: 1;
    }

    .node-title {
        font-size: 14px;
        font-weight: bold;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .node-name {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .node-level-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
        margin-top: 5px;
    }

    .org-node.brm-1 .node-level-badge {
        background-color: #dc3545;
        color: white;
    }

    .org-node.brm-2 .node-level-badge {
        background-color: #ffc107;
        color: #333;
    }

    .org-node.brm-3 .node-level-badge {
        background-color: #28a745;
        color: white;
    }

    .org-node.empty-position .node-level-badge {
        background-color: #6c757d;
        color: white;
    }

    .node-actions {
        margin-top: 10px;
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .node-actions button {
        padding: 3px 8px;
        font-size: 11px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>Struktur Organisasi</h5>
                <p class="text-muted mb-0">Visualisasi struktur organisasi berdasarkan unit kerja</p>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Filter Unit Kerja</label>
                        <select id="unitFilter" class="form-select">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($units as $unit)
                            <option value="{{ $unit->kode_unit_kerja }}">
                                {{ $unit->kode_unit_kerja }} - {{ $unit->display_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" onclick="loadChartData()">
                            <i class="material-icons-two-tone">refresh</i> Refresh
                        </button>
                    </div>
                </div>

                <div class="orgchart-container">
                    <div id="chartContainer">
                        <div class="alert alert-info">
                            Silakan pilih unit kerja untuk menampilkan struktur organisasi
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="alert alert-info">
                        <strong>Informasi:</strong>
                        <ul class="mb-0">
                            <li>Klik pada posisi kosong (garis putus-putus) untuk mengisi dengan karyawan</li>
                            <li>BRM-1: GM, Manajer, Kabag</li>
                            <li>BRM-2: Level BRM-2 dari data SAP</li>
                            <li>BRM-3: Asisten</li>
                            <li>Satu karyawan dapat memegang beberapa posisi (rangkap jabatan)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for assigning/editing employee to position -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalTitle">Assign Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="employeeForm">
                <div class="modal-body">
                    <input type="hidden" id="employeePositionId" name="employee_position_id">
                    <input type="hidden" id="positionId" name="position_id">

                    <div class="mb-3">
                        <label>Posisi</label>
                        <input type="text" id="positionName" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Level</label>
                        <input type="text" id="positionLevel" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Pilih Karyawan <span class="text-danger">*</span></label>
                        <select id="employeeSelect" name="nik" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let currentUnit = '';
    let chartData = [];
    let employeeModal = null;
    let isEditMode = false;

    $(document).ready(function () {
        // Initialize Select2
        $('#unitFilter').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Unit Kerja',
            allowClear: true,
            width: '100%'
        });

        $('#employeeSelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#employeeModal')
        });

        // Initialize modal
        employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));

        // Auto-load if only one unit (non-admin)
        @if (!$isAdmin && count($units) === 1)
            $('#unitFilter').val('{{ $units[0]->kode_unit_kerja }}').trigger('change');
        loadChartData();
        @endif
    });

    // Unit filter change event
    $('#unitFilter').on('change', function () {
        currentUnit = $(this).val();
        if (currentUnit) {
            loadChartData();
        }
    });

    // Load organizational chart data
    function loadChartData() {
        if (!currentUnit) {
            alert('Silakan pilih unit kerja terlebih dahulu');
            return;
        }

        $.ajax({
            url: '{{ route("organizational-chart.data") }}',
            method: 'GET',
            data: { kode_unit: currentUnit },
            success: function (response) {
                if (response.success) {
                    chartData = response.data;
                    renderChart(chartData);
                }
            },
            error: function (xhr) {
                console.error('Error loading chart data:', xhr);
                alert('Gagal memuat data struktur organisasi');
            }
        });
    }

    // Render organizational chart
    function renderChart(data) {
        $('#chartContainer').empty();

        if (!data || (!data.brm1 && !data.brm2 && !data.brm3)) {
            $('#chartContainer').html('<div class="alert alert-info">Tidak ada data untuk ditampilkan</div>');
            return;
        }

        const $chart = $('<div>').addClass('org-chart');

        // Render BRM-1
        if (data.brm1 && data.brm1.length > 0) {
            const $level1 = $('<div>').addClass('org-level');
            data.brm1.forEach(node => {
                $level1.append(createNodeElement(node));
            });
            $chart.append($level1);
        }

        // Render BRM-2
        if (data.brm2 && data.brm2.length > 0) {
            const $level2 = $('<div>').addClass('org-level');
            data.brm2.forEach(node => {
                $level2.append(createNodeElement(node));
            });
            $chart.append($level2);
        }

        // Render BRM-3
        if (data.brm3 && data.brm3.length > 0) {
            const $level3 = $('<div>').addClass('org-level');
            data.brm3.forEach(node => {
                $level3.append(createNodeElement(node));
            });
            $chart.append($level3);
        }

        $('#chartContainer').append($chart);
    }

    // Create node element
    function createNodeElement(node) {
        const $node = $('<div>').addClass('org-node');

        if (node.level) {
            $node.addClass(node.level.toLowerCase());
        }

        if (node.isEmpty) {
            $node.addClass('empty-position');
        }

        const $title = $('<div>').addClass('node-title').text(node.title || 'Posisi Kosong');
        $node.append($title);

        if (node.name && !node.isEmpty) {
            const $name = $('<div>').addClass('node-name').text(node.name);
            $node.append($name);
        }

        const $badge = $('<div>').addClass('node-level-badge').text(node.level || '');
        $node.append($badge);

        // Add action buttons
        const $actions = $('<div>').addClass('node-actions');

        if (node.isEmpty && node.position_id && node.canAssign !== false) {
            // Empty position that can be assigned - show assign button
            const $assignBtn = $('<button>')
                .addClass('btn btn-sm btn-primary')
                .html('<i class="material-icons-two-tone" style="font-size: 14px;">add</i> Assign')
                .on('click', function () {
                    openAssignModal(node);
                });
            $actions.append($assignBtn);
        } else if (!node.isEmpty && node.employee_position_id) {
            // Filled position - show edit and delete buttons
            const $editBtn = $('<button>')
                .addClass('btn btn-sm btn-warning')
                .html('<i class="material-icons-two-tone" style="font-size: 14px;">edit</i>')
                .on('click', function () {
                    openEditModal(node);
                });

            const $deleteBtn = $('<button>')
                .addClass('btn btn-sm btn-danger')
                .html('<i class="material-icons-two-tone" style="font-size: 14px;">delete</i>')
                .on('click', function () {
                    deleteEmployeePosition(node);
                });

            $actions.append($editBtn, $deleteBtn);
        }

        $node.append($actions);

        return $node;
    }

    // Open assign modal
    function openAssignModal(node) {
        isEditMode = false;
        $('#employeeModalTitle').text('Assign Employee ke Posisi');
        $('#employeePositionId').val('');
        $('#positionId').val(node.position_id);
        $('#positionName').val(node.title);
        $('#positionLevel').val(node.level);
        $('#employeeSelect').val('').trigger('change');

        loadAvailableEmployees(node.level);
        employeeModal.show();
    }

    // Open edit modal
    function openEditModal(node) {
        isEditMode = true;
        $('#employeeModalTitle').text('Edit Employee Position');
        $('#employeePositionId').val(node.employee_position_id);
        $('#positionId').val(node.position_id);
        $('#positionName').val(node.title);
        $('#positionLevel').val(node.level);

        loadAvailableEmployees(node.level, node.sap);
        employeeModal.show();
    }

    // Load available employees
    function loadAvailableEmployees(level, selectedSap = null) {
        $.ajax({
            url: '{{ route("organizational-chart.available-employees") }}',
            method: 'GET',
            data: {
                kode_unit: currentUnit,
                level: level
            },
            success: function (response) {
                if (response.success) {
                    const $select = $('#employeeSelect');
                    $select.empty();
                    $select.append('<option value="">-- Pilih Karyawan --</option>');

                    response.data.forEach(function (emp) {
                        const $option = $('<option>')
                            .val(emp.sap)
                            .text(emp.name + ' (' + emp.level + ') - ' + emp.desc_position);

                        if (selectedSap && emp.sap === selectedSap) {
                            $option.prop('selected', true);
                        }

                        $select.append($option);
                    });

                    $select.trigger('change');
                }
            },
            error: function (xhr) {
                console.error('Error loading employees:', xhr);
            }
        });
    }

    // Handle form submission
    $('#employeeForm').on('submit', function (e) {
        e.preventDefault();

        const employeePositionId = $('#employeePositionId').val();
        const positionId = $('#positionId').val();
        const nik = $('#employeeSelect').val();

        if (!nik) {
            alert('Silakan pilih karyawan');
            return;
        }

        if (isEditMode && employeePositionId) {
            // Update
            updateEmployeePosition(employeePositionId, nik);
        } else {
            // Create
            createEmployeePosition(positionId, nik);
        }
    });

    // Create employee position
    function createEmployeePosition(positionId, nik) {
        $.ajax({
            url: '{{ route("organizational-chart.employee-position.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id_position: positionId,
                nik: nik
            },
            success: function (response) {
                if (response.success) {
                    alert('Employee berhasil ditambahkan');
                    employeeModal.hide();
                    loadChartData();
                } else {
                    alert(response.message || 'Gagal menambahkan employee');
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Gagal menambahkan employee';
                alert(message);
            }
        });
    }

    // Update employee position
    function updateEmployeePosition(employeePositionId, nik) {
        $.ajax({
            url: '{{ route("organizational-chart.employee-position.update", ":id") }}'.replace(':id', employeePositionId),
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                nik: nik
            },
            success: function (response) {
                if (response.success) {
                    alert('Employee position berhasil diupdate');
                    employeeModal.hide();
                    loadChartData();
                } else {
                    alert(response.message || 'Gagal mengupdate employee position');
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Gagal mengupdate employee position';
                alert(message);
            }
        });
    }

    // Delete employee position
    function deleteEmployeePosition(node) {
        if (!confirm('Apakah Anda yakin ingin menghapus employee dari posisi ini?')) {
            return;
        }

        $.ajax({
            url: '{{ route("organizational-chart.employee-position.destroy", ":id") }}'.replace(':id', node.employee_position_id),
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    alert('Employee position berhasil dihapus');
                    loadChartData();
                } else {
                    alert(response.message || 'Gagal menghapus employee position');
                }
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.message || 'Gagal menghapus employee position';
                alert(message);
            }
        });
    }
</script>
@endsection