<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute debe ser aceptado.',
    'active_url' => ':attribute no es una URL valida.',
    'after' => ':attribute debe ser una fecha superior :date.',
    'after_or_equal' => ':attribute debe ser una fecha superior o igual a :date.',
    'alpha' => ':attribute solo debe contener letras.',
    'alpha_dash' => ':attribute solo debe contener letras, números, diagonales and guindajo.',
    'alpha_num' => ':attribute solo debe contener letras and números.',
    'array' => ':attribute debe ser un arreglo.',
    'before' => ':attribute debe ser una fecha inferior a :date.',
    'before_or_equal' => ':attribute debe ser una fecha inferior o igual a :date.',
    'between' => [
        'numeric' => ':attribute debe ser entre :min y :max.',
        'file' => ':attribute debe ser entre :min y :max kilobytes.',
        'string' => ':attribute debe ser entre :min y :max caracteres.',
        'array' => ':attribute debe ser entre :min y :max items.',
    ],
    'boolean' => ':attribute campo debe ser true o false.',
    'confirmed' => ':attribute confirmación no coincide.',
    'date' => ':attribute no es una fecha valida.',
    'date_equals' => ':attribute debe ser una fecha iguala :date.',
    'date_format' => ':attribute formato no reconocido :format.',
    'different' => ':attribute y :other deben ser diferentes.',
    'digits' => ':attribute debe ser :digits digitos.',
    'digits_between' => 'The :attribute debe estar entre :min y :max digitos.',
    'dimensions' => ':attribute tiene dimensiones invalidas.',
    'distinct' => ':attribute tiene valores duplicados.',
    'email' => ':attribute debe ser una correo valido.',
    'ends_with' => ':attribute debe terminar con uno de los siguientes valores: :values.',
    'exists' => 'Selección :attribute es invalida.',
    'file' => ':attribute debe ser un archivo.',
    'filled' => ':attribute debe tener valores.',
    'gt' => [
        'numeric' => 'The :attribute debe ser mayor que :value.',
        'file' => 'The :attribute debe ser mayor que :value kilobytes.',
        'string' => 'The :attribute debe ser mayor que :value catacteres.',
        'array' => 'The :attribute debe tener más :value items.',
    ],
    'gte' => [
        'numeric' => ':attribute debe ser mayor o igual a :value.',
        'file' => ':attribute debe ser mayor o igual a :value kilobytes.',
        'string' => ':attribute debe ser mayor o igual a :value caracteres.',
        'array' => ':attribute debe tener :value items o más.',
    ],
    'image' => ':attribute debe ser una imagen.',
    'in' => 'Selección:attribute es invalida.',
    'in_array' => ':attribute no existe en :other.',
    'integer' => ':attribute debe ser una imagen.',
    'ip' => ':attribute debe ser una dirección IP valida.',
    'ipv4' => ':attribute debe ser una dirección IPv4 valida.',
    'ipv6' => ':attribute debe ser una dirección IPv6 valida.',
    'json' => ':attribute debe ser una cadena JSON valida.',
    'lt' => [
        'numeric' => ':attribute debe ser menor que :value.',
        'file' => ':attribute debe ser menor que :value kilobytes.',
        'string' => ':attribute debe ser menor que :value caracteres.',
        'array' => 'The :attribute debe tener menos que :value items.',
    ],
    'lte' => [
        'numeric' => ':attribute debe ser menor que o igual :value.',
        'file' => ':attribute debe ser menor que o igual :value kilobytes.',
        'string' => ':attribute debe ser menor que o igual :value caracteres.',
        'array' => 'The :attribute no debe tener mas que :value items.',
    ],
    'max' => [
        'numeric' => ':attribute no debe ser mayor que :max.',
        'file' => ':attribute no debe ser mayor que :max kilobytes.',
        'string' => ':attribute no debe ser mayor que :max caracteres.',
        'array' => ':attribute no tiene que tener más que :max items.',
    ],
    'mimes' => ':attribute debe ser una archivo tipo: :values.',
    'mimetypes' => ':attribute debe ser una archivo tipo: :values.',
    'min' => [
        'numeric' => ':attribute debe tener al menos :min.',
        'file' => ':attribute debe tener al menos :min kilobytes.',
        'string' => ':attribute debe tener al menos :min caracteres.',
        'array' => ':attribute debe tener al menos :min items.',
    ],
    'multiple_of' => ':attribute debe ser un múltiplo de :value.',
    'not_in' => 'selected :attribute es invalido.',
    'not_regex' => ':attribute formato es invalido.',
    'numeric' => ':attribute debe ser un número.',
    'password' => 'password es incorrecto.',
    'present' => ':attribute debe ser necesario.',
    'regex' => ':attribute formato es invalido.',
    'required' => ':attribute es requerido.',
    'required_if' => ':attribute es requerido cuando :other es :value.',
    'required_unless' => ':attribute es requerido a menos que :other este en :values.',
    'required_with' => ':attribute es requerido cuando :values es necesario.',
    'required_with_all' => ':attribute es requerido cuando :values son necesarios.',
    'required_without' => ':attribute es requerido cuando :values no son necesario.',
    'required_without_all' => ':attribute es requerido cuando no hay :values necesarios.',
    'same' => ':attribute y :other debe coincidir.',
    'size' => [
        'numeric' => ':attribute debe ser :size.',
        'file' => ':attribute debe ser :size kilobytes.',
        'string' => ':attribute debe ser :size caracteres.',
        'array' => ':attribute debe contener :size items.',
    ],
    'starts_with' => ':attribute debe iniciar con uno de los siguiente valores: :values.',
    'string' => ':attribute debe ser una string.',
    'timezone' => ':attribute debe ser una zona valida.',
    'unique' => ':attribute ya existe, intente con otro.',
    'uploaded' => ':attribute fallo la carga.',
    'url' => ':attribute format es invalido.',
    'uuid' => ':attribute debe ser una UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
