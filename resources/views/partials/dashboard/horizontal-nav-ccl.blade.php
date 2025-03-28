<nav id="navbar_main" class="mobile-offcanvas nav navbar navbar-expand-xl hover-nav horizontal-nav mx-md-auto  col-md-7 mh_navbar_ccl pb-0">
   <div class="container-fluid d-flex justify-content-center">
      <div class="offcanvas-header">
         <div class="navbar-brand">
            <svg width="180"  xmlns="http://www.w3.org/2000/svg">
               <image href="{{asset('images/ccls/logo_color.svg')}}"  width="180" />
            </svg>             
         </div>
         <button class="btn-close float-end"></button>
      </div>
      <ul class="navbar-nav op-menu">
          {{-- <li class="nav-item {{ Request::is('personas-trabajadoras')  || Request::is('localiza-tu-ccl')  ? 'active' : '' }}"><a class="nav-link text-center " href="{{route('ptrabajadoraspublic')}}"> <span class="d-md-block d-lg-block"> PERSONAS </span> TRABAJADORAS   </a></li> --}}
          {{-- <li class="nav-item {{ Request::is('personas-empleadoras') ? 'active' : '' }}"><a class="nav-link text-center" href="{{route('pempleadoraspublic')}}"> <span class="d-md-block d-lg-block"> PERSONAS </span>  EMPLEADORAS </a></li> --}}
          {{-- <li class="nav-item {{ Request::is('personas-conciliadoras') ? 'active' : '' }}"><a class="nav-link text-center" href="{{route('pconciliadoraspublic')}}"><span class="d-md-block d-lg-block"> PERSONAS </span>  CONCILIADORAS </a></li> --}}
          {{-- <li class="nav-item {{ Request::is('personas-conciliadoras') ? 'active' : '' }}"><a class="nav-link text-center" href="{{route('pconciliadoraspublic')}}"><span class="d-md-block d-lg-block">  ACERCA DE </span>  CONACENTROS </a></li>           --}}
      </ul>
   </div> <!-- container-fluid.// -->    
</nav>

