
<footer class="footer">
    <div class="container py-4 py-md-5 px-4 px-md-3 text-body-secondary">
    <div class="row">
      <div class="col-lg-2 mb-3">
        <a class="d-inline-flex align-items-center mb-2 text-body-emphasis text-decoration-none" href="/personas-trabajadoras" >
            <svg width="230" height="90" xmlns="http://www.w3.org/2000/svg">
                <image href="{{asset('images/ccls/logo_blanco.svg')}}"  width="230" height="90"/>
            </svg>    
        </a>
        <ul class="list-unstyled small">
          <li class="mb-2">Somos un mecanismo de coordinación, interlocución y promoción de la cultura de la conciliación laboral.</li>          
        </ul>
      </div>
      <div class="col-6 col-lg-2 offset-lg-1 mb-3 colMenuFooter">
        <h5 class="mb-2" >MENÚ</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#">CONÓCENOS</a></li>
          <li class="mb-2"><a href="#">HERRAMIENTAS</a></li>
          <li class="mb-2 {{ Request::is('personas-trabajadoras') ? 'active' : '' }}"><a href="{{route('ptrabajadoraspublic')}}">PERSONAS TRABAJADORAS</a></li>
          <li class="mb-2 {{ Request::is('personas-empleadoras') ? 'active' : '' }}"><a href="{{route('pempleadoraspublic')}}">PERSONAS EMPLEADORAS</a></li>
          <li class="mb-2 {{ Request::is('personas-conciliadoras') ? 'active' : '' }}"><a href="{{route('pconciliadoraspublic')}}">PERSONAS CONCILEADORAS</a></li>          
          <li class="mb-2 {{ Request::is('personas-conciliadoras') ? 'active' : '' }}"><a href="{{asset('storage/AvisodePrivacidad.pdf')}}" download>AVISO DE PRIVACIDAD</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-1 mb-3">
        <h5 class="mb-2">SÍGUENOS</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#">Facebook</a></li>
          <li class="mb-2"><a href="#">Instagram</a></li>
          <li class="mb-2"><a href="#">Twitter</a></li>          
        </ul>
      </div>
      <div class="col-6 col-lg-2 mb-3">
        <h5 class="mb-2" >CONTÁCTANOS</h5>
        <ul class="list-unstyled">
          <li class="mb-2">Si tienes más dudas, contáctanos a través de:</li>
          <li class="mb-2"><a href="mailto:info@conciliacionlaboral.com.mx" target="_blank" rel="noopener">info@conciliacionlaboral.com.mx</a></li>
          <li class="mb-2">(000) 000- 0000</li>          
        </ul>
      </div>
      <div class="col-6 col-lg-3 mb-3">
        <h5 class="mb-2">ÚNETE A NUESTRO MAIL LIST</h5>
        <div class="bd-example">
            <div class="form-group form-group-alt">
                <input type="text" class="form-control" placeholder="Ingresa tu correo electrónico">
            </div>
            <button class="btn btn-primary btn-primary-ccls rounded-pill" >Enviar</button>
        </div>

      </div>
    </div>
  </div>
</footer>

