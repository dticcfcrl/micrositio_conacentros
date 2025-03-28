
<!--  biblioteca para openstreetmap  -->
<script src="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.umd.js"></script>
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   (function ($) {

    /*
    Busqueda usando mapa
    */
    let customIcon = {
        iconUrl: "/assets/contenidos/iconos/edificio.png",
        iconSize:[25,25],
    }
    let iconoPin = L.icon(customIcon);
    let iconOptions = {
        title:"ccls",
        draggable:true,
        icon:iconoPin
    }
    
    let searchInput = document.getElementById('search');
    let resultList = document.getElementById('result-list');
    let mapContainer = document.getElementById('map-container');
    let currentMarkers = [];
    let viajesIdaMarkers; 
    let cclsMarkers;
    //viajesIdaMarkers = "viajesCompartidosIda";
    cclsMarkers = {{ centers_location:locations ambito="{{ get:ambito }}" }};
    
    // Agregar los markers de los viajes compartidos de ida        
    var map = L.map(mapContainer).setView([23.634501, -102.552784], 5);
         

    //map = L.map(mapContainer).setView([19.4326296, -99.1331785], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    function onClick(e) {
        var popup = e.target.getPopup();
        var content = popup.getContent();

    }

    let cclEstado = document.querySelector('#_cclEstados');
    let datosCCL  = document.querySelector('#_datosCCL');
    let markers ;
    // Colocar ccls por estados  apartir de la selección del dropdown

    function verMapa(){   
        const cclMaps = document.querySelectorAll('#result-list .cclContent a.vermapa');

        cclMaps.forEach(cclMap => {
            cclMap.addEventListener('click', function handleClick(event) {      
                const clickedData = JSON.parse(event.target.querySelector('span').innerHTML);          
                const position = new L.LatLng(clickedData.lat, clickedData.lon);
                map.flyTo(position, 16);
                $('html, body').animate({
                    scrollTop: $("#map-container").offset().top - 50
                }, 50);
            });
        });
    }               


    function colocarCCLEstado(){
        let sectorOp ;
        let textOpSector;
        cclEstado.addEventListener('change', (event) => {                 
            if(cclEstado.value !== "Selecciona un estado"){     
                customIcon = {
                    iconUrl: "/assets/contenidos/iconos/edificio.png",
                    iconSize:[35,35],
                }
                currentMarkers = [];
                cclsMarkers = {{ centers_location:locations ambito="{{ get:ambito }}" }};
                var estados = {{ centers_location:states }};
                var estadoId = parseInt(cclEstado.value);
                let estadoSelNom, latMap, longMap;
                console.log(estados);
                $.each(estados, function(index, estado) {
                    console.log(estado);
                    if (estadoId == estado.clave) {
                        estadoSelNom = estado.nombre;     
                        latMap = estado.lat;
                        longMap = estado.long;                          
                    }
                });
                
                map.remove();
                map = L.map(mapContainer).setView([latMap, longMap], 6);
                markers = new Array();     
                resultList.innerHTML= "";              
                
                $.each(cclsMarkers, function(index, ccl) {
                    if (ccl.estado === estadoSelNom) {
                        let lat = ccl.lat;
                        let long = ccl.long;
                        let marker = new L.Marker([lat, long], {icon: iconoPin})
                            .bindPopup(`<a href='${ccl.url_google}' target="_blank" rel="noopener noreferrer">
                                            Centro de conciliación laboral:<br>
                                            Domicilio: ${ccl.direccion}<br>
                                            Contacto: ${ccl.contacto}
                                        </a>`)
                            .addTo(map);
                        markers.push(marker);

                        const div = document.createElement('div');
                        div.classList.add('w-100', 'justify-content-between', 'cclContent', 'my-2');
                        const h5 = document.createElement('h5');
                        const p1 = document.createElement('p');
                        const p2 = document.createElement('p');
                        const p3 = document.createElement('p');
                        const p4 = document.createElement('p');
                        const p41 = document.createElement('p');
                        const p5 = document.createElement('p');

                        let cad = JSON.stringify({ lat: lat, lon: long }, undefined, 10);

                        h5.classList.add('dorada');
                        p1.classList.add('mb-1', 'direccionCCL');
                        p2.classList.add('mb-1', 'ambitoCCL');
                        p3.classList.add('mb-1', 'contactoCCL');
                        p4.classList.add('mb-1', 'contactoCCL');
                        p41.classList.add('mb-1', 'contactoCCL');
                        p5.classList.add('mb-1', 'enlaceMapa', 'dorada', 'row');

                        if (ccl.ambito === "Federal") {
                            h5.innerHTML = "Centro Federal de Conciliación y Registro Laboral (CFCRL)";
                            p41.innerHTML = `Si quieres hacer una cita de conciliación: <a href='${ccl.liga_cita}' target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;

                            p5.innerHTML = `<a class="vermapa dorada col-12 text-center" rel="noopener noreferrer">Ver en el mapa<span class='d-none'>${cad}</span></a> <hr>
                                        <a class="dorada col-12" href='https://www.gob.mx/cfcrl' target="_blank" rel="noopener noreferrer">
                                            Ir al sitio web del Centro de Conciliación<span class='d-none'></span>
                                        </a>`;
                            p4.innerHTML = `Si eres persona empleadora y quieres hacer una cita para ratificar tu convenio: <a href="agendar-cita?estado=${ccl.estado}&id=${ccl.id}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;
                        } else {
                            h5.innerHTML = "Centro de Conciliación Laboral local";
                            p41.innerHTML = `Si quieres hacer una cita de conciliación: <a href=" ${ccl.liga_cita}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;
                            p5.innerHTML = `<a class="vermapa dorada col-12 text-center" rel="noopener noreferrer">Ver en el mapa<span class='d-none'>${cad}</span></a> <hr>
                                        <a class="dorada col-12" href='${ccl.link}' target="_blank" rel="noopener noreferrer">
                                            Ir al sitio web del Centro de Conciliación<span class='d-none'></span>
                                        </a>`;
                            
                            //p4.innerHTML = `Si eres persona empleadora y quieres hacer una cita para ratificar tu convenio: <a href="${ccl.liga_cita_local}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;            
                            /*p4.innerHTML = `Si eres persona empleadora y quieres hacer una cita para ratificar tu convenio: <a href="agendar-cita?estado=${ccl.estado}&id=${ccl.id}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;*/
                            
                            if(!!ccl.liga_cita_local){            
                                p4.innerHTML = `Si eres persona empleadora y quieres hacer una cita para ratificar tu convenio: <a href="${ccl.liga_cita_local}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;
                            }else{
                                p4.innerHTML = `Si eres persona empleadora y quieres hacer una cita para ratificar tu convenio: <a href="agendar-cita?estado=${ccl.estado}&id=${ccl.id}" target="_blank" rel="noopener noreferrer" class="enlaceSolCita dorada">Solicita tu cita aquí</a>`;
                            }
                           
                        }

                        p1.innerHTML = `Domicilio: ${ccl.direccion}`;
                        p3.innerHTML = `Contacto: ${ccl.contacto}`;
                        
                       
                        
                        /*p5.innerHTML = `<a class="vermapa dorada col-12 text-center" rel="noopener noreferrer">Ver en el mapa<span class='d-none'>${cad}</span></a> <hr>
                                        <a class="dorada col-12" href='${ccl.link}' target="_blank" rel="noopener noreferrer">
                                            Ir al sitio web del Centro de Conciliación<span class='d-none'></span>
                                        </a>`;*/

                        div.appendChild(h5);
                        div.appendChild(p1);
                        div.appendChild(p3);
                        div.appendChild(p4);
                        div.appendChild(p41);
                        div.appendChild(p5);

                        resultList.appendChild(div);
                    }
                });

                //map = L.map(mapContainer).setView([19.4326296, -99.1331785], 8);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);                    
                //alert("cosa " +sectorOp);                                    

                verMapa();
                datosCCL.classList.remove('ocultar');

            }else{                    
                Swal.fire({
                        icon: "error",
                        title: "ADVERTENCIA",
                        text: "Debes seleccionar un estado de la república",                        
                });    
            }                
        });       

    }
    colocarCCLEstado();  

}(jQuery));

</script>
