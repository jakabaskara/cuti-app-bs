@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Kalender Libur & Cuti Bersama</h3>
                    </div>
                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <span class="material-icons">add</span> Tambah Data
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>Tampilan:</label>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary active" id="btnMonthView"
                                    onclick="switchView('month')">
                                    <span class="material-icons">calendar_today</span> Bulanan
                                </button>
                                <button type="button" class="btn btn-outline-primary" id="btnYearView"
                                    onclick="switchView('year')">
                                    <span class="material-icons">event_note</span> Tahunan
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <label>Navigasi:</label>
                            <div class="btn-group w-100" role="group">
                                <button class="btn btn-outline-secondary" onclick="navigate('prev')">
                                    <span class="material-icons">chevron_left</span>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="navigate('today')">Hari Ini</button>
                                <button class="btn btn-outline-secondary" onclick="navigate('next')">
                                    <span class="material-icons">chevron_right</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Tahun:</label>
                            <select class="form-select" id="yearSelect" onchange="loadCalendar()">
                                @for ($y = 2024; $y <= 2030; $y++)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <h4 id="currentPeriod" class="text-center"></h4>
                        </div>
                    </div>

                    <div id="monthViewContainer">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="monthCalendar">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Minggu</th>
                                        <th class="text-center">Senin</th>
                                        <th class="text-center">Selasa</th>
                                        <th class="text-center">Rabu</th>
                                        <th class="text-center">Kamis</th>
                                        <th class="text-center">Jumat</th>
                                        <th class="text-center">Sabtu</th>
                                    </tr>
                                </thead>
                                <tbody id="monthCalendarBody">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="yearViewContainer" style="display: none;">
                        <div class="row" id="yearCalendarBody">
                        </div>
                    </div>

                    <div class="mt-3">
                        <h5>Keterangan:</h5>
                        <div class="d-flex flex-wrap">
                            <div class="legend-item">
                                <span class="legend-badge legend-libur"></span>
                                <span>Libur Biasa</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-badge legend-cuti"></span>
                                <span>Cuti Bersama</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Libur/Cuti Bersama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Keterangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_libur" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_libur" name="jenis_libur" required>
                                <option value="">Pilih Jenis</option>
                                <option value="libur_biasa">Libur Biasa</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
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

    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Libur/Cuti Bersama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_tanggal" class="form-label">Tanggal <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Keterangan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_description" name="description"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jenis_libur" class="form-label">Jenis <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="edit_jenis_libur" name="jenis_libur" required>
                                <option value="">Pilih Jenis</option>
                                <option value="libur_biasa">Libur Biasa</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Libur/Cuti Bersama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-4">Tanggal:</dt>
                        <dd class="col-sm-8" id="detail_tanggal"></dd>
                        <dt class="col-sm-4">Keterangan:</dt>
                        <dd class="col-sm-8" id="detail_description"></dd>
                        <dt class="col-sm-4">Jenis:</dt>
                        <dd class="col-sm-8" id="detail_jenis"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" onclick="editFromDetail()">
                        <span class="material-icons">edit</span> Edit
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteFromDetail()">
                        <span class="material-icons">delete</span> Hapus
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <style>
        /* Reset text color untuk kalender */
        #monthCalendar tbody td {
            background-color: white;
            color: #212529 !important;
        }

        #monthCalendar {
            background-color: white;
        }

        #monthCalendar thead th {
            color: white !important;
            background-color: #343a40;
        }

        /* Cell kalender */
        #monthCalendar .calendar-day {
            height: 120px;
            vertical-align: top;
            padding: 5px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s;
            background-color: white !important;
            color: #212529 !important;
        }

        #monthCalendar .calendar-day:hover {
            background-color: #f0f0f0 !important;
        }

        #monthCalendar .calendar-day.other-month {
            background-color: #f9f9f9 !important;
            color: #6c757d !important;
        }

        #monthCalendar .calendar-day.today {
            background-color: #fff3cd !important;
            color: #212529 !important;
        }

        #monthCalendar .calendar-day.has-event {
            font-weight: bold;
        }

        /* Nomor tanggal - HARUS HITAM */
        #monthCalendar .calendar-day-number {
            font-weight: bold;
            margin-bottom: 5px;
            color: #212529 !important;
            font-size: 18px;
        }

        #monthCalendar .calendar-day.other-month .calendar-day-number {
            color: #6c757d !important;
        }

        /* Event block - teks PUTIH karena background berwarna */
        #monthCalendar .calendar-event {
            font-size: 11px;
            padding: 3px 6px;
            margin-bottom: 3px;
            border-radius: 4px;
            color: white !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        #monthCalendar .calendar-event * {
            color: white !important;
        }

        #monthCalendar .calendar-event .material-icons {
            font-size: 14px;
        }

        #monthCalendar .event-libur-biasa {
            background-color: #ff6b6b !important;
            border-left: 3px solid #ff4757;
        }

        #monthCalendar .event-cuti-bersama {
            background-color: #4ecdc4 !important;
            border-left: 3px solid #3bb6ad;
        }

        /* Year view styling */
        .year-month-card {
            height: 280px;
            margin-bottom: 20px;
        }

        .year-month-card .card {
            height: 100%;
        }

        .year-month-header {
            background-color: #343a40 !important;
            color: white !important;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .year-month-body {
            padding: 10px;
            height: calc(100% - 50px);
            overflow-y: auto;
            background-color: white !important;
        }

        .year-event-item {
            font-size: 12px;
            padding: 5px 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            color: white !important;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .year-event-item * {
            color: white !important;
        }

        .year-event-item .material-icons {
            font-size: 16px;
        }

        .year-event-item:hover {
            opacity: 0.8;
        }

        .year-event-item.event-libur-biasa {
            background-color: #ff6b6b !important;
        }

        .year-event-item.event-cuti-bersama {
            background-color: #4ecdc4 !important;
        }

        /* Legend styling */
        .legend-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-right: 20px;
            color: #212529;
        }

        .legend-badge {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            display: inline-block;
        }

        .legend-libur {
            background-color: #ff6b6b;
        }

        .legend-cuti {
            background-color: #4ecdc4;
        }

        /* Modal detail styling */
        #detailModal .modal-body {
            color: #212529;
        }

        #detailModal .modal-body dt,
        #detailModal .modal-body dd {
            color: #212529 !important;
        }

        #detail_jenis .badge * {
            color: white !important;
        }
    </style>

    <script>
        let currentView = 'month';
        let currentYear = {{ date('Y') }};
        let currentMonth = {{ date('m') }};
        let calendarData = [];
        let selectedEventId = null;

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];

        document.addEventListener('DOMContentLoaded', function() {
            loadCalendar();
            setupFormHandlers();
        });

        function loadCalendar() {
            const year = document.getElementById('yearSelect').value;
            currentYear = parseInt(year);

            let url = `{{ route('admin.kalender.data') }}?year=${currentYear}`;
            if (currentView === 'month') {
                url += `&month=${currentMonth}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    calendarData = data.data;
                    if (currentView === 'month') {
                        renderMonthView();
                    } else {
                        renderYearView();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading calendar data');
                });
        }

        function switchView(view) {
            currentView = view;
            document.getElementById('btnMonthView').classList.toggle('active', view === 'month');
            document.getElementById('btnYearView').classList.toggle('active', view === 'year');
            document.getElementById('monthViewContainer').style.display = view === 'month' ? 'block' : 'none';
            document.getElementById('yearViewContainer').style.display = view === 'year' ? 'block' : 'none';
            loadCalendar();
        }

        function navigate(direction) {
            if (currentView === 'month') {
                if (direction === 'prev') {
                    currentMonth--;
                    if (currentMonth < 1) {
                        currentMonth = 12;
                        currentYear--;
                        document.getElementById('yearSelect').value = currentYear;
                    }
                } else if (direction === 'next') {
                    currentMonth++;
                    if (currentMonth > 12) {
                        currentMonth = 1;
                        currentYear++;
                        document.getElementById('yearSelect').value = currentYear;
                    }
                } else if (direction === 'today') {
                    const today = new Date();
                    currentYear = today.getFullYear();
                    currentMonth = today.getMonth() + 1;
                    document.getElementById('yearSelect').value = currentYear;
                }
            } else {
                if (direction === 'prev') {
                    currentYear--;
                    document.getElementById('yearSelect').value = currentYear;
                } else if (direction === 'next') {
                    currentYear++;
                    document.getElementById('yearSelect').value = currentYear;
                } else if (direction === 'today') {
                    currentYear = new Date().getFullYear();
                    document.getElementById('yearSelect').value = currentYear;
                }
            }
            loadCalendar();
        }

        function renderMonthView() {
            const firstDay = new Date(currentYear, currentMonth - 1, 1);
            const lastDay = new Date(currentYear, currentMonth, 0);
            const prevLastDay = new Date(currentYear, currentMonth - 1, 0);

            document.getElementById('currentPeriod').textContent =
                `${monthNames[currentMonth - 1]} ${currentYear}`;

            const startDay = firstDay.getDay();
            const totalDays = lastDay.getDate();
            const prevTotalDays = prevLastDay.getDate();

            let html = '';
            let dayCount = 1;
            let nextMonthDay = 1;

            for (let week = 0; week < 6; week++) {
                html += '<tr>';
                for (let day = 0; day < 7; day++) {
                    const cellIndex = week * 7 + day;

                    if (cellIndex < startDay) {
                        const prevDay = prevTotalDays - startDay + cellIndex + 1;
                        html +=
                            `<td class="calendar-day other-month" style="background-color: #f9f9f9; color: #6c757d;"><div class="calendar-day-number" style="color: #6c757d !important;">${prevDay}</div></td>`;
                    } else if (dayCount <= totalDays) {
                        const dateStr =
                            `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                        const events = calendarData.filter(e => e.tanggal === dateStr);
                        const today = new Date();
                        const isToday = dayCount === today.getDate() && currentMonth === (today.getMonth() + 1) &&
                            currentYear === today.getFullYear();

                        let classes = 'calendar-day';
                        if (isToday) classes += ' today';
                        if (events.length > 0) classes += ' has-event';

                        const bgColor = isToday ? '#fff3cd' : 'white';
                        html +=
                            `<td class="${classes}" onclick="onDayClick('${dateStr}')" style="background-color: ${bgColor}; color: #212529 !important;">`;
                        html +=
                            `<div class="calendar-day-number" style="color: #212529 !important; font-weight: bold;">${dayCount}</div>`;
                        events.forEach(event => {
                            const bgColor = event.jenis_libur === 'libur_biasa' ? '#ff6b6b' : '#4ecdc4';
                            html +=
                                `<div class="calendar-event event-${event.jenis_libur}" onclick="showDetail(${event.id}, event)" style="background-color: ${bgColor}; color: white !important; padding: 4px 8px;">
                                    ${event.description}
                                </div>`;
                        });
                        html += '</td>';
                        dayCount++;
                    } else {
                        html +=
                            `<td class="calendar-day other-month" style="background-color: #f9f9f9; color: #6c757d;"><div class="calendar-day-number" style="color: #6c757d !important;">${nextMonthDay}</div></td>`;
                        nextMonthDay++;
                    }
                }
                html += '</tr>';
                if (dayCount > totalDays && nextMonthDay > 7) break;
            }

            document.getElementById('monthCalendarBody').innerHTML = html;
        }

        function renderYearView() {
            document.getElementById('currentPeriod').textContent = `Tahun ${currentYear}`;

            let html = '';
            for (let month = 1; month <= 12; month++) {
                const events = calendarData.filter(e => {
                    const eventDate = new Date(e.tanggal);
                    return eventDate.getMonth() + 1 === month;
                });

                html += `<div class="col-md-4 col-lg-3">
                    <div class="card year-month-card">
                        <div class="year-month-header">${monthNames[month - 1]}</div>
                        <div class="year-month-body">`;

                if (events.length > 0) {
                    events.forEach(event => {
                        const date = new Date(event.tanggal);
                        const bgColor = event.jenis_libur === 'libur_biasa' ? '#ff6b6b' : '#4ecdc4';
                        html +=
                            `<div class="year-event-item event-${event.jenis_libur}" onclick="showDetail(${event.id}, event)" style="background-color: ${bgColor}; color: white !important; padding: 6px 10px;">
                                ${date.getDate()} - ${event.description}
                            </div>`;
                    });
                } else {
                    html +=
                        '<small class="text-muted" style="color: #6c757d !important;">Tidak ada libur/cuti bersama</small>';
                }

                html += `</div></div></div>`;
            }

            document.getElementById('yearCalendarBody').innerHTML = html;
        }

        function onDayClick(dateStr) {
            document.getElementById('tanggal').value = dateStr;
            const modal = new bootstrap.Modal(document.getElementById('addModal'));
            modal.show();
        }

        function showDetail(id, event) {
            event.stopPropagation();
            selectedEventId = id;

            fetch(`{{ route('admin.kalender.show', '') }}/${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        const date = new Date(data.tanggal);
                        const tanggalEl = document.getElementById('detail_tanggal');
                        tanggalEl.textContent = date.toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        tanggalEl.style.color = '#212529';

                        const descEl = document.getElementById('detail_description');
                        descEl.textContent = data.description;
                        descEl.style.color = '#212529';

                        const label = data.jenis_libur === 'libur_biasa' ? 'Libur Biasa' : 'Cuti Bersama';
                        const bgColor = data.jenis_libur === 'libur_biasa' ? '#ff6b6b' : '#4ecdc4';
                        document.getElementById('detail_jenis').innerHTML =
                            `<span class="badge event-${data.jenis_libur}" style="background-color: ${bgColor}; padding: 8px 12px; color: white !important;">
                                ${label}
                            </span>`;

                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading detail');
                });
        }

        function editFromDetail() {
            bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();

            fetch(`{{ route('admin.kalender.show', '') }}/${selectedEventId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_tanggal').value = data.tanggal;
                        document.getElementById('edit_description').value = data.description;
                        document.getElementById('edit_jenis_libur').value = data.jenis_libur;

                        const modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.show();
                    }
                });
        }

        function deleteFromDetail() {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

            fetch(`{{ route('admin.kalender.destroy', '') }}/${selectedEventId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
                        alert(result.message);
                        loadCalendar();
                    } else {
                        alert(result.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting data');
                });
        }

        function setupFormHandlers() {
            document.getElementById('addForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

                fetch('{{ route('admin.kalender.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                            this.reset();
                            alert(result.message);
                            loadCalendar();
                        } else {
                            alert(result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving data');
                    });
            });

            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                const id = data.id;

                fetch(`{{ route('admin.kalender.update', '') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                            this.reset();
                            alert(result.message);
                            loadCalendar();
                        } else {
                            alert(result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating data');
                    });
            });
        }
    </script>
@endsection
