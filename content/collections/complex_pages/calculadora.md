---
id: e610b859-f73b-49f6-8f28-ab34d44b1a28
blueprint: calculadora
title: 'Calculadora de prestaciones por liquidación'
updated_by: f989c7ba-beca-462e-89e5-66fddce245af
updated_at: 1724709644
template_field: complex_pages/calculator
template: complex_pages/calculadora
politics:
  title_politics: 'Políticas y términos de uso de Calculadora'
  subtitle_politics: 'Descargo de Responsabilidades'
  content_politics: '<p>Al utilizar esta calculadora estoy consciente que las cantidades que arroja son un cálculo aproximado y no una cantidad exacta ni definitiva de las prestaciones a las que tengo derecho en caso de haber sido despedido injustificadamente, pues se trata de un monto que deriva de los datos que yo proporciono, el cual puede variar atendiendo a la información que se aporte en la audiencia conciliatoria. También entiendo que dicha cantidad sólo considera los conceptos de indemnización constitucional, prima de antigüedad, así como vacaciones, aguinaldo proporcionales al último año laborado y prima vacacional.</p>'
  cta_politics: 'Acepto que leí y comprendo los términos de uso'
informative:
  title_informative: 'Ingreso de datos e información'
  subtitle_informative: null
  first_text_informative: 'Para este cálculo, el salario que debes ingresar es tu'
  tooltip_text_informative: 'Dicho salario se utiliza para la cuantificación de vacaciones, prima vacacional, aguinaldo y prima de antigüedad. Por otro lado, la indemnización constitucional se calcula con el salario integrado. El salario integrado se determina sumando al salario base la parte proporcional diaria de prima vacacional y de aguinaldo.'
  content_informative: '<p style="text-align: justify;">A continuación, te preguntaremos cuánto y cada cuándo te pagan. Si cuentas con un recibo de nómina que especifica tu salario base, por favor, ingresa ese monto y periodicidad con la que te lo dan. Si no cuentas con él, contesta cuánto recibes de tu patrón y la periodicidad con la que recibes ese pago (diario, semanal, quincenal o mensual).</p>'
salary_peridocity:
  title_salary: '¿Cuánto te pagan o es tu salario?'
  title_periodicity: '¿Cada cuánto recibes tu salario?'
validation_group:
  title_validation: 'Validación del salario diario'
  subtitle_validations: 'Verifique que el monto del salario diario sea correcto, de lo contrario de clic en "Regresar" para que lo pueda modificar.'
  daily_salary_text: 'Salario diario:'
  text_validation: 'Confirmo que el monto del salario diario es correcto'
border_zone_profesions:
  title_border_zone: '¿Laboraste en un municipio fronterizo norte? Utiliza el mapa para identificar si este es tu caso, y contesta si o no a la pregunta.'
  title_modal_border_zones: 'Mostrar municipios.'
  title_profesion: '¿Desempeñaste un oficio que tiene un salario mínimo especial, distinto al salario mínimo general? Revisa la lista de oficios, si no trabajaste en ninguno de estos, solo deja vacío este campo.'
period_labor:
  title_start_date: '¿Cuándo ingresaste a ese trabajo?'
  title_current_labor_validator: '¿Continúas laborando en ese trabajo?'
  explanation_labor: 'En caso de que no hayas dejado de laborar, la calculadora de prestaciones por liquidación tomará el día de hoy como su día de salida del trabajo para efectos de realizar el estimado.'
  title_date_end: '¿Cuándo dejó de laborar?'
title_modal_explanation: 'Explicación de las compensaciones'
content_field_modal:
  -
    id: lv6373cs
    subtitle: 'Aguinaldo:'
    type: title_subcontent
    enabled: true
  -
    id: lv637au6
    textarea_subcontent: '<p>Todas las personas trabajadoras tienen derecho a recibir una gratificación anual que se conoce como aguinaldo y debe pagarse antes del 20 de diciembre. El monto debe ser, como mínimo, equivalente a 15 días de salario y para su cálculo se considera el salario base o lo que ordinariamente se percibe por día laborado. Aquellas personas trabajadoras que no han cumplido un año de servicio tendrán derecho a que se les pague la parte proporcional, conforme al tiempo que hubieren trabajado.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv637g9t
    subtitle: 'Vacaciones:'
    type: title_subcontent
    enabled: true
  -
    id: lv637p82
    textarea_subcontent: '<p>Las personas trabajadoras con más de un año de servicios tienen derecho a disfrutar de un período anual de vacaciones pagadas que, en ningún caso, podrá ser inferior a doce días laborables y que aumentará en dos días laborables por cada año subsecuente de servicios, hasta llegar a veinte.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv637ug6
    subtitle: 'Prima Vacacional:'
    type: title_subcontent
    enabled: true
  -
    id: lv6380s0
    textarea_subcontent: '<p>Durante el periodo de vacaciones el patrón deberá de cubrir el salario ordinario percibido por las personas trabajadoras. Asimismo, se le deberá otorgar a las personas trabajadoras una prima vacacional no menor al 25 por ciento sobre los salarios que les correspondan durante el período de descanso.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv6388i3
    subtitle: 'Indemnización constitucional:'
    type: title_subcontent
    enabled: true
  -
    id: lv638ewe
    textarea_subcontent: '<p>La Constitución establece que, en caso de despido injustificado, toda persona trabajadora tiene derecho a ser reinstalado en su puesto o bien, a una indemnización equivalente a tres meses de salario integrado, es decir, al salario base diario más el aguinaldo anual, más la prima vacacional anual.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv638kv4
    subtitle: 'Prima Antigüedad:'
    type: title_subcontent
    enabled: true
  -
    id: lv638uui
    textarea_subcontent: '<p>Es un derecho laboral de las personas trabajadoras consistente en el pago del importe de doce días de salario por cada año de servicio, con un tope de dos veces el salario mínimo aplicable para cada día. La prima de antigüedad se deberá pagar a los trabajadores que se separen de su empleo de manera voluntaria, siempre que hayan cumplido, por lo menos, quince años de servicios. También tienen derecho a esta prestación aquellos empleados que se separen de su trabajo por causa justificada, así como los que sean separados de su empleo, independientemente de la circunstancia del despido.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv63912w
    subtitle: 'Propuesta 100% (90 días):'
    type: title_subcontent
    enabled: true
  -
    id: lv6397gs
    textarea_subcontent: '<p>Muestra el monto correspondiente al 100% de las prestaciones ya adquiridas, no negociables, que deben conformar una liquidación: el aguinaldo proporcional, las vacaciones y prima vacacional proporcionales, así como de la indemnización constitucional y la prima de antigüedad. Es importante considerar que, para llegar a un acuerdo conciliatorio sin juicio, generalmente se negocia una cantidad menor.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv639cru
    subtitle: 'Propuesta de 50% (45 Días):'
    type: title_subcontent
    enabled: true
  -
    id: lv639m2t
    textarea_subcontent: '<p>Muestra el monto correspondiente al 50% de las prestaciones ya adquiridas, no negociables, que deben conformar una liquidación: el aguinaldo proporcional, las vacaciones y prima vacacional proporcionales, así como de la indemnización constitucional y la prima de antigüedad. Es importante considerar que, para llegar a un acuerdo conciliatorio sin juicio, generalmente se negocia una cantidad menor al 100%. Aquí se muestra esta cantidad como parámetro para informar la negociación de las personas trabajadora y empleadora.</p>'
    type: text_subcontent
    enabled: true
  -
    id: lv639sam
    subtitle: 'Nota Importante:'
    type: title_subcontent
    enabled: true
  -
    id: lv639xhi
    textarea_subcontent: '<p>Estos cálculos son un estimado basado en condiciones generales establecidas en la Ley Federal del Trabajo. Cuando una persona trabajadora tiene prestaciones mayores a las de la ley, la compensación por un despido injustificado puede ser mayor. Recuerda que, en el Centro de Conciliación, la persona conciliadora asignada a tu solicitud te apoyará para realizar un cálculo preciso para tu caso.</p>'
    type: text_subcontent
    enabled: true
title_modal_border_zone: 'Zonas fronterizas'
content_field_modal_border_zone:
  -
    id: lv63folz
    image: contenidos/infografias/mapa-zona-norte.jpg
    type: image_content
    enabled: true
  -
    id: lv63gei4
    textarea_subcontent: '<p style="text-align: center;"><small>El listado es meramente informativo.</small></p>'
    type: text_subcontent
    enabled: true
content_field_modal_explanation:
  -
    id: m05e5xm6
    subtitle: 'Despido justificado o injustificado'
    type: title_subcontent
    enabled: true
  -
    id: m05e7vgy
    textarea_subcontent: '<p>En caso de un despido justificado, el empleador no está obligado a pagar una indemnización al trabajador, más allá de las prestaciones que ya le correspondan por ley, es decir, las mismas prestaciones &quot;irrenunciables&quot;, que incluyen las proporciones de aguinaldo, vacaciones y prima vacacional del año en curso.</p><p style="text-align: justify;">Mientras que un despido injustificado ocurre cuando el empleador termina la relación laboral sin que exista una justificación bajo el mismo Artículo 47 de Ley Federal del Trabajo. Esto implica que terminar la relación por reestructura de personal, por la situación económica de la empresa, o cualquier otra razón no especificada en la ley, es un despido injustificado.</p><p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"><strong><small>Artículo 47 de la Ley Federal del Trabajo</small></strong></a></p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: m05eb6jy
    subtitle: 'Derechos irrenunciables y los negociables'
    type: title_subcontent
    enabled: true
  -
    id: m05ebjdg
    textarea_subcontent: "<p>Cuando una persona deja de laborar por renuncia, despido o\_voluntariamente, cuenta con prestaciones a su favor que son irrenunciables y deben pagarse al 100%, las cuales son proporcionales al tiempo que laboró y\_corresponden al último año\_trabajado,\_siempre que no se hayan pagado o disfrutado previamente</p><p style=\"text-align: justify;\">Ahora bien, cuando se trata de un despido injustificado la persona trabajadora tiene derecho de optar por su reinstalación o pedir que la indemnicen conforme a la ley. En los casos en los que el patrón señale que la persona trabajadora no fue despedida, sino que\_renunció voluntariamente o bien reconoce que sí existió el despido, pero que este fue justificado (lo cual está dispuesto a probar en juicio) no existe la obligación de indemnizar al trabajador.</p><p style=\"text-align: justify;\"><strong>&quot;Recuerda&quot;</strong> que las personas conciliadoras actuarán como facilitadoras en la comunicación entre trabajadores y empleadores, con el objetivo de alcanzar un acuerdo justo y equilibrado, siempre en apego a lo establecido por la ley, y así evitar la necesidad de recurrir a un juicio.</p><p style=\"text-align: justify;\">Esta distinción ayuda a guiar las expectativas de la persona trabajadora y la empleadora durante la conciliación, asegurando que se cumplan los derechos irrenunciables del trabajador, mientras se busca una negociación de las demás compensaciones. Generalmente, ambas partes prefieren evitar un juicio debido al tiempo, costo, desgaste e incertidumbre que este conlleva. Por ello, es común que las partes negocien para llegar a un acuerdo que sea mutuamente <strong>beneficioso</strong>.</p><hr>"
    type: text_subcontent
    enabled: true
  -
    id: lv63vlja
    subtitle: 'Aguinaldo:'
    type: title_subcontent
    enabled: true
  -
    id: lv63vqxb
    textarea_subcontent: '<p style="text-align: justify;">Todas las personas trabajadoras tienen derecho a recibir una gratificación anual que se conoce como aguinaldo y debe pagarse antes del 20 de diciembre. El monto debe ser, como mínimo, equivalente a 15 días de salario y para su cálculo se considera el salario base o lo que ordinariamente se percibe por día laborado. Aquellas personas trabajadoras que no han cumplido un año de servicio tendrán derecho a que se les pague la parte proporcional, conforme al tiempo que hubieren trabajado.</p><p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"><strong><small>Artículo 87 de la Ley Federal del Trabajo</small></strong></a></p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63vwhy
    subtitle: 'Vacaciones:'
    type: title_subcontent
    enabled: true
  -
    id: lv63w1nm
    textarea_subcontent: '<p style="text-align: justify;">Las personas trabajadoras con más de un año de servicios tienen derecho a disfrutar de un período anual de vacaciones pagadas que, en ningún caso, podrá ser inferior a doce días laborables y que aumentará en dos días laborables por cada año subsecuente de servicios, hasta llegar a veinte.</p><p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"><strong><u><small>Artículos 76, 77, 78, 79 y 81 de la Ley Federal del Trabajo</small></u></strong></a></p>'
    type: text_subcontent
    enabled: true
  -
    id: m05f70yh
    image: anosdias.png
    type: image_content
    enabled: true
  -
    id: lv63w79q
    subtitle: 'Prima Vacacional:'
    type: title_subcontent
    enabled: true
  -
    id: lv63wcrw
    textarea_subcontent: '<p style="text-align: justify;">Durante el periodo de vacaciones el patrón deberá de cubrir el salario ordinario percibido por las personas trabajadoras. Asimismo, se le deberá otorgar a las personas trabajadoras una prima vacacional no menor al 25 por ciento sobre los salarios que les correspondan durante el período de descanso.</p><p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"><strong><small>Artículo 80 de la Ley Federal del Trabajo</small></strong></a></p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63wior
    subtitle: 'Indemnización constitucional:'
    type: title_subcontent
    enabled: true
  -
    id: lv63wqy9
    textarea_subcontent: '<p style="text-align: justify;">La Constitución establece que, en caso de despido injustificado, toda persona trabajadora tiene derecho a ser reinstalado en su puesto o bien, a una indemnización equivalente a tres meses de salario integrado, es decir, al salario base diario más el aguinaldo anual, más la prima vacacional anual.</p><p style="text-align: justify;"><u><small>Artículo 123, apartado A, fracción XXII de la Constitución Política de los Estados Unidos Mexicanos</small></u></p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63wwyt
    subtitle: 'Prima Antigüedad:'
    type: title_subcontent
    enabled: true
  -
    id: lv63x3gi
    textarea_subcontent: '<p style="text-align: justify;">Es un derecho laboral de las personas trabajadoras consistente en el pago del importe de doce días de salario por cada año de servicio, con un tope de dos veces el salario mínimo aplicable para cada día. La prima de antigüedad se deberá pagar a los trabajadores que se separen de su empleo de manera voluntaria, siempre que hayan cumplido, por lo menos, quince años de servicios. También tienen derecho a esta prestación aquellos empleados que se separen de su trabajo por causa justificada, así como los que sean separados de su empleo, independientemente de la circunstancia del despido.</p><p style="text-align: justify;"><a target="_blank" href="https://www.diputados.gob.mx/LeyesBiblio/pdf/LFT.pdf"><strong><u><small>Artículo 162 de la Ley Federal del Trabajo</small></u></strong></a></p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63xauh
    subtitle: 'Propuesta 100%:'
    type: title_subcontent
    enabled: true
  -
    id: lv63xiz0
    textarea_subcontent: '<p>Muestra el monto correspondiente al 100% de las prestaciones ya adquiridas, las cuales no son negociables e incluyen las partes proporcionales de aguinaldo, vacaciones, prima vacacional, así como la indemnización constitucional y la prima de antigüedad.</p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63xq7y
    subtitle: 'Propuesta de negociación:'
    type: title_subcontent
    enabled: true
  -
    id: lv63xxd5
    textarea_subcontent: '<p style="text-align: justify;">Para facilitar la negociación de un acuerdo mutuamente beneficioso, la calculadora realiza el ejercicio de liquidación, proporcionando ejemplos de propuestas de solución que incluyen del 50 al 100% de los conceptos negociables, tales como indemnización y prima de antigüedad, así como el total de los derechos irrenunciables.</p><hr>'
    type: text_subcontent
    enabled: true
  -
    id: lv63y3cs
    subtitle: 'Nota Importante:'
    type: title_subcontent
    enabled: true
  -
    id: lv63yeku
    textarea_subcontent: '<p style="text-align: justify;">Estos cálculos son un estimado basado en condiciones generales establecidas en la Ley Federal del Trabajo. Cuando una persona trabajadora tiene prestaciones mayores a las de la ley, la compensación por un despido injustificado puede ser mayor. Recuerda que, en el Centro de Conciliación, la persona conciliadora asignada a tu solicitud te apoyará para realizar un cálculo preciso para tu caso.</p>'
    type: text_subcontent
    enabled: true
result_content:
  title_details_result: 'Información para el cálculo'
  details_text_content:
    start_date_text: 'Fecha inicio'
    end_date_text: 'Fecha salida'
    years_old_text: 'Años de antigüedad'
    border_zone_text: 'Zona fronteriza norte'
    minimum_salary_text: 'Salario mínimo'
    daily_salary_text: 'Salario diario'
    profesion_text: Profesión
  title_table_result: Resultado
  title_explanation: 'Explicación del cálculo'
  title_download: 'Descargar cálculo'
  title_edit: 'Editar datos'
  title_other_amount: 'Cálculo de otros porcentajes'
  leyend_text:
    -
      type: paragraph
      attrs:
        textAlign: left
      content:
        -
          type: text
          text: 'La "Calculadora para cuantificar la liquidación por despido injustificado" es una herramienta de orientación diseñada para que las personas trabajadoras puedan informarse sobre los conceptos y cálculos que se abordarán en una audiencia de conciliación.'
  title_important_note: 'Nota Importante'
  text_important_note:
    -
      type: paragraph
      attrs:
        textAlign: justify
      content:
        -
          type: text
          text: 'Estos cálculos son un estimado basado en condiciones generales establecidas en la Ley Federal del Trabajo. Cuando una persona trabajadora tiene prestaciones mayores a las de la ley, la compensación por un despido injustificado puede ser mayor. Recuerda que, en el Centro de Conciliación, la persona conciliadora asignada a tu solicitud te apoyará para realizar un cálculo preciso para tu caso.'
---
