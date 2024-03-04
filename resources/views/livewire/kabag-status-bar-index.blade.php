 <div class="row">
     <div class="col-xl-4">
         <div class="card widget widget-stats">
             <div class="card-body">
                 <div class="widget-stats-container d-flex">
                     <div class="widget-stats-icon widget-stats-icon-success">
                         <i class="material-icons-outlined">check_circle</i>
                     </div>
                     <div class="widget-stats-content flex-fill">
                         <span class="widget-stats-title text-dark">Disetujui</span>
                         <span class="widget-stats-amount">{{ $disetujui }}</span>
                         <span class="widget-stats-info">Form Cuti Disetujui</span>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="col-xl-4">
         <div class="card widget widget-stats">
             <div class="card-body">
                 <div class="widget-stats-container d-flex">
                     <div class="widget-stats-icon widget-stats-icon-warning">
                         <i class="material-icons-outlined">info</i>
                     </div>
                     <div class="widget-stats-content flex-fill">
                         <span class="widget-stats-title text-dark">Pending</span>
                         <span class="widget-stats-amount">{{ $pending }}</span>
                         <span class="widget-stats-info">Form Cuti Menunggu Respon</span>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <div class="col-xl-4">
         <div class="card widget widget-stats">
             <div class="card-body">
                 <div class="widget-stats-container d-flex">
                     <div class="widget-stats-icon widget-stats-icon-danger">
                         <i class="material-icons-outlined">highlight_off</i>
                     </div>
                     <div class="widget-stats-content flex-fill">
                         <span class="widget-stats-title text-dark">Dibatalkan</span>
                         <span class="widget-stats-amount">{{ $ditolak }}</span>
                         <span class="widget-stats-info">Form Cuti Ditolak</span>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
