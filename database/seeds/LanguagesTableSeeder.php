<?php
use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 'aa',
                'value' => 'Afar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            1 => 
            array (
                'id' => 'ab',
                'value' => 'Abkhazian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            2 => 
            array (
                'id' => 'ace',
                'value' => 'Achinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            3 => 
            array (
                'id' => 'ach',
                'value' => 'Acoli',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            4 => 
            array (
                'id' => 'ada',
                'value' => 'Adangme',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            5 => 
            array (
                'id' => 'ady',
                'value' => 'Adyghe',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            6 => 
            array (
                'id' => 'ae',
                'value' => 'Avestan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            7 => 
            array (
                'id' => 'aeb',
                'value' => 'Tunisian Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            8 => 
            array (
                'id' => 'af',
                'value' => 'Afrikaans',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            9 => 
            array (
                'id' => 'afh',
                'value' => 'Afrihili',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            10 => 
            array (
                'id' => 'agq',
                'value' => 'Aghem',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            11 => 
            array (
                'id' => 'ain',
                'value' => 'Ainu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            12 => 
            array (
                'id' => 'ak',
                'value' => 'Akan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            13 => 
            array (
                'id' => 'akk',
                'value' => 'Akkadian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            14 => 
            array (
                'id' => 'akz',
                'value' => 'Alabama',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            15 => 
            array (
                'id' => 'ale',
                'value' => 'Aleut',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            16 => 
            array (
                'id' => 'aln',
                'value' => 'Gheg Albanian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            17 => 
            array (
                'id' => 'alt',
                'value' => 'Southern Altai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            18 => 
            array (
                'id' => 'am',
                'value' => 'Amharic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            19 => 
            array (
                'id' => 'an',
                'value' => 'Aragonese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            20 => 
            array (
                'id' => 'ang',
                'value' => 'Old English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            21 => 
            array (
                'id' => 'anp',
                'value' => 'Angika',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            22 => 
            array (
                'id' => 'ar',
                'value' => 'Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            23 => 
            array (
                'id' => 'ar_001',
                'value' => 'Modern Standard Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            24 => 
            array (
                'id' => 'arc',
                'value' => 'Aramaic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            25 => 
            array (
                'id' => 'arn',
                'value' => 'Mapuche',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            26 => 
            array (
                'id' => 'aro',
                'value' => 'Araona',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            27 => 
            array (
                'id' => 'arp',
                'value' => 'Arapaho',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            28 => 
            array (
                'id' => 'arq',
                'value' => 'Algerian Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            29 => 
            array (
                'id' => 'arw',
                'value' => 'Arawak',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            30 => 
            array (
                'id' => 'ary',
                'value' => 'Moroccan Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            31 => 
            array (
                'id' => 'arz',
                'value' => 'Egyptian Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            32 => 
            array (
                'id' => 'as',
                'value' => 'Assamese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            33 => 
            array (
                'id' => 'asa',
                'value' => 'Asu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            34 => 
            array (
                'id' => 'ase',
                'value' => 'American Sign Language',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            35 => 
            array (
                'id' => 'ast',
                'value' => 'Asturian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            36 => 
            array (
                'id' => 'av',
                'value' => 'Avaric',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            37 => 
            array (
                'id' => 'avk',
                'value' => 'Kotava',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            38 => 
            array (
                'id' => 'awa',
                'value' => 'Awadhi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            39 => 
            array (
                'id' => 'ay',
                'value' => 'Aymara',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            40 => 
            array (
                'id' => 'az',
                'value' => 'Azerbaijani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            41 => 
            array (
                'id' => 'azb',
                'value' => 'South Azerbaijani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            42 => 
            array (
                'id' => 'ba',
                'value' => 'Bashkir',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            43 => 
            array (
                'id' => 'bal',
                'value' => 'Baluchi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            44 => 
            array (
                'id' => 'ban',
                'value' => 'Balinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            45 => 
            array (
                'id' => 'bar',
                'value' => 'Bavarian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            46 => 
            array (
                'id' => 'bas',
                'value' => 'Basaa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            47 => 
            array (
                'id' => 'bax',
                'value' => 'Bamun',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            48 => 
            array (
                'id' => 'bbc',
                'value' => 'Batak Toba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            49 => 
            array (
                'id' => 'bbj',
                'value' => 'Ghomala',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            50 => 
            array (
                'id' => 'be',
                'value' => 'Belarusian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            51 => 
            array (
                'id' => 'bej',
                'value' => 'Beja',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            52 => 
            array (
                'id' => 'bem',
                'value' => 'Bemba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            53 => 
            array (
                'id' => 'bew',
                'value' => 'Betawi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            54 => 
            array (
                'id' => 'bez',
                'value' => 'Bena',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            55 => 
            array (
                'id' => 'bfd',
                'value' => 'Bafut',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            56 => 
            array (
                'id' => 'bfq',
                'value' => 'Badaga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            57 => 
            array (
                'id' => 'bg',
                'value' => 'Bulgarian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            58 => 
            array (
                'id' => 'bho',
                'value' => 'Bhojpuri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            59 => 
            array (
                'id' => 'bi',
                'value' => 'Bislama',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            60 => 
            array (
                'id' => 'bik',
                'value' => 'Bikol',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            61 => 
            array (
                'id' => 'bin',
                'value' => 'Bini',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            62 => 
            array (
                'id' => 'bjn',
                'value' => 'Banjar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            63 => 
            array (
                'id' => 'bkm',
                'value' => 'Kom',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            64 => 
            array (
                'id' => 'bla',
                'value' => 'Siksika',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            65 => 
            array (
                'id' => 'bm',
                'value' => 'Bambara',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            66 => 
            array (
                'id' => 'bn',
                'value' => 'Bengali',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            67 => 
            array (
                'id' => 'bo',
                'value' => 'Tibetan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            68 => 
            array (
                'id' => 'bpy',
                'value' => 'Bishnupriya',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            69 => 
            array (
                'id' => 'bqi',
                'value' => 'Bakhtiari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            70 => 
            array (
                'id' => 'br',
                'value' => 'Breton',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            71 => 
            array (
                'id' => 'bra',
                'value' => 'Braj',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            72 => 
            array (
                'id' => 'brh',
                'value' => 'Brahui',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            73 => 
            array (
                'id' => 'brx',
                'value' => 'Bodo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            74 => 
            array (
                'id' => 'bs',
                'value' => 'Bosnian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            75 => 
            array (
                'id' => 'bss',
                'value' => 'Akoose',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            76 => 
            array (
                'id' => 'bua',
                'value' => 'Buriat',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            77 => 
            array (
                'id' => 'bug',
                'value' => 'Buginese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            78 => 
            array (
                'id' => 'bum',
                'value' => 'Bulu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            79 => 
            array (
                'id' => 'byn',
                'value' => 'Blin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            80 => 
            array (
                'id' => 'byv',
                'value' => 'Medumba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            81 => 
            array (
                'id' => 'ca',
                'value' => 'Catalan',
                'is_default' => 0,
                'order' => 2,
                'is_active' => 1,
            ),
            82 => 
            array (
                'id' => 'cad',
                'value' => 'Caddo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            83 => 
            array (
                'id' => 'car',
                'value' => 'Carib',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            84 => 
            array (
                'id' => 'cay',
                'value' => 'Cayuga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            85 => 
            array (
                'id' => 'cch',
                'value' => 'Atsam',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            86 => 
            array (
                'id' => 'ce',
                'value' => 'Chechen',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            87 => 
            array (
                'id' => 'ceb',
                'value' => 'Cebuano',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            88 => 
            array (
                'id' => 'cgg',
                'value' => 'Chiga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            89 => 
            array (
                'id' => 'ch',
                'value' => 'Chamorro',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            90 => 
            array (
                'id' => 'chb',
                'value' => 'Chibcha',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            91 => 
            array (
                'id' => 'chg',
                'value' => 'Chagatai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            92 => 
            array (
                'id' => 'chk',
                'value' => 'Chuukese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            93 => 
            array (
                'id' => 'chm',
                'value' => 'Mari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            94 => 
            array (
                'id' => 'chn',
                'value' => 'Chinook Jargon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            95 => 
            array (
                'id' => 'cho',
                'value' => 'Choctaw',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            96 => 
            array (
                'id' => 'chp',
                'value' => 'Chipewyan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            97 => 
            array (
                'id' => 'chr',
                'value' => 'Cherokee',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            98 => 
            array (
                'id' => 'chy',
                'value' => 'Cheyenne',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            99 => 
            array (
                'id' => 'ckb',
                'value' => 'Central Kurdish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            100 => 
            array (
                'id' => 'co',
                'value' => 'Corsican',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            101 => 
            array (
                'id' => 'cop',
                'value' => 'Coptic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            102 => 
            array (
                'id' => 'cps',
                'value' => 'Capiznon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            103 => 
            array (
                'id' => 'cr',
                'value' => 'Cree',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            104 => 
            array (
                'id' => 'crh',
                'value' => 'Crimean Turkish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            105 => 
            array (
                'id' => 'cs',
                'value' => 'Czech',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            106 => 
            array (
                'id' => 'csb',
                'value' => 'Kashubian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            107 => 
            array (
                'id' => 'cu',
                'value' => 'Church Slavic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            108 => 
            array (
                'id' => 'cv',
                'value' => 'Chuvash',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            109 => 
            array (
                'id' => 'cy',
                'value' => 'Welsh',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            110 => 
            array (
                'id' => 'da',
                'value' => 'Danish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            111 => 
            array (
                'id' => 'dak',
                'value' => 'Dakota',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            112 => 
            array (
                'id' => 'dar',
                'value' => 'Dargwa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            113 => 
            array (
                'id' => 'dav',
                'value' => 'Taita',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            114 => 
            array (
                'id' => 'de',
                'value' => 'German',
                'is_default' => 0,
                'order' => 3,
                'is_active' => 1,
            ),
            115 => 
            array (
                'id' => 'de_AT',
                'value' => 'Austrian German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            116 => 
            array (
                'id' => 'de_CH',
                'value' => 'Swiss High German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            117 => 
            array (
                'id' => 'del',
                'value' => 'Delaware',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            118 => 
            array (
                'id' => 'den',
                'value' => 'Slave',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            119 => 
            array (
                'id' => 'dgr',
                'value' => 'Dogrib',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            120 => 
            array (
                'id' => 'din',
                'value' => 'Dinka',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            121 => 
            array (
                'id' => 'dje',
                'value' => 'Zarma',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            122 => 
            array (
                'id' => 'doi',
                'value' => 'Dogri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            123 => 
            array (
                'id' => 'dsb',
                'value' => 'Lower Sorbian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            124 => 
            array (
                'id' => 'dtp',
                'value' => 'Central Dusun',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            125 => 
            array (
                'id' => 'dua',
                'value' => 'Duala',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            126 => 
            array (
                'id' => 'dum',
                'value' => 'Middle Dutch',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            127 => 
            array (
                'id' => 'dv',
                'value' => 'Divehi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            128 => 
            array (
                'id' => 'dyo',
                'value' => 'Jola-Fonyi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            129 => 
            array (
                'id' => 'dyu',
                'value' => 'Dyula',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            130 => 
            array (
                'id' => 'dz',
                'value' => 'Dzongkha',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            131 => 
            array (
                'id' => 'dzg',
                'value' => 'Dazaga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            132 => 
            array (
                'id' => 'ebu',
                'value' => 'Embu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            133 => 
            array (
                'id' => 'ee',
                'value' => 'Ewe',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            134 => 
            array (
                'id' => 'efi',
                'value' => 'Efik',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            135 => 
            array (
                'id' => 'egl',
                'value' => 'Emilian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            136 => 
            array (
                'id' => 'egy',
                'value' => 'Ancient Egyptian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            137 => 
            array (
                'id' => 'eka',
                'value' => 'Ekajuk',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            138 => 
            array (
                'id' => 'el',
                'value' => 'Greek',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            139 => 
            array (
                'id' => 'elx',
                'value' => 'Elamite',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            140 => 
            array (
                'id' => 'en',
                'value' => 'English',
                'is_default' => 1,
                'order' => 1,
                'is_active' => 1,
            ),
            141 => 
            array (
                'id' => 'en_AU',
                'value' => 'Australian English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            142 => 
            array (
                'id' => 'en_CA',
                'value' => 'Canadian English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            143 => 
            array (
                'id' => 'en_GB',
                'value' => 'British English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            144 => 
            array (
                'id' => 'en_US',
                'value' => 'American English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            145 => 
            array (
                'id' => 'enm',
                'value' => 'Middle English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            146 => 
            array (
                'id' => 'eo',
                'value' => 'Esperanto',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            147 => 
            array (
                'id' => 'es',
                'value' => 'Spanish',
                'is_default' => 0,
                'order' => 4,
                'is_active' => 1,
            ),
            148 => 
            array (
                'id' => 'es_419',
                'value' => 'Latin American Spanish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            149 => 
            array (
                'id' => 'es_ES',
                'value' => 'European Spanish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            150 => 
            array (
                'id' => 'es_MX',
                'value' => 'Mexican Spanish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            151 => 
            array (
                'id' => 'esu',
                'value' => 'Central Yupik',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            152 => 
            array (
                'id' => 'et',
                'value' => 'Estonian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            153 => 
            array (
                'id' => 'eu',
                'value' => 'Basque',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            154 => 
            array (
                'id' => 'ewo',
                'value' => 'Ewondo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            155 => 
            array (
                'id' => 'ext',
                'value' => 'Extremaduran',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            156 => 
            array (
                'id' => 'fa',
                'value' => 'Persian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            157 => 
            array (
                'id' => 'fan',
                'value' => 'Fang',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            158 => 
            array (
                'id' => 'fat',
                'value' => 'Fanti',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            159 => 
            array (
                'id' => 'ff',
                'value' => 'Fulah',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            160 => 
            array (
                'id' => 'fi',
                'value' => 'Finnish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            161 => 
            array (
                'id' => 'fil',
                'value' => 'Filipino',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            162 => 
            array (
                'id' => 'fit',
                'value' => 'Tornedalen Finnish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            163 => 
            array (
                'id' => 'fj',
                'value' => 'Fijian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            164 => 
            array (
                'id' => 'fo',
                'value' => 'Faroese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            165 => 
            array (
                'id' => 'fon',
                'value' => 'Fon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            166 => 
            array (
                'id' => 'fr',
                'value' => 'French',
                'is_default' => 0,
                'order' => 5,
                'is_active' => 1,
            ),
            167 => 
            array (
                'id' => 'fr_CA',
                'value' => 'Canadian French',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            168 => 
            array (
                'id' => 'fr_CH',
                'value' => 'Swiss French',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            169 => 
            array (
                'id' => 'frc',
                'value' => 'Cajun French',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            170 => 
            array (
                'id' => 'frm',
                'value' => 'Middle French',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            171 => 
            array (
                'id' => 'fro',
                'value' => 'Old French',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            172 => 
            array (
                'id' => 'frp',
                'value' => 'Arpitan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            173 => 
            array (
                'id' => 'frr',
                'value' => 'Northern Frisian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            174 => 
            array (
                'id' => 'frs',
                'value' => 'Eastern Frisian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            175 => 
            array (
                'id' => 'fur',
                'value' => 'Friulian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            176 => 
            array (
                'id' => 'fy',
                'value' => 'Western Frisian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            177 => 
            array (
                'id' => 'ga',
                'value' => 'Irish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            178 => 
            array (
                'id' => 'gaa',
                'value' => 'Ga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            179 => 
            array (
                'id' => 'gag',
                'value' => 'Gagauz',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            180 => 
            array (
                'id' => 'gan',
                'value' => 'Gan Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            181 => 
            array (
                'id' => 'gay',
                'value' => 'Gayo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            182 => 
            array (
                'id' => 'gba',
                'value' => 'Gbaya',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            183 => 
            array (
                'id' => 'gbz',
                'value' => 'Zoroastrian Dari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            184 => 
            array (
                'id' => 'gd',
                'value' => 'Scottish Gaelic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            185 => 
            array (
                'id' => 'gez',
                'value' => 'Geez',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            186 => 
            array (
                'id' => 'gil',
                'value' => 'Gilbertese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            187 => 
            array (
                'id' => 'gl',
                'value' => 'Galician',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            188 => 
            array (
                'id' => 'glk',
                'value' => 'Gilaki',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            189 => 
            array (
                'id' => 'gmh',
                'value' => 'Middle High German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            190 => 
            array (
                'id' => 'gn',
                'value' => 'Guarani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            191 => 
            array (
                'id' => 'goh',
                'value' => 'Old High German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            192 => 
            array (
                'id' => 'gom',
                'value' => 'Goan Konkani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            193 => 
            array (
                'id' => 'gon',
                'value' => 'Gondi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            194 => 
            array (
                'id' => 'gor',
                'value' => 'Gorontalo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            195 => 
            array (
                'id' => 'got',
                'value' => 'Gothic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            196 => 
            array (
                'id' => 'grb',
                'value' => 'Grebo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            197 => 
            array (
                'id' => 'grc',
                'value' => 'Ancient Greek',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            198 => 
            array (
                'id' => 'gsw',
                'value' => 'Swiss German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            199 => 
            array (
                'id' => 'gu',
                'value' => 'Gujarati',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            200 => 
            array (
                'id' => 'guc',
                'value' => 'Wayuu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            201 => 
            array (
                'id' => 'gur',
                'value' => 'Frafra',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            202 => 
            array (
                'id' => 'guz',
                'value' => 'Gusii',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            203 => 
            array (
                'id' => 'gv',
                'value' => 'Manx',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            204 => 
            array (
                'id' => 'gwi',
                'value' => 'Gwichʼin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            205 => 
            array (
                'id' => 'ha',
                'value' => 'Hausa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            206 => 
            array (
                'id' => 'hai',
                'value' => 'Haida',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            207 => 
            array (
                'id' => 'hak',
                'value' => 'Hakka Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            208 => 
            array (
                'id' => 'haw',
                'value' => 'Hawaiian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            209 => 
            array (
                'id' => 'he',
                'value' => 'Hebrew',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            210 => 
            array (
                'id' => 'hi',
                'value' => 'Hindi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            211 => 
            array (
                'id' => 'hif',
                'value' => 'Fiji Hindi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            212 => 
            array (
                'id' => 'hil',
                'value' => 'Hiligaynon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            213 => 
            array (
                'id' => 'hit',
                'value' => 'Hittite',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            214 => 
            array (
                'id' => 'hmn',
                'value' => 'Hmong',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            215 => 
            array (
                'id' => 'ho',
                'value' => 'Hiri Motu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            216 => 
            array (
                'id' => 'hr',
                'value' => 'Croatian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            217 => 
            array (
                'id' => 'hsb',
                'value' => 'Upper Sorbian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            218 => 
            array (
                'id' => 'hsn',
                'value' => 'Xiang Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            219 => 
            array (
                'id' => 'ht',
                'value' => 'Haitian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            220 => 
            array (
                'id' => 'hu',
                'value' => 'Hungarian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            221 => 
            array (
                'id' => 'hup',
                'value' => 'Hupa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            222 => 
            array (
                'id' => 'hy',
                'value' => 'Armenian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            223 => 
            array (
                'id' => 'hz',
                'value' => 'Herero',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            224 => 
            array (
                'id' => 'ia',
                'value' => 'Interlingua',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            225 => 
            array (
                'id' => 'iba',
                'value' => 'Iban',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            226 => 
            array (
                'id' => 'ibb',
                'value' => 'Ibibio',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            227 => 
            array (
                'id' => 'id',
                'value' => 'Indonesian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            228 => 
            array (
                'id' => 'ie',
                'value' => 'Interlingue',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            229 => 
            array (
                'id' => 'ig',
                'value' => 'Igbo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            230 => 
            array (
                'id' => 'ii',
                'value' => 'Sichuan Yi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            231 => 
            array (
                'id' => 'ik',
                'value' => 'Inupiaq',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            232 => 
            array (
                'id' => 'ilo',
                'value' => 'Iloko',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            233 => 
            array (
                'id' => 'inh',
                'value' => 'Ingush',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            234 => 
            array (
                'id' => 'io',
                'value' => 'Ido',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            235 => 
            array (
                'id' => 'is',
                'value' => 'Icelandic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            236 => 
            array (
                'id' => 'it',
                'value' => 'Italian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            237 => 
            array (
                'id' => 'iu',
                'value' => 'Inuktitut',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            238 => 
            array (
                'id' => 'izh',
                'value' => 'Ingrian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            239 => 
            array (
                'id' => 'ja',
                'value' => 'Japanese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            240 => 
            array (
                'id' => 'jam',
                'value' => 'Jamaican Creole English',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            241 => 
            array (
                'id' => 'jbo',
                'value' => 'Lojban',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            242 => 
            array (
                'id' => 'jgo',
                'value' => 'Ngomba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            243 => 
            array (
                'id' => 'jmc',
                'value' => 'Machame',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            244 => 
            array (
                'id' => 'jpr',
                'value' => 'Judeo-Persian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            245 => 
            array (
                'id' => 'jrb',
                'value' => 'Judeo-Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            246 => 
            array (
                'id' => 'jut',
                'value' => 'Jutish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            247 => 
            array (
                'id' => 'jv',
                'value' => 'Javanese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            248 => 
            array (
                'id' => 'ka',
                'value' => 'Georgian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            249 => 
            array (
                'id' => 'kaa',
                'value' => 'Kara-Kalpak',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            250 => 
            array (
                'id' => 'kab',
                'value' => 'Kabyle',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            251 => 
            array (
                'id' => 'kac',
                'value' => 'Kachin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            252 => 
            array (
                'id' => 'kaj',
                'value' => 'Jju',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            253 => 
            array (
                'id' => 'kam',
                'value' => 'Kamba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            254 => 
            array (
                'id' => 'kaw',
                'value' => 'Kawi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            255 => 
            array (
                'id' => 'kbd',
                'value' => 'Kabardian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            256 => 
            array (
                'id' => 'kbl',
                'value' => 'Kanembu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            257 => 
            array (
                'id' => 'kcg',
                'value' => 'Tyap',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            258 => 
            array (
                'id' => 'kde',
                'value' => 'Makonde',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            259 => 
            array (
                'id' => 'kea',
                'value' => 'Kabuverdianu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            260 => 
            array (
                'id' => 'ken',
                'value' => 'Kenyang',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            261 => 
            array (
                'id' => 'kfo',
                'value' => 'Koro',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            262 => 
            array (
                'id' => 'kg',
                'value' => 'Kongo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            263 => 
            array (
                'id' => 'kgp',
                'value' => 'Kaingang',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            264 => 
            array (
                'id' => 'kha',
                'value' => 'Khasi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            265 => 
            array (
                'id' => 'kho',
                'value' => 'Khotanese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            266 => 
            array (
                'id' => 'khq',
                'value' => 'Koyra Chiini',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            267 => 
            array (
                'id' => 'khw',
                'value' => 'Khowar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            268 => 
            array (
                'id' => 'ki',
                'value' => 'Kikuyu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            269 => 
            array (
                'id' => 'kiu',
                'value' => 'Kirmanjki',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            270 => 
            array (
                'id' => 'kj',
                'value' => 'Kuanyama',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            271 => 
            array (
                'id' => 'kk',
                'value' => 'Kazakh',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            272 => 
            array (
                'id' => 'kkj',
                'value' => 'Kako',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            273 => 
            array (
                'id' => 'kl',
                'value' => 'Kalaallisut',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            274 => 
            array (
                'id' => 'kln',
                'value' => 'Kalenjin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            275 => 
            array (
                'id' => 'km',
                'value' => 'Khmer',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            276 => 
            array (
                'id' => 'kmb',
                'value' => 'Kimbundu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            277 => 
            array (
                'id' => 'kn',
                'value' => 'Kannada',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            278 => 
            array (
                'id' => 'ko',
                'value' => 'Korean',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            279 => 
            array (
                'id' => 'koi',
                'value' => 'Komi-Permyak',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            280 => 
            array (
                'id' => 'kok',
                'value' => 'Konkani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            281 => 
            array (
                'id' => 'kos',
                'value' => 'Kosraean',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            282 => 
            array (
                'id' => 'kpe',
                'value' => 'Kpelle',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            283 => 
            array (
                'id' => 'kr',
                'value' => 'Kanuri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            284 => 
            array (
                'id' => 'krc',
                'value' => 'Karachay-Balkar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            285 => 
            array (
                'id' => 'kri',
                'value' => 'Krio',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            286 => 
            array (
                'id' => 'krj',
                'value' => 'Kinaray-a',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            287 => 
            array (
                'id' => 'krl',
                'value' => 'Karelian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            288 => 
            array (
                'id' => 'kru',
                'value' => 'Kurukh',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            289 => 
            array (
                'id' => 'ks',
                'value' => 'Kashmiri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            290 => 
            array (
                'id' => 'ksb',
                'value' => 'Shambala',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            291 => 
            array (
                'id' => 'ksf',
                'value' => 'Bafia',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            292 => 
            array (
                'id' => 'ksh',
                'value' => 'Colognian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            293 => 
            array (
                'id' => 'ku',
                'value' => 'Kurdish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            294 => 
            array (
                'id' => 'kum',
                'value' => 'Kumyk',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            295 => 
            array (
                'id' => 'kut',
                'value' => 'Kutenai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            296 => 
            array (
                'id' => 'kv',
                'value' => 'Komi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            297 => 
            array (
                'id' => 'kw',
                'value' => 'Cornish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            298 => 
            array (
                'id' => 'ky',
                'value' => 'Kyrgyz',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            299 => 
            array (
                'id' => 'la',
                'value' => 'Latin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            300 => 
            array (
                'id' => 'lad',
                'value' => 'Ladino',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            301 => 
            array (
                'id' => 'lag',
                'value' => 'Langi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            302 => 
            array (
                'id' => 'lah',
                'value' => 'Lahnda',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            303 => 
            array (
                'id' => 'lam',
                'value' => 'Lamba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            304 => 
            array (
                'id' => 'lb',
                'value' => 'Luxembourgish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            305 => 
            array (
                'id' => 'lez',
                'value' => 'Lezghian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            306 => 
            array (
                'id' => 'lfn',
                'value' => 'Lingua Franca Nova',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            307 => 
            array (
                'id' => 'lg',
                'value' => 'Ganda',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            308 => 
            array (
                'id' => 'li',
                'value' => 'Limburgish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            309 => 
            array (
                'id' => 'lij',
                'value' => 'Ligurian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            310 => 
            array (
                'id' => 'liv',
                'value' => 'Livonian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            311 => 
            array (
                'id' => 'lkt',
                'value' => 'Lakota',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            312 => 
            array (
                'id' => 'lmo',
                'value' => 'Lombard',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            313 => 
            array (
                'id' => 'ln',
                'value' => 'Lingala',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            314 => 
            array (
                'id' => 'lo',
                'value' => 'Lao',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            315 => 
            array (
                'id' => 'lol',
                'value' => 'Mongo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            316 => 
            array (
                'id' => 'loz',
                'value' => 'Lozi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            317 => 
            array (
                'id' => 'lt',
                'value' => 'Lithuanian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            318 => 
            array (
                'id' => 'ltg',
                'value' => 'Latgalian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            319 => 
            array (
                'id' => 'lu',
                'value' => 'Luba-Katanga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            320 => 
            array (
                'id' => 'lua',
                'value' => 'Luba-Lulua',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            321 => 
            array (
                'id' => 'lui',
                'value' => 'Luiseno',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            322 => 
            array (
                'id' => 'lun',
                'value' => 'Lunda',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            323 => 
            array (
                'id' => 'luo',
                'value' => 'Luo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            324 => 
            array (
                'id' => 'lus',
                'value' => 'Mizo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            325 => 
            array (
                'id' => 'luy',
                'value' => 'Luyia',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            326 => 
            array (
                'id' => 'lv',
                'value' => 'Latvian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            327 => 
            array (
                'id' => 'lzh',
                'value' => 'Literary Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            328 => 
            array (
                'id' => 'lzz',
                'value' => 'Laz',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            329 => 
            array (
                'id' => 'mad',
                'value' => 'Madurese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            330 => 
            array (
                'id' => 'maf',
                'value' => 'Mafa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            331 => 
            array (
                'id' => 'mag',
                'value' => 'Magahi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            332 => 
            array (
                'id' => 'mai',
                'value' => 'Maithili',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            333 => 
            array (
                'id' => 'mak',
                'value' => 'Makasar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            334 => 
            array (
                'id' => 'man',
                'value' => 'Mandingo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            335 => 
            array (
                'id' => 'mas',
                'value' => 'Masai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            336 => 
            array (
                'id' => 'mde',
                'value' => 'Maba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            337 => 
            array (
                'id' => 'mdf',
                'value' => 'Moksha',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            338 => 
            array (
                'id' => 'mdr',
                'value' => 'Mandar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            339 => 
            array (
                'id' => 'men',
                'value' => 'Mende',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            340 => 
            array (
                'id' => 'mer',
                'value' => 'Meru',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            341 => 
            array (
                'id' => 'mfe',
                'value' => 'Morisyen',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            342 => 
            array (
                'id' => 'mg',
                'value' => 'Malagasy',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            343 => 
            array (
                'id' => 'mga',
                'value' => 'Middle Irish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            344 => 
            array (
                'id' => 'mgh',
                'value' => 'Makhuwa-Meetto',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            345 => 
            array (
                'id' => 'mgo',
                'value' => 'Metaʼ',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            346 => 
            array (
                'id' => 'mh',
                'value' => 'Marshallese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            347 => 
            array (
                'id' => 'mi',
                'value' => 'Maori',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            348 => 
            array (
                'id' => 'mic',
                'value' => 'Micmac',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            349 => 
            array (
                'id' => 'min',
                'value' => 'Minangkabau',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            350 => 
            array (
                'id' => 'mk',
                'value' => 'Macedonian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            351 => 
            array (
                'id' => 'ml',
                'value' => 'Malayalam',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            352 => 
            array (
                'id' => 'mn',
                'value' => 'Mongolian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            353 => 
            array (
                'id' => 'mnc',
                'value' => 'Manchu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            354 => 
            array (
                'id' => 'mni',
                'value' => 'Manipuri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            355 => 
            array (
                'id' => 'moh',
                'value' => 'Mohawk',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            356 => 
            array (
                'id' => 'mos',
                'value' => 'Mossi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            357 => 
            array (
                'id' => 'mr',
                'value' => 'Marathi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            358 => 
            array (
                'id' => 'mrj',
                'value' => 'Western Mari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            359 => 
            array (
                'id' => 'ms',
                'value' => 'Malay',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            360 => 
            array (
                'id' => 'mt',
                'value' => 'Maltese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            361 => 
            array (
                'id' => 'mua',
                'value' => 'Mundang',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            362 => 
            array (
                'id' => 'mul',
                'value' => 'Multiple Languages',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            363 => 
            array (
                'id' => 'mus',
                'value' => 'Creek',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            364 => 
            array (
                'id' => 'mwl',
                'value' => 'Mirandese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            365 => 
            array (
                'id' => 'mwr',
                'value' => 'Marwari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            366 => 
            array (
                'id' => 'mwv',
                'value' => 'Mentawai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            367 => 
            array (
                'id' => 'my',
                'value' => 'Burmese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            368 => 
            array (
                'id' => 'mye',
                'value' => 'Myene',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            369 => 
            array (
                'id' => 'myv',
                'value' => 'Erzya',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            370 => 
            array (
                'id' => 'mzn',
                'value' => 'Mazanderani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            371 => 
            array (
                'id' => 'na',
                'value' => 'Nauru',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            372 => 
            array (
                'id' => 'nan',
                'value' => 'Min Nan Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            373 => 
            array (
                'id' => 'nap',
                'value' => 'Neapolitan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            374 => 
            array (
                'id' => 'naq',
                'value' => 'Nama',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            375 => 
            array (
                'id' => 'nb',
                'value' => 'Norwegian Bokmål',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            376 => 
            array (
                'id' => 'nd',
                'value' => 'North Ndebele',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            377 => 
            array (
                'id' => 'nds',
                'value' => 'Low German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            378 => 
            array (
                'id' => 'ne',
                'value' => 'Nepali',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            379 => 
            array (
                'id' => 'new',
                'value' => 'Newari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            380 => 
            array (
                'id' => 'ng',
                'value' => 'Ndonga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            381 => 
            array (
                'id' => 'nia',
                'value' => 'Nias',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            382 => 
            array (
                'id' => 'niu',
                'value' => 'Niuean',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            383 => 
            array (
                'id' => 'njo',
                'value' => 'Ao Naga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            384 => 
            array (
                'id' => 'nl',
                'value' => 'Dutch',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            385 => 
            array (
                'id' => 'nl_BE',
                'value' => 'Flemish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            386 => 
            array (
                'id' => 'nmg',
                'value' => 'Kwasio',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            387 => 
            array (
                'id' => 'nn',
                'value' => 'Norwegian Nynorsk',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            388 => 
            array (
                'id' => 'nnh',
                'value' => 'Ngiemboon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            389 => 
            array (
                'id' => 'no',
                'value' => 'Norwegian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            390 => 
            array (
                'id' => 'nog',
                'value' => 'Nogai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            391 => 
            array (
                'id' => 'non',
                'value' => 'Old Norse',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            392 => 
            array (
                'id' => 'nov',
                'value' => 'Novial',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            393 => 
            array (
                'id' => 'nqo',
                'value' => 'NʼKo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            394 => 
            array (
                'id' => 'nr',
                'value' => 'South Ndebele',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            395 => 
            array (
                'id' => 'nso',
                'value' => 'Northern Sotho',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            396 => 
            array (
                'id' => 'nus',
                'value' => 'Nuer',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            397 => 
            array (
                'id' => 'nv',
                'value' => 'Navajo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            398 => 
            array (
                'id' => 'nwc',
                'value' => 'Classical Newari',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            399 => 
            array (
                'id' => 'ny',
                'value' => 'Nyanja',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            400 => 
            array (
                'id' => 'nym',
                'value' => 'Nyamwezi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            401 => 
            array (
                'id' => 'nyn',
                'value' => 'Nyankole',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            402 => 
            array (
                'id' => 'nyo',
                'value' => 'Nyoro',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            403 => 
            array (
                'id' => 'nzi',
                'value' => 'Nzima',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            404 => 
            array (
                'id' => 'oc',
                'value' => 'Occitan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            405 => 
            array (
                'id' => 'oj',
                'value' => 'Ojibwa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            406 => 
            array (
                'id' => 'om',
                'value' => 'Oromo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            407 => 
            array (
                'id' => 'or',
                'value' => 'Oriya',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            408 => 
            array (
                'id' => 'os',
                'value' => 'Ossetic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            409 => 
            array (
                'id' => 'osa',
                'value' => 'Osage',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            410 => 
            array (
                'id' => 'ota',
                'value' => 'Ottoman Turkish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            411 => 
            array (
                'id' => 'pa',
                'value' => 'Punjabi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            412 => 
            array (
                'id' => 'pag',
                'value' => 'Pangasinan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            413 => 
            array (
                'id' => 'pal',
                'value' => 'Pahlavi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            414 => 
            array (
                'id' => 'pam',
                'value' => 'Pampanga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            415 => 
            array (
                'id' => 'pap',
                'value' => 'Papiamento',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            416 => 
            array (
                'id' => 'pau',
                'value' => 'Palauan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            417 => 
            array (
                'id' => 'pcd',
                'value' => 'Picard',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            418 => 
            array (
                'id' => 'pdc',
                'value' => 'Pennsylvania German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            419 => 
            array (
                'id' => 'pdt',
                'value' => 'Plautdietsch',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            420 => 
            array (
                'id' => 'peo',
                'value' => 'Old Persian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            421 => 
            array (
                'id' => 'pfl',
                'value' => 'Palatine German',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            422 => 
            array (
                'id' => 'phn',
                'value' => 'Phoenician',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            423 => 
            array (
                'id' => 'pi',
                'value' => 'Pali',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            424 => 
            array (
                'id' => 'pl',
                'value' => 'Polish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            425 => 
            array (
                'id' => 'pms',
                'value' => 'Piedmontese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            426 => 
            array (
                'id' => 'pnt',
                'value' => 'Pontic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            427 => 
            array (
                'id' => 'pon',
                'value' => 'Pohnpeian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            428 => 
            array (
                'id' => 'prg',
                'value' => 'Prussian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            429 => 
            array (
                'id' => 'pro',
                'value' => 'Old Provençal',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            430 => 
            array (
                'id' => 'ps',
                'value' => 'Pashto',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            431 => 
            array (
                'id' => 'pt',
                'value' => 'Portuguese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            432 => 
            array (
                'id' => 'pt_BR',
                'value' => 'Brazilian Portuguese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            433 => 
            array (
                'id' => 'pt_PT',
                'value' => 'European Portuguese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            434 => 
            array (
                'id' => 'qu',
                'value' => 'Quechua',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            435 => 
            array (
                'id' => 'quc',
                'value' => 'Kʼicheʼ',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            436 => 
            array (
                'id' => 'qug',
                'value' => 'Chimborazo Highland Quichua',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            437 => 
            array (
                'id' => 'raj',
                'value' => 'Rajasthani',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            438 => 
            array (
                'id' => 'rap',
                'value' => 'Rapanui',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            439 => 
            array (
                'id' => 'rar',
                'value' => 'Rarotongan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            440 => 
            array (
                'id' => 'rgn',
                'value' => 'Romagnol',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            441 => 
            array (
                'id' => 'rif',
                'value' => 'Riffian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            442 => 
            array (
                'id' => 'rm',
                'value' => 'Romansh',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            443 => 
            array (
                'id' => 'rn',
                'value' => 'Rundi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            444 => 
            array (
                'id' => 'ro',
                'value' => 'Romanian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            445 => 
            array (
                'id' => 'ro_MD',
                'value' => 'Moldavian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            446 => 
            array (
                'id' => 'rof',
                'value' => 'Rombo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            447 => 
            array (
                'id' => 'rom',
                'value' => 'Romany',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            448 => 
            array (
                'id' => 'root',
                'value' => 'Root',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            449 => 
            array (
                'id' => 'rtm',
                'value' => 'Rotuman',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            450 => 
            array (
                'id' => 'ru',
                'value' => 'Russian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            451 => 
            array (
                'id' => 'rue',
                'value' => 'Rusyn',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            452 => 
            array (
                'id' => 'rug',
                'value' => 'Roviana',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            453 => 
            array (
                'id' => 'rup',
                'value' => 'Aromanian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            454 => 
            array (
                'id' => 'rw',
                'value' => 'Kinyarwanda',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            455 => 
            array (
                'id' => 'rwk',
                'value' => 'Rwa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            456 => 
            array (
                'id' => 'sa',
                'value' => 'Sanskrit',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            457 => 
            array (
                'id' => 'sad',
                'value' => 'Sandawe',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            458 => 
            array (
                'id' => 'sah',
                'value' => 'Sakha',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            459 => 
            array (
                'id' => 'sam',
                'value' => 'Samaritan Aramaic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            460 => 
            array (
                'id' => 'saq',
                'value' => 'Samburu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            461 => 
            array (
                'id' => 'sas',
                'value' => 'Sasak',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            462 => 
            array (
                'id' => 'sat',
                'value' => 'Santali',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            463 => 
            array (
                'id' => 'saz',
                'value' => 'Saurashtra',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            464 => 
            array (
                'id' => 'sba',
                'value' => 'Ngambay',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            465 => 
            array (
                'id' => 'sbp',
                'value' => 'Sangu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            466 => 
            array (
                'id' => 'sc',
                'value' => 'Sardinian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            467 => 
            array (
                'id' => 'scn',
                'value' => 'Sicilian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            468 => 
            array (
                'id' => 'sco',
                'value' => 'Scots',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            469 => 
            array (
                'id' => 'sd',
                'value' => 'Sindhi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            470 => 
            array (
                'id' => 'sdc',
                'value' => 'Sassarese Sardinian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            471 => 
            array (
                'id' => 'se',
                'value' => 'Northern Sami',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            472 => 
            array (
                'id' => 'see',
                'value' => 'Seneca',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            473 => 
            array (
                'id' => 'seh',
                'value' => 'Sena',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            474 => 
            array (
                'id' => 'sei',
                'value' => 'Seri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            475 => 
            array (
                'id' => 'sel',
                'value' => 'Selkup',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            476 => 
            array (
                'id' => 'ses',
                'value' => 'Koyraboro Senni',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            477 => 
            array (
                'id' => 'sg',
                'value' => 'Sango',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            478 => 
            array (
                'id' => 'sga',
                'value' => 'Old Irish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            479 => 
            array (
                'id' => 'sgs',
                'value' => 'Samogitian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            480 => 
            array (
                'id' => 'sh',
                'value' => 'Serbo-Croatian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            481 => 
            array (
                'id' => 'shi',
                'value' => 'Tachelhit',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            482 => 
            array (
                'id' => 'shn',
                'value' => 'Shan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            483 => 
            array (
                'id' => 'shu',
                'value' => 'Chadian Arabic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            484 => 
            array (
                'id' => 'si',
                'value' => 'Sinhala',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            485 => 
            array (
                'id' => 'sid',
                'value' => 'Sidamo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            486 => 
            array (
                'id' => 'sk',
                'value' => 'Slovak',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            487 => 
            array (
                'id' => 'sl',
                'value' => 'Slovenian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            488 => 
            array (
                'id' => 'sli',
                'value' => 'Lower Silesian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            489 => 
            array (
                'id' => 'sly',
                'value' => 'Selayar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            490 => 
            array (
                'id' => 'sm',
                'value' => 'Samoan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            491 => 
            array (
                'id' => 'sma',
                'value' => 'Southern Sami',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            492 => 
            array (
                'id' => 'smj',
                'value' => 'Lule Sami',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            493 => 
            array (
                'id' => 'smn',
                'value' => 'Inari Sami',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            494 => 
            array (
                'id' => 'sms',
                'value' => 'Skolt Sami',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            495 => 
            array (
                'id' => 'sn',
                'value' => 'Shona',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            496 => 
            array (
                'id' => 'snk',
                'value' => 'Soninke',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            497 => 
            array (
                'id' => 'so',
                'value' => 'Somali',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            498 => 
            array (
                'id' => 'sog',
                'value' => 'Sogdien',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            499 => 
            array (
                'id' => 'sq',
                'value' => 'Albanian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
        ));
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'id' => 'sr',
                'value' => 'Serbian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            1 => 
            array (
                'id' => 'srn',
                'value' => 'Sranan Tongo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            2 => 
            array (
                'id' => 'srr',
                'value' => 'Serer',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            3 => 
            array (
                'id' => 'ss',
                'value' => 'Swati',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            4 => 
            array (
                'id' => 'ssy',
                'value' => 'Saho',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            5 => 
            array (
                'id' => 'st',
                'value' => 'Southern Sotho',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            6 => 
            array (
                'id' => 'stq',
                'value' => 'Saterland Frisian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            7 => 
            array (
                'id' => 'su',
                'value' => 'Sundanese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            8 => 
            array (
                'id' => 'suk',
                'value' => 'Sukuma',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            9 => 
            array (
                'id' => 'sus',
                'value' => 'Susu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            10 => 
            array (
                'id' => 'sux',
                'value' => 'Sumerian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            11 => 
            array (
                'id' => 'sv',
                'value' => 'Swedish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            12 => 
            array (
                'id' => 'sw',
                'value' => 'Swahili',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            13 => 
            array (
                'id' => 'swb',
                'value' => 'Comorian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            14 => 
            array (
                'id' => 'swc',
                'value' => 'Congo Swahili',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            15 => 
            array (
                'id' => 'syc',
                'value' => 'Classical Syriac',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            16 => 
            array (
                'id' => 'syr',
                'value' => 'Syriac',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            17 => 
            array (
                'id' => 'szl',
                'value' => 'Silesian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            18 => 
            array (
                'id' => 'ta',
                'value' => 'Tamil',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            19 => 
            array (
                'id' => 'tcy',
                'value' => 'Tulu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            20 => 
            array (
                'id' => 'te',
                'value' => 'Telugu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            21 => 
            array (
                'id' => 'tem',
                'value' => 'Timne',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            22 => 
            array (
                'id' => 'teo',
                'value' => 'Teso',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            23 => 
            array (
                'id' => 'ter',
                'value' => 'Tereno',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            24 => 
            array (
                'id' => 'tet',
                'value' => 'Tetum',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            25 => 
            array (
                'id' => 'tg',
                'value' => 'Tajik',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            26 => 
            array (
                'id' => 'th',
                'value' => 'Thai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            27 => 
            array (
                'id' => 'ti',
                'value' => 'Tigrinya',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            28 => 
            array (
                'id' => 'tig',
                'value' => 'Tigre',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            29 => 
            array (
                'id' => 'tiv',
                'value' => 'Tiv',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            30 => 
            array (
                'id' => 'tk',
                'value' => 'Turkmen',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            31 => 
            array (
                'id' => 'tkl',
                'value' => 'Tokelau',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            32 => 
            array (
                'id' => 'tkr',
                'value' => 'Tsakhur',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            33 => 
            array (
                'id' => 'tl',
                'value' => 'Tagalog',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            34 => 
            array (
                'id' => 'tlh',
                'value' => 'Klingon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            35 => 
            array (
                'id' => 'tli',
                'value' => 'Tlingit',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            36 => 
            array (
                'id' => 'tly',
                'value' => 'Talysh',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            37 => 
            array (
                'id' => 'tmh',
                'value' => 'Tamashek',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            38 => 
            array (
                'id' => 'tn',
                'value' => 'Tswana',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            39 => 
            array (
                'id' => 'to',
                'value' => 'Tongan',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            40 => 
            array (
                'id' => 'tog',
                'value' => 'Nyasa Tonga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            41 => 
            array (
                'id' => 'tpi',
                'value' => 'Tok Pisin',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            42 => 
            array (
                'id' => 'tr',
                'value' => 'Turkish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            43 => 
            array (
                'id' => 'tru',
                'value' => 'Turoyo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            44 => 
            array (
                'id' => 'trv',
                'value' => 'Taroko',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            45 => 
            array (
                'id' => 'ts',
                'value' => 'Tsonga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            46 => 
            array (
                'id' => 'tsd',
                'value' => 'Tsakonian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            47 => 
            array (
                'id' => 'tsi',
                'value' => 'Tsimshian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            48 => 
            array (
                'id' => 'tt',
                'value' => 'Tatar',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            49 => 
            array (
                'id' => 'ttt',
                'value' => 'Muslim Tat',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            50 => 
            array (
                'id' => 'tum',
                'value' => 'Tumbuka',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            51 => 
            array (
                'id' => 'tvl',
                'value' => 'Tuvalu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            52 => 
            array (
                'id' => 'tw',
                'value' => 'Twi',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            53 => 
            array (
                'id' => 'twq',
                'value' => 'Tasawaq',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            54 => 
            array (
                'id' => 'ty',
                'value' => 'Tahitian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            55 => 
            array (
                'id' => 'tyv',
                'value' => 'Tuvinian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            56 => 
            array (
                'id' => 'tzm',
                'value' => 'Central Atlas Tamazight',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            57 => 
            array (
                'id' => 'udm',
                'value' => 'Udmurt',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            58 => 
            array (
                'id' => 'ug',
                'value' => 'Uyghur',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            59 => 
            array (
                'id' => 'uga',
                'value' => 'Ugaritic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            60 => 
            array (
                'id' => 'uk',
                'value' => 'Ukrainian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            61 => 
            array (
                'id' => 'umb',
                'value' => 'Umbundu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            62 => 
            array (
                'id' => 'und',
                'value' => 'Unknown Language',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            63 => 
            array (
                'id' => 'ur',
                'value' => 'Urdu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            64 => 
            array (
                'id' => 'uz',
                'value' => 'Uzbek',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            65 => 
            array (
                'id' => 'vai',
                'value' => 'Vai',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            66 => 
            array (
                'id' => 've',
                'value' => 'Venda',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            67 => 
            array (
                'id' => 'vec',
                'value' => 'Venetian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            68 => 
            array (
                'id' => 'vep',
                'value' => 'Veps',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            69 => 
            array (
                'id' => 'vi',
                'value' => 'Vietnamese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            70 => 
            array (
                'id' => 'vls',
                'value' => 'West Flemish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            71 => 
            array (
                'id' => 'vmf',
                'value' => 'Main-Franconian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            72 => 
            array (
                'id' => 'vo',
                'value' => 'Volapük',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            73 => 
            array (
                'id' => 'vot',
                'value' => 'Votic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            74 => 
            array (
                'id' => 'vro',
                'value' => 'Võro',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            75 => 
            array (
                'id' => 'vun',
                'value' => 'Vunjo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            76 => 
            array (
                'id' => 'wa',
                'value' => 'Walloon',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            77 => 
            array (
                'id' => 'wae',
                'value' => 'Walser',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            78 => 
            array (
                'id' => 'wal',
                'value' => 'Wolaytta',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            79 => 
            array (
                'id' => 'war',
                'value' => 'Waray',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            80 => 
            array (
                'id' => 'was',
                'value' => 'Washo',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            81 => 
            array (
                'id' => 'wbp',
                'value' => 'Warlpiri',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            82 => 
            array (
                'id' => 'wo',
                'value' => 'Wolof',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            83 => 
            array (
                'id' => 'wuu',
                'value' => 'Wu Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            84 => 
            array (
                'id' => 'xal',
                'value' => 'Kalmyk',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            85 => 
            array (
                'id' => 'xh',
                'value' => 'Xhosa',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            86 => 
            array (
                'id' => 'xmf',
                'value' => 'Mingrelian',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            87 => 
            array (
                'id' => 'xog',
                'value' => 'Soga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            88 => 
            array (
                'id' => 'yao',
                'value' => 'Yao',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            89 => 
            array (
                'id' => 'yap',
                'value' => 'Yapese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            90 => 
            array (
                'id' => 'yav',
                'value' => 'Yangben',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            91 => 
            array (
                'id' => 'ybb',
                'value' => 'Yemba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            92 => 
            array (
                'id' => 'yi',
                'value' => 'Yiddish',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            93 => 
            array (
                'id' => 'yo',
                'value' => 'Yoruba',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            94 => 
            array (
                'id' => 'yrl',
                'value' => 'Nheengatu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            95 => 
            array (
                'id' => 'yue',
                'value' => 'Cantonese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            96 => 
            array (
                'id' => 'za',
                'value' => 'Zhuang',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            97 => 
            array (
                'id' => 'zap',
                'value' => 'Zapotec',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            98 => 
            array (
                'id' => 'zbl',
                'value' => 'Blissymbols',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            99 => 
            array (
                'id' => 'zea',
                'value' => 'Zeelandic',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            100 => 
            array (
                'id' => 'zen',
                'value' => 'Zenaga',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            101 => 
            array (
                'id' => 'zgh',
                'value' => 'Standard Moroccan Tamazight',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            102 => 
            array (
                'id' => 'zh',
                'value' => 'Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            103 => 
            array (
                'id' => 'zh_Hans',
                'value' => 'Simplified Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            104 => 
            array (
                'id' => 'zh_Hant',
                'value' => 'Traditional Chinese',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            105 => 
            array (
                'id' => 'zu',
                'value' => 'Zulu',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            106 => 
            array (
                'id' => 'zun',
                'value' => 'Zuni',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            107 => 
            array (
                'id' => 'zxx',
                'value' => 'No linguistic content',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
            108 => 
            array (
                'id' => 'zza',
                'value' => 'Zaza',
                'is_default' => 0,
                'order' => 0,
                'is_active' => NULL,
            ),
        ));
        
        
    }
}