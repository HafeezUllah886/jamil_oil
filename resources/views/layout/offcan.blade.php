 <div class="offcanvas offcanvas-end" aria-labelledby="canvasEndLabel" id="canvasEnd" tabindex="-1" role="dialog">
     <div class="offcanvas-header bg-primary-100 ">
         <h5 class="offcanvas-title f-w-600" id="canvasEndLabel">
             Filters </h5>
         <button aria-label="Close" class="btn bg-light-primary px-2 py-1 text-reset btn-sm" data-bs-dismiss="offcanvas"
             type="button"><i class="ti ti-x f-s-18 d-inline"></i></button>
     </div>
     <form action="">
         <div class="offcanvas-body position-relative bg-primary-100">

             @yield('filter-content')

         </div>
         <div class="offcanvas-footer canvas-footer text-end">

             <button type="submit" class="btn  bg-gradient-primary w-100">
                 Apply
             </button>
         </div>
     </form>
 </div>
