<div class="m-0 watermark-container" id="pdf_div">
    <div class="watermark">Documento sin validez oficial</div>
    <div class="" id="logo">
        <img src="/assets/contenidos/logo.png" width="200" height="50">
        <div class="text-end me-3">
            <p>
                Fecha de consulta: {{ Carbon\Carbon::now('America/Mexico_City')->format('Y-m-d') }}
            </p>
        </div>
    </div>
    <h2 class="section-title my-2 dorada text-center">Calculadora de prestaciones por liquidación</h2>
    <hr>

    <div id="resultado" class="p-0">
        <div class="row">

            <div id="detalles" class="row">
                <h4 class="dorada mb-2">
                    Información para el cálculo:
                </h4>

                <div class="col-md-6 cols col-6">
                    <p>
                        Fecha inicio: <span id="detalle_fecha_inicio">{{ $datos['fechaInicio'] }}</span>
                    </p>
                    <p>
                        Años de antigüedad: <span
                            id="detalle_antiguedad">{{ $datos['anios_antiguedad'] >= 15 ? '15 años o más' : 'menos de 15 años' }}</span>
                    </p>
                    <p>
                        Salario mínimo: <span id="detalle_salario_minimo">{{ $datos['salarioMinimo'] }}</span>
                    </p>
                </div>
                <div class="col-md-6 cols col-6">
                    <p>
                        Fecha salida: <span id="detalle_fecha_salida">{{ $datos['fechaSalida'] }}</span>
                    </p>
                    <p>
                        Zona fronteriza norte: <span id="detalle_zona">{{ $datos['zona_fronteriza'] }}</span>
                    </p>
                    <p>
                        Salario diario: <span id="detalle_salario_diario">{{ $datos['remuneracionDiaria'] }}</span>
                    </p>
                </div>
                <div class="col-12">
                    <p>
                        Profesión: <span id="detalle_profesion">{{ $datos['profesion'] }}</span>
                    </p>
                </div>
            </div>

            <div id="prestaciones_completas" class="col-12 col-md-12 table-responsive">
                <div class="my-2">
                    <h4 class="dorada">
                        Resultado:
                    </h4>
                </div>
                <table class="table">
                    <thead>
                        <tr class="dorada">
                            <th>
                                Prestación
                                <div><br class="d-sm-block d-md-none"></div>
                            </th>
                            <th class="propuesta_completa">
                                Propuesta 100%
                            </th>
                            <th class="propuesta_parcial">
                                Notas
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="text-grey">Aguinaldo</th>
                            <td id="aguinaldo_completa" class="propuesta_completa">{{ $datos['completa']['aguinaldo'] }}
                            </td>
                            <td id="aguinaldo_parcial" class="propuesta_parcial">Derecho irrenunciable</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-grey">Vacaciones</th>
                            <td id="vacaciones_completa" class="propuesta_completa">
                                {{ $datos['completa']['vacaciones'] }}</td>
                            <td id="vacaciones_parcial" class="propuesta_parcial">Derecho irrenunciable</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-grey">Prima Vacacional</th>
                            <td id="prima_vacacional_completa" class="propuesta_completa">
                                {{ $datos['completa']['prima_vacacional'] }}</td>
                            <td id="prima_vacacional_parcial" class="propuesta_parcial">Derecho irrenunciable</td>
                        </tr>
                        <tr>
                            <th scope="row"
                                class="{{ $datos['anios_antiguedad'] >= 15 ? 'text-green' : 'text-orange' }}">Prima de
                                Antigüedad</th>
                            <td id="prima_antiguedad_completa" class="propuesta_completa">
                                {{ $datos['completa']['prima_antiguedad'] }}</td>
                            <td id="prima_antiguedad_parcial"
                                class="{{ $datos['anios_antiguedad'] >= 15 ? 'text-green' : 'text-orange' }}">
                                {{ $datos['anios_antiguedad'] >= 15 ? 'Derecho irrenunciable' : 'Compensación negociable' }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-orange">Indemnización <br> Constitucional</th>
                            <td id="indemizacion_completa" class="propuesta_completa">
                                {{ $datos['completa']['indemnizacion'] }}</td>
                            <td id="indemizacion_parcial" class="text-orange ">Compensación negociable</td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr class="text-dark">
                            <th>TOTAL</th>
                            <td id="total_completa" class="propuesta_completa">{{ $datos['completa']['total'] }}</td>
                            <td id="total_parcial" class="propuesta_parcial"></td>
                        </tr>
                    </thead>
                </table>


            </div>
        </div>
    </div>
    <br><br><br><br><br><br>
</div>
<div class="m-0 watermark-container" id="pdf_div">
    <div class="watermark">Documento sin validez oficial</div>
    <div id="other_amounts">
        <hr>
        <div class="d-sm-block d-md-none" style="height: 50px"><br></div>
        <h4 class="dorada mb-2">
            Cálculo de otros procentajes:
        </h4>

        <div>
            <p>PROPUESTA 90%: <span>{{ $datos['al90']['total'] }}</span></p>
        </div>
        <div>
            <p>PROPUESTA 80%: <span>{{ $datos['al80']['total'] }}</span></p>
        </div>
        <div>
            <p>PROPUESTA 70%: <span>{{ $datos['al70']['total'] }}</span></p>
        </div>
        <div>
            <p>PROPUESTA 60%: <span>{{ $datos['al60']['total'] }}</span></p>
        </div>
        <div>
            <p>PROPUESTA 50%: <span>{{ $datos['al50']['total'] }}</span></p>
        </div>
    </div>

    <div style="height: 400px"><br></div>

    <div id="leyenda" style="text-align: justify; width: 95%;">
        <small>
            <p>
                La "Calculadora para cuantificar la liquidación por despido injustificado" es una herramienta de
                orientación diseñada para que las personas trabajadoras puedan informarse sobre los conceptos y cálculos
                que se abordarán en una audiencia de conciliación.
            </p>
        </small>
        <h4 class="h4 dorada my-3">
            Nota Importante
        </h4>
        <p>
        </p>
        <p style="text-align: justify;">Estos cálculos son un estimado basado en condiciones generales establecidas en
            la Ley Federal del Trabajo. Cuando una persona trabajadora tiene prestaciones mayores a las de la ley, la
            compensación por un despido injustificado puede ser mayor. Recuerda que, en el Centro de Conciliación, la
            persona conciliadora asignada a tu solicitud te apoyará para realizar un cálculo preciso para tu caso.</p>
    </div>
</div>
<br><br><br>
<div class="watermark-container" style="font-size: 9px; padding: 2px;">
    <div class="watermark">Documento sin validez oficial</div>
    <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Explicación de las compensaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>


    <div class="modal-body">
        <h4 class="h4 dorada my-3">
            Despido justificado o injustificado
        </h4>
        <p>En caso de un despido justificado, el empleador no está obligado a pagar una indemnización al trabajador, más
            allá de las prestaciones que ya le correspondan por ley, es decir, las mismas prestaciones "irrenunciables",
            que incluyen las proporciones de aguinaldo, vacaciones y prima vacacional del año en curso.</p>
        <p style="text-align: justify;">Mientras que un despido injustificado ocurre cuando el empleador termina la
            relación laboral sin que exista una justificación bajo el mismo Artículo 47 de Ley Federal del Trabajo. Esto
            implica que terminar la relación por reestructura de personal, por la situación económica de la empresa, o
            cualquier otra razón no especificada en la ley, es un despido injustificado.</p>
        <p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"
                class="ccl-link-13 dorada"><strong><small>Artículo 47 de la Ley Federal del Trabajo</small></strong></a>
        </p>
        <hr>

        <h4 class="h4 dorada my-3">
            Derechos irrenunciables y los negociables

        </h4>
        <p>Cuando una persona deja de laborar por renuncia, despido o&nbsp;voluntariamente, cuenta con prestaciones a su
            favor que son irrenunciables y deben pagarse al 100%, las cuales son proporcionales al tiempo que laboró
            y&nbsp;corresponden al último año&nbsp;trabajado,&nbsp;siempre que no se hayan pagado o disfrutado
            previamente</p>
        <p style="text-align: justify;">Ahora bien, cuando se trata de un despido injustificado la persona trabajadora
            tiene derecho de optar por su reinstalación o pedir que la indemnicen conforme a la ley. En los casos en los
            que el patrón señale que la persona trabajadora no fue despedida, sino que&nbsp;renunció voluntariamente o
            bien reconoce que sí existió el despido, pero que este fue justificado (lo cual está dispuesto a probar en
            juicio) no existe la obligación de indemnizar al trabajador.</p>
        <p style="text-align: justify;"><strong>"Recuerda"</strong> que las personas conciliadoras actuarán como
            facilitadoras en la comunicación entre trabajadores y empleadores, con el objetivo de alcanzar un acuerdo
            justo y equilibrado, siempre en apego a lo establecido por la ley, y así evitar la necesidad de recurrir a
            un juicio.</p>
        <p style="text-align: justify;">Esta distinción ayuda a guiar las expectativas de la persona trabajadora y la
            empleadora durante la conciliación, asegurando que se cumplan los derechos irrenunciables del trabajador,
            mientras se busca una negociación de las demás compensaciones. Generalmente, ambas partes prefieren evitar
            un juicio debido al tiempo, costo, desgaste e incertidumbre que este conlleva. Por ello, es común que las
            partes negocien para llegar a un acuerdo que sea mutuamente <strong>beneficioso</strong>.</p>
        <hr>

        <h4 class="h4 dorada my-3">
            Aguinaldo:

        </h4>
        <p style="text-align: justify;">Todas las personas trabajadoras tienen derecho a recibir una gratificación
            anual que se conoce como aguinaldo y debe pagarse antes del 20 de diciembre. El monto debe ser, como mínimo,
            equivalente a 15 días de salario y para su cálculo se considera el salario base o lo que ordinariamente se
            percibe por día laborado. Aquellas personas trabajadoras que no han cumplido un año de servicio tendrán
            derecho a que se les pague la parte proporcional, conforme al tiempo que hubieren trabajado.</p>
        <p style="text-align: justify;"><a target="_blank"
                href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"
                class="ccl-link-13 dorada"><strong><small>Artículo 87 de la Ley Federal del
                        Trabajo</small></strong></a></p>
        <hr>

        <h4 class="h4 dorada my-3">
            Vacaciones:

        </h4>
        <p style="text-align: justify;">Las personas trabajadoras con más de un año de servicios tienen derecho a
            disfrutar de un período anual de vacaciones pagadas que, en ningún caso, podrá ser inferior a doce días
            laborables y que aumentará en dos días laborables por cada año subsecuente de servicios, hasta llegar a
            veinte.{{ asset('assets/anosdias.png') }}</p>
        <p style="text-align: justify;"><a target="_blank"
                href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"
                class="ccl-link-13 dorada"><strong><u><small>Artículos 76, 77, 78, 79 y 81 de la Ley Federal del
                            Trabajo</small></u></strong></a></p>
    </div>
    <div style="height: 160px"><br></div>
</div>
<div class="watermark-container" style="font-size: 9px; padding: 2px;">
    <div class="watermark">Documento sin validez oficial</div>
    <div class="modal-body">
        <div style="text-align: center;">
            <img src="{{ asset('assets/anosdias.png') }}" style="height: 200px">
        </div>
        <h4 class="h4 dorada my-3">
            Prima Vacacional:
        </h4>
        <p style="text-align: justify;">Durante el periodo de vacaciones el patrón deberá de cubrir el salario
            ordinario percibido por las personas trabajadoras. Asimismo, se le deberá otorgar a las personas
            trabajadoras una prima vacacional no menor al 25 por ciento sobre los salarios que les correspondan
            durante
            el período de descanso.</p>
        <p style="text-align: justify;"><a target="_blank"
                href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"
                class="ccl-link-13 dorada"><strong><small>Artículo 80 de la Ley Federal del
                        Trabajo</small></strong></a></p>
        <hr>

        <h4 class="h4 dorada my-3">
            Indemnización constitucional:

        </h4>
        <p style="text-align: justify;">La Constitución establece que, en caso de despido injustificado, toda
            persona
            trabajadora tiene derecho a ser reinstalado en su puesto o bien, a una indemnización equivalente a tres
            meses de salario integrado, es decir, al salario base diario más el aguinaldo anual, más la prima
            vacacional
            anual.</p>
        <p style="text-align: justify;"><u><small>Artículo 123, apartado A, fracción XXII de la Constitución
                    Política
                    de los Estados Unidos Mexicanos</small></u></p>
        <hr>

        <h4 class="h4 dorada my-3">
            Prima de Antigüedad:

        </h4>
        <p style="text-align: justify;">Es un derecho laboral de las personas trabajadoras consistente en el pago
            del
            importe de doce días de salario por cada año de servicio, con un tope de dos veces el salario mínimo
            aplicable para cada día. La prima de antigüedad se deberá pagar a los trabajadores que se separen de su
            empleo de manera voluntaria, siempre que hayan cumplido, por lo menos, quince años de servicios. También
            tienen derecho a esta prestación aquellos empleados que se separen de su trabajo por causa justificada,
            así
            como los que sean separados de su empleo, independientemente de la circunstancia del despido.</p>
        <p style="text-align: justify;"><a target="_blank"
                href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"
                class="ccl-link-13 dorada"><strong><u><small>Artículo 162 de la Ley Federal del
                            Trabajo</small></u></strong></a></p>
        <hr>

        <h4 class="h4 dorada my-3">
            Propuesta 100%:

        </h4>
        <p>Muestra el monto correspondiente al 100% de las prestaciones ya adquiridas, las cuales no son negociables
            e
            incluyen las partes proporcionales de aguinaldo, vacaciones, prima vacacional, así como la indemnización
            constitucional y la prima de antigüedad.</p>
        <hr>

        <h4 class="h4 dorada my-3">
            Propuesta de negociación:

        </h4>
        <p style="text-align: justify;">Para facilitar la negociación de un acuerdo mutuamente beneficioso, la
            calculadora realiza el ejercicio de liquidación, proporcionando ejemplos de propuestas de solución que
            incluyen del 50 al 100% de los conceptos negociables, tales como indemnización y prima de antigüedad,
            así
            como el total de los derechos irrenunciables.</p>
        <hr>

        <h4 class="h4 dorada my-3">
            Nota Importante:

        </h4>
        <p style="text-align: justify;">Estos cálculos son un estimado basado en condiciones generales establecidas
            en
            la Ley Federal del Trabajo. Cuando una persona trabajadora tiene prestaciones mayores a las de la ley,
            la
            compensación por un despido injustificado puede ser mayor. Recuerda que, en el Centro de Conciliación,
            la
            persona conciliadora asignada a tu solicitud te apoyará para realizar un cálculo preciso para tu caso.
        </p>
    </div>
</div>
