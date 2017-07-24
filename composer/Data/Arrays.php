<?php
// 001 es el impuesto ISR
// 002 es el impuesto IVA
// 003 es el impuesto IEPS
// La mayotia de las ocasiones estos impuestos tienen un valor fijo exeptuando en
// el caso del que iva se declare en una retencion, puede llevar un rango que no pase del 0.16
$arrayTasa = array(
    '001' => array('Tasa' => array(0.350000,0)),
    '002' => array('Tasa' => array(0.160000,0)),
    '003' => array('Tasa' => array(0.265000, 0.300000, 0.530000, 0.500000, 1.600000, 0.304000),
        'Cuota' => array(0.350000, 0.059100, 3, 0.2988))
);

$arrayMoneda = Array
    (
    'AED' => Array
        (
        'descripcion' => "Dirham de EAU",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'AFN' => Array
        (
        'descripcion' => "Afghani",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ALL' => Array
        (
        'descripcion' => "Lek",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'AMD' => Array
        (
        'descripcion' => "Dram armenio",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ANG' => Array
        (
        'descripcion' => "Florín antillano neerlandés",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'AOA' => Array
        (
        'descripcion' => "Kwanza",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ARS' => Array
        (
        'descripcion' => "Peso Argentino",
        'decimales' => 2,
        'porcentaje_variacion' => 0.6297217727
    ),
    'AUD' => Array
        (
        'descripcion' => "Dólar Australiano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.4369012092
    ),
    'AWG' => Array
        (
        'descripcion' => "Aruba Florin",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'AZN' => Array
        (
        'descripcion' => "Azerbaijanian Manat",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BAM' => Array
        (
        'descripcion' => "Convertibles marca",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BBD' => Array
        (
        'descripcion' => "Dólar de Barbados",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BDT' => Array
        (
        'descripcion' => "Taka",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BGN' => Array
        (
        'descripcion' => "Lev búlgaro",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BHD' => Array
        (
        'descripcion' => "Dinar de Bahrein",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BIF' => Array
        (
        'descripcion' => "Burundi Franc",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BMD' => Array
        (
        'descripcion' => "Dólar de Bermudas",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BND' => Array
        (
        'descripcion' => "Dólar de Brunei",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BOB' => Array
        (
        'descripcion' => "Boliviano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3536252588
    ),
    'BOV' => Array
        (
        'descripcion' => "Mvdol",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BRL' => Array
        (
        'descripcion' => "Real brasileño",
        'decimales' => 2,
        'porcentaje_variacion' => 0.509556452
    ),
    'BSD' => Array
        (
        'descripcion' => "Dólar de las Bahamas",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BTN' => Array
        (
        'descripcion' => "Ngultrum",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BWP' => Array
        (
        'descripcion' => "Pula",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BYR' => Array
        (
        'descripcion' => "Rublo bielorruso",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'BZD' => Array
        (
        'descripcion' => "Dólar de Belice",
        'decimales' => 2,
        'porcentaje_variacion' => 0.36297996
    ),
    'CAD' => Array
        (
        'descripcion' => "Dolar Canadiense",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3149439016
    ),
    'CDF' => Array
        (
        'descripcion' => "Franco congoleño",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CHE' => Array
        (
        'descripcion' => "WIR Euro",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CHF' => Array
        (
        'descripcion' => "Franco Suizo",
        'decimales' => 2,
        'porcentaje_variacion' => 0.4946701171
    ),
    'CHW' => Array
        (
        'descripcion' => "Franc WIR",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CLF' => Array
        (
        'descripcion' => "Unidad de Fomento",
        'decimales' => 4,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CLP' => Array
        (
        'descripcion' => "Peso chileno",
        'decimales' => 0,
        'porcentaje_variacion' => 0.4467867795
    ),
    'CNY' => Array
        (
        'descripcion' => "Yuan Renminbi",
        'decimales' => 2,
        'porcentaje_variacion' => 0.277564846
    ),
    'COP' => Array
        (
        'descripcion' => "Peso Colombiano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3322485577
    ),
    'COU' => Array
        (
        'descripcion' => "Unidad de Valor real",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CRC' => Array
        (
        'descripcion' => "Colón costarricense",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3119593198
    ),
    'CUC' => Array
        (
        'descripcion' => "Peso Convertible",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CUP' => Array
        (
        'descripcion' => "Peso Cubano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.36297996
    ),
    'CVE' => Array
        (
        'descripcion' => "Cabo Verde Escudo",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'CZK' => Array
        (
        'descripcion' => "Corona checa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.4163341757
    ),
    'DJF' => Array
        (
        'descripcion' => "Franco de Djibouti",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'DKK' => Array
        (
        'descripcion' => "Corona danesa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3987507282
    ),
    'DOP' => Array
        (
        'descripcion' => "Peso Dominicano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.5080789614
    ),
    'DZD' => Array
        (
        'descripcion' => "Dinar argelino",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3032625981
    ),
    'EGP' => Array
        (
        'descripcion' => "Libra egipcia",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3404922723
    ),
    'ERN' => Array
        (
        'descripcion' => "Nakfa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ETB' => Array
        (
        'descripcion' => "Birr etíope",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'EUR' => Array
        (
        'descripcion' => "Euro",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3967208728
    ),
    'FJD' => Array
        (
        'descripcion' => "Dólar de Fiji",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3546436911
    ),
    'FKP' => Array
        (
        'descripcion' => "Libra malvinense",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GBP' => Array
        (
        'descripcion' => "Libra Esterlina",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2879697171
    ),
    'GEL' => Array
        (
        'descripcion' => "Lari",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GHS' => Array
        (
        'descripcion' => "Cedi de Ghana",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GIP' => Array
        (
        'descripcion' => "Libra de Gibraltar",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GMD' => Array
        (
        'descripcion' => "Dalasi",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GNF' => Array
        (
        'descripcion' => "Franco guineano",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'GTQ' => Array
        (
        'descripcion' => "Quetzal",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3565315936
    ),
    'GYD' => Array
        (
        'descripcion' => "Dólar guyanés",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3517850527
    ),
    'HKD' => Array
        (
        'descripcion' => "Dolar De Hong Kong",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3531619653
    ),
    'HNL' => Array
        (
        'descripcion' => "Lempira",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2764192234
    ),
    'HRK' => Array
        (
        'descripcion' => "Kuna",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'HTG' => Array
        (
        'descripcion' => "Gourde",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'HUF' => Array
        (
        'descripcion' => "Florín",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3759057905
    ),
    'IDR' => Array
        (
        'descripcion' => "Rupia",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3477862556
    ),
    'ILS' => Array
        (
        'descripcion' => "Nuevo Shekel Israelí",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3305824562
    ),
    'INR' => Array
        (
        'descripcion' => "Rupia india",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2953978508
    ),
    'IQD' => Array
        (
        'descripcion' => "Dinar iraquí",
        'decimales' => 3,
        'porcentaje_variacion' => 0.5860892653
    ),
    'IRR' => Array
        (
        'descripcion' => "Rial iraní",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ISK' => Array
        (
        'descripcion' => "Corona islandesa",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'JMD' => Array
        (
        'descripcion' => "Dólar Jamaiquino",
        'decimales' => 2,
        'porcentaje_variacion' => 0.281001947
    ),
    'JOD' => Array
        (
        'descripcion' => "Dinar jordano",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3527424452
    ),
    'JPY' => Array
        (
        'descripcion' => "Yen",
        'decimales' => 0,
        'porcentaje_variacion' => 0.5726823038
    ),
    'KES' => Array
        (
        'descripcion' => "Chelín keniano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3021362424
    ),
    'KGS' => Array
        (
        'descripcion' => "Som",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'KHR' => Array
        (
        'descripcion' => "Riel",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'KMF' => Array
        (
        'descripcion' => "Franco Comoro",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'KPW' => Array
        (
        'descripcion' => "Corea del Norte ganó",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'KRW' => Array
        (
        'descripcion' => "Won",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3537676143
    ),
    'KWD' => Array
        (
        'descripcion' => "Dinar kuwaití",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3270606821
    ),
    'KYD' => Array
        (
        'descripcion' => "Dólar de las Islas Caimán",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'KZT' => Array
        (
        'descripcion' => "Tenge",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LAK' => Array
        (
        'descripcion' => "Kip",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LBP' => Array
        (
        'descripcion' => "Libra libanesa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LKR' => Array
        (
        'descripcion' => "Rupia de Sri Lanka",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LRD' => Array
        (
        'descripcion' => "Dólar liberiano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LSL' => Array
        (
        'descripcion' => "Loti",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'LYD' => Array
        (
        'descripcion' => "Dinar libio",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MAD' => Array
        (
        'descripcion' => "Dirham marroquí",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3588110923
    ),
    'MDL' => Array
        (
        'descripcion' => "Leu moldavo",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MGA' => Array
        (
        'descripcion' => "Ariary malgache",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MKD' => Array
        (
        'descripcion' => "Denar",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MMK' => Array
        (
        'descripcion' => "Kyat",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MNT' => Array
        (
        'descripcion' => "Tugrik",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MOP' => Array
        (
        'descripcion' => "Pataca",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MRO' => Array
        (
        'descripcion' => "Ouguiya",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MUR' => Array
        (
        'descripcion' => "Rupia de Mauricio",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MVR' => Array
        (
        'descripcion' => "Rupia",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MWK' => Array
        (
        'descripcion' => "Kwacha",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MXN' => Array
        (
        'descripcion' => "Peso Mexicano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MXV' => Array
        (
        'descripcion' => "México Unidad de Inversión (UDI),",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'MYR' => Array
        (
        'descripcion' => "Ringgit malayo",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2669740855
    ),
    'MZN' => Array
        (
        'descripcion' => "Mozambique Metical",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'NAD' => Array
        (
        'descripcion' => "Dólar de Namibia",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'NGN' => Array
        (
        'descripcion' => "Naira",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3097941399
    ),
    'NIO' => Array
        (
        'descripcion' => "Córdoba Oro",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'NOK' => Array
        (
        'descripcion' => "Corona noruega",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3394569275
    ),
    'NPR' => Array
        (
        'descripcion' => "Rupia nepalí",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'NZD' => Array
        (
        'descripcion' => "Dólar de Nueva Zelanda",
        'decimales' => 2,
        'porcentaje_variacion' => 0.4476414934
    ),
    'OMR' => Array
        (
        'descripcion' => "Rial omaní",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3527424452
    ),
    'PAB' => Array
        (
        'descripcion' => "Balboa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'PEN' => Array
        (
        'descripcion' => "Nuevo Sol",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2888849739
    ),
    'PGK' => Array
        (
        'descripcion' => "Kina",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'PHP' => Array
        (
        'descripcion' => "Peso filipino",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2959771402
    ),
    'PKR' => Array
        (
        'descripcion' => "Rupia de Pakistán",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'PLN' => Array
        (
        'descripcion' => "Zloty",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3864927467
    ),
    'PYG' => Array
        (
        'descripcion' => "Guaraní",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3657899782
    ),
    'QAR' => Array
        (
        'descripcion' => "Qatar Rial",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'RON' => Array
        (
        'descripcion' => "Leu rumano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3588107558
    ),
    'RSD' => Array
        (
        'descripcion' => "Dinar serbio",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'RUB' => Array
        (
        'descripcion' => "Rublo ruso",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3518959199
    ),
    'RWF' => Array
        (
        'descripcion' => "Franco ruandés",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SAR' => Array
        (
        'descripcion' => "Riyal saudí",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3517276347
    ),
    'SBD' => Array
        (
        'descripcion' => "Dólar de las Islas Salomón",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SCR' => Array
        (
        'descripcion' => "Rupia de Seychelles",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SDG' => Array
        (
        'descripcion' => "Libra sudanesa",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SEK' => Array
        (
        'descripcion' => "Corona sueca",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3843119669
    ),
    'SGD' => Array
        (
        'descripcion' => "Dolar De Singapur",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3539315901
    ),
    'SHP' => Array
        (
        'descripcion' => "Libra de Santa Helena",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SLL' => Array
        (
        'descripcion' => "Leona",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SOS' => Array
        (
        'descripcion' => "Chelín somalí",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SRD' => Array
        (
        'descripcion' => "Dólar de Suriname",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SSP' => Array
        (
        'descripcion' => "Libra sudanesa Sur",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'STD' => Array
        (
        'descripcion' => "Dobra",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SVC' => Array
        (
        'descripcion' => "Colon El Salvador",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SYP' => Array
        (
        'descripcion' => "Libra Siria",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'SZL' => Array
        (
        'descripcion' => "Lilangeni",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'THB' => Array
        (
        'descripcion' => "Baht",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3717992668
    ),
    'TJS' => Array
        (
        'descripcion' => "Somoni",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'TMT' => Array
        (
        'descripcion' => "Turkmenistán nuevo manat",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'TND' => Array
        (
        'descripcion' => "Dinar tunecino",
        'decimales' => 3,
        'porcentaje_variacion' => 0.3527424452
    ),
    'TOP' => Array
        (
        'descripcion' => "Paanga",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'TRY' => Array
        (
        'descripcion' => "Lira turca",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3533886709
    ),
    'TTD' => Array
        (
        'descripcion' => "Dólar de Trinidad y Tobago",
        'decimales' => 2,
        'porcentaje_variacion' => 0.2852926919
    ),
    'TWD' => Array
        (
        'descripcion' => "Nuevo dólar de Taiwán",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3008702902
    ),
    'TZS' => Array
        (
        'descripcion' => "Shilling tanzano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'UAH' => Array
        (
        'descripcion' => "Hryvnia",
        'decimales' => 2,
        'porcentaje_variacion' => 0.5083874057
    ),
    'UGX' => Array
        (
        'descripcion' => "Shilling de Uganda",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'USD' => Array
        (
        'descripcion' => "Dolar americano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'USN' => Array
        (
        'descripcion' => "Dólar estadounidense (día siguiente),",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'UYI' => Array
        (
        'descripcion' => "Peso Uruguay en Unidades Indexadas (URUIURUI),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'UYU' => Array
        (
        'descripcion' => "Peso Uruguayo",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3178714973
    ),
    'UZS' => Array
        (
        'descripcion' => "Uzbekistán Sum",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'VEF' => Array
        (
        'descripcion' => "Bolívar",
        'decimales' => 2,
        'porcentaje_variacion' => 1.5277121009
    ),
    'VND' => Array
        (
        'descripcion' => "Dong",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3006176082
    ),
    'VUV' => Array
        (
        'descripcion' => "Vatu",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'WST' => Array
        (
        'descripcion' => "Tala",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XAF' => Array
        (
        'descripcion' => "Franco CFA BEAC",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XAG' => Array
        (
        'descripcion' => "Plata",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XAU' => Array
        (
        'descripcion' => "Oro",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XBA' => Array
        (
        'descripcion' => "Unidad de Mercados de Bonos Unidad Europea Composite (EURCO),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XBB' => Array
        (
        'descripcion' => "Unidad Monetaria de Bonos de Mercados Unidad Europea (UEM-6),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XBC' => Array
        (
        'descripcion' => "Mercados de Bonos Unidad Europea unidad de cuenta a 9 (UCE-9),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XBD' => Array
        (
        'descripcion' => "Mercados de Bonos Unidad Europea unidad de cuenta a 17 (UCE-17),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XCD' => Array
        (
        'descripcion' => "Dólar del Caribe Oriental",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XDR' => Array
        (
        'descripcion' => "DEG (Derechos Especiales de Giro),",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3681341959
    ),
    'XOF' => Array
        (
        'descripcion' => "Franco CFA BCEAO",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XPD' => Array
        (
        'descripcion' => "Paladio",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XPF' => Array
        (
        'descripcion' => "Franco CFP",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XPT' => Array
        (
        'descripcion' => "Platino",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XSU' => Array
        (
        'descripcion' => "Sucre",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XTS' => Array
        (
        'descripcion' => "Códigos reservados específicamente para propósitos de prueba",
        'decimales' => 0,
        'porcentaje_variacion' => 0
    ),
    'XUA' => Array
        (
        'descripcion' => "Unidad ADB de Cuenta",
        'decimales' => 0,
        'porcentaje_variacion' => 0.3527424452
    ),
    'XXX' => Array
        (
        'descripcion' => "Los códigos asignados para las transacciones en que intervenga ninguna moneda",
        'decimales' => 0,
        'porcentaje_variacion' => 0
    ),
    'YER' => Array
        (
        'descripcion' => "Rial yemení",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ZAR' => Array
        (
        'descripcion' => "Rand",
        'decimales' => 2,
        'porcentaje_variacion' => 0.540140951
    ),
    'ZMW' => Array
        (
        'descripcion' => "Kwacha zambiano",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    ),
    'ZWL' => Array
        (
        'descripcion' => "Zimbabwe Dólar",
        'decimales' => 2,
        'porcentaje_variacion' => 0.3527424452
    )
);
?>