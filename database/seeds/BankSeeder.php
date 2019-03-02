<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = [
      [
        'id' => 1,
        'cbn_code' => '044',
        'cbn_code_main' => '044',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'ACCESS BANK PLC'
      ],
      [
        'id' => 2,
        'cbn_code' => '014',
        'cbn_code_main' => '014',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'MAINSTREET BANK PLC'
      ],
      [
        'id' => 3,
        'cbn_code' => '063',
        'cbn_code_main' => '063',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'DIAMOND BANK PLC'
      ],
      [
        'id' => 4,
        'cbn_code' => '082',
        'cbn_code_main' => '082',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'KEYSTONE BANK PLC'
      ],
      [
        'id' => 5,
        'cbn_code' => '070',
        'cbn_code_main' => '070',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'FIDELITY BANK PLC'
      ],
      [
        'id' => 6,
        'cbn_code' => '214',
        'cbn_code_main' => '214',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'FIRST CITY MONUMENT BANK PLC'
      ],
      [
        'id' => 7,
        'cbn_code' => '011',
        'cbn_code_main' => '011',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'FIRST BANK OF NIGERIA PLC'
      ],
      [
        'id' => 8,
        'cbn_code' => '058',
        'cbn_code_main' => '058',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'GUARANTY TRUST BANK PLC'
      ],
      [
        'id' => 9,
        'cbn_code' => '050',
        'cbn_code_main' => '050',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'ECOBANK NIGERIA PLC'
      ],
      [
        'id' => 10,
        'cbn_code' => '076',
        'cbn_code_main' => '076',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'POLARIS BANK PLC'
      ],
      [
        'id' => 11,
        'cbn_code' => '084',
        'cbn_code_main' => '084',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'ENTERPRISE BANK PLC'
      ],
      [
        'id' => 12,
        'cbn_code' => '221',
        'cbn_code_main' => '221',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'STANBIC-IBTC BANK PLC'
      ],
      [
        'id' => 13,
        'cbn_code' => '232',
        'cbn_code_main' => '232',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'STERLING BANK PLC'
      ],
      [
        'id' => 14,
        'cbn_code' => '032',
        'cbn_code_main' => '032',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'UNION BANK OF NIGERIA PLC'
      ],
      [
        'id' => 15,
        'cbn_code' => '033',
        'cbn_code_main' => '033',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'UNITED BANK FOR AFRICA PLC'
      ],
      [
        'id' => 16,
        'cbn_code' => '215',
        'cbn_code_main' => '215',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'UNITY BANK PLC'
      ],
      [
        'id' => 17,
        'cbn_code' => '068',
        'cbn_code_main' => '068',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'STANDARD CHARTERED BANK NIGERIA LTD'
      ],
      [
        'id' => 18,
        'cbn_code' => '035',
        'cbn_code_main' => '035',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'WEMA BANK PLC'
      ],
      [
        'id' => 19,
        'cbn_code' => '057',
        'cbn_code_main' => '057',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'ZENITH BANK PLC'
      ],
      [
        'id' => 20,
        'cbn_code' => '023',
        'cbn_code_main' => '023',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'CITI BANK'
      ],
      [
        'id' => 21,
        'cbn_code' => '030',
        'cbn_code_main' => '030',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'HERITAGE BANK LIMITED'
      ],
      [
        'id' => 22,
        'cbn_code' => '184',
        'cbn_code_main' => '184',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OBELEDU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 23,
        'cbn_code' => '110',
        'cbn_code_main' => '110',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'EKWULOBIA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 24,
        'cbn_code' => '168',
        'cbn_code_main' => '168',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NDIOLU MICRO FINANCE BANK'
      ],
      [
        'id' => 25,
        'cbn_code' => '195',
        'cbn_code_main' => '195',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UFUMA MICRO FINANCE BANK'
      ],
      [
        'id' => 26,
        'cbn_code' => '181',
        'cbn_code_main' => '181',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NKPOR MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 27,
        'cbn_code' => '169',
        'cbn_code_main' => '169',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NDIORA MICRO FINANCE BANK'
      ],
      [
        'id' => 28,
        'cbn_code' => '190',
        'cbn_code_main' => '190',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'PEOPLES MICRO FINANCE BANK'
      ],
      [
        'id' => 29,
        'cbn_code' => '186',
        'cbn_code_main' => '186',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OGIDI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 30,
        'cbn_code' => '136',
        'cbn_code_main' => '136',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OBOSI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 31,
        'cbn_code' => '182',
        'cbn_code_main' => '182',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NTUCA MICRO FINANCE BANK'
      ],
      [
        'id' => 32,
        'cbn_code' => '176',
        'cbn_code_main' => '176',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NIG AGRIC COOP AND DEV BANK'
      ],
      [
        'id' => 33,
        'cbn_code' => '111',
        'cbn_code_main' => '111',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ENUGWU-UKWU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 34,
        'cbn_code' => '139',
        'cbn_code_main' => '139',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UMUNZE MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 35,
        'cbn_code' => '172',
        'cbn_code_main' => '172',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NICE MICRO FINANCE BANK'
      ],
      [
        'id' => 36,
        'cbn_code' => '171',
        'cbn_code_main' => '171',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NIBO MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 37,
        'cbn_code' => '106',
        'cbn_code_main' => '106',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AWKUZU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 38,
        'cbn_code' => '107',
        'cbn_code_main' => '107',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'CASTLE  MICROFINANCE BANK LTD'
      ],
      [
        'id' => 39,
        'cbn_code' => '161',
        'cbn_code_main' => '161',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UKPOR MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 40,
        'cbn_code' => '123',
        'cbn_code_main' => '123',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'IHIALA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 41,
        'cbn_code' => '125',
        'cbn_code_main' => '125',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'MAYFRESH SAVINGS AND LOAN'
      ],
      [
        'id' => 42,
        'cbn_code' => '162',
        'cbn_code_main' => '162',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ULI MICROFINANCE BANK LTD'
      ],
      [
        'id' => 43,
        'cbn_code' => '108',
        'cbn_code_main' => '108',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'DOMINION MICROFINANCE BANK'
      ],
      [
        'id' => 44,
        'cbn_code' => '178',
        'cbn_code_main' => '178',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NIG AGRIC COOP AND DEV BANK'
      ],
      [
        'id' => 45,
        'cbn_code' => '177',
        'cbn_code_main' => '177',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NIG AGRIC COOP AND DEV BANK'
      ],
      [
        'id' => 46,
        'cbn_code' => '192',
        'cbn_code_main' => '192',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UCB MICRO FINANCE BANK'
      ],
      [
        'id' => 47,
        'cbn_code' => '205',
        'cbn_code_main' => '205',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'TOP CLASS MICRO FINANCE BANK'
      ],
      [
        'id' => 48,
        'cbn_code' => '109',
        'cbn_code_main' => '109',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'EJIAMATU MICRO FINANCE BANK'
      ],
      [
        'id' => 49,
        'cbn_code' => '183',
        'cbn_code_main' => '183',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OBA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 50,
        'cbn_code' => '101',
        'cbn_code_main' => '101',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OZUBULU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 51,
        'cbn_code' => '105',
        'cbn_code_main' => '105',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AWKA-ETITI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 52,
        'cbn_code' => '119',
        'cbn_code_main' => '119',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'EZEBO MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 53,
        'cbn_code' => '103',
        'cbn_code_main' => '103',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ALHOCOL MICRO FINANCE BANK'
      ],
      [
        'id' => 54,
        'cbn_code' => '104',
        'cbn_code_main' => '104',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AWKA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 55,
        'cbn_code' => '134',
        'cbn_code_main' => '134',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'IGBOUKWU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 56,
        'cbn_code' => '138',
        'cbn_code_main' => '138',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UGA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 57,
        'cbn_code' => '151',
        'cbn_code_main' => '151',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ISUOFIA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 58,
        'cbn_code' => '154',
        'cbn_code_main' => '154',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NKPOLOGWU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 59,
        'cbn_code' => '142',
        'cbn_code_main' => '142',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ACHINA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 60,
        'cbn_code' => '201',
        'cbn_code_main' => '201',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AKALABOR MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 61,
        'cbn_code' => '144',
        'cbn_code_main' => '144',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AKPO MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 62,
        'cbn_code' => '102',
        'cbn_code_main' => '102',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AKUOMA MICRO FINANCE BANK'
      ],
      [
        'id' => 63,
        'cbn_code' => '187',
        'cbn_code_main' => '187',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OLUCHUKWU MICRO FINANCE BANK'
      ],
      [
        'id' => 64,
        'cbn_code' => '145',
        'cbn_code_main' => '145',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AMESI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 65,
        'cbn_code' => '143',
        'cbn_code_main' => '143',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ADAZI-NNUKWU MICRO FINANCE BANK'
      ],
      [
        'id' => 66,
        'cbn_code' => '121',
        'cbn_code_main' => '121',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ICHI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 67,
        'cbn_code' => '137',
        'cbn_code_main' => '137',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ORAUKWU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 68,
        'cbn_code' => '135',
        'cbn_code_main' => '135',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NNOBI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 69,
        'cbn_code' => '122',
        'cbn_code_main' => '122',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'IDEMILI MICRO FINANCE BANK'
      ],
      [
        'id' => 70,
        'cbn_code' => '155',
        'cbn_code_main' => '155',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NNOKWA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 71,
        'cbn_code' => '132',
        'cbn_code_main' => '132',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ALOR MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 72,
        'cbn_code' => '131',
        'cbn_code_main' => '131',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ADAZI-ENU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 73,
        'cbn_code' => '130',
        'cbn_code_main' => '130',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ADAZI-ANI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 74,
        'cbn_code' => '156',
        'cbn_code_main' => '156',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NRI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 75,
        'cbn_code' => '194',
        'cbn_code_main' => '194',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UDOKA MICRO FINANCE BANK'
      ],
      [
        'id' => 76,
        'cbn_code' => '197',
        'cbn_code_main' => '197',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UMMUNE MICRO FINANCE'
      ],
      [
        'id' => 77,
        'cbn_code' => '141',
        'cbn_code_main' => '141',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ABAGANA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 78,
        'cbn_code' => '193',
        'cbn_code_main' => '193',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UDEZUKA MICRO FINANCE BANK'
      ],
      [
        'id' => 79,
        'cbn_code' => '120',
        'cbn_code_main' => '120',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'FRONTLINE MICRO FINANCE BANK'
      ],
      [
        'id' => 80,
        'cbn_code' => '199',
        'cbn_code_main' => '199',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UTUH MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 81,
        'cbn_code' => '188',
        'cbn_code_main' => '188',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OSUMENYI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 82,
        'cbn_code' => '149',
        'cbn_code_main' => '149',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'EZINIFITE MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 83,
        'cbn_code' => '152',
        'cbn_code_main' => '152',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NANKA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 84,
        'cbn_code' => '158',
        'cbn_code_main' => '158',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OGBU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 85,
        'cbn_code' => '159',
        'cbn_code_main' => '159',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'OKO MICRO FINANCE BANK'
      ],
      [
        'id' => 86,
        'cbn_code' => '146',
        'cbn_code_main' => '146',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AWGBU MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 87,
        'cbn_code' => '166',
        'cbn_code_main' => '166',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NATEX MICRO FINANCE BANK'
      ],
      [
        'id' => 88,
        'cbn_code' => '163',
        'cbn_code_main' => '163',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UMUNYA MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 89,
        'cbn_code' => '180',
        'cbn_code_main' => '180',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'NIG AGRIC COOP AND DEV BANK'
      ],
      [
        'id' => 90,
        'cbn_code' => '140',
        'cbn_code_main' => '140',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'UMUOJI MICRO FINANCE BANK LTD'
      ],
      [
        'id' => 91,
        'cbn_code' => '112',
        'cbn_code_main' => '112',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'EQUINOX MICRO FINANCE BANK'
      ],
      [
        'id' => 92,
        'cbn_code' => '164',
        'cbn_code_main' => '164',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'MODEL MICROFINANCE BANK'
      ],
      [
        'id' => 93,
        'cbn_code' => '175',
        'cbn_code_main' => '175',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'AGULERI MICRO FINANCE BANK'
      ],
      [
        'id' => 94,
        'cbn_code' => '191',
        'cbn_code_main' => '191',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'CASH PAYMENT'
      ],
      [
        'id' => 95,
        'cbn_code' => '202',
        'cbn_code_main' => '202',
        'bank_type' => 'MICRO-FINANCE/AGRIC BANKS',
        'bank_name' => 'ABATETE  MICROFINANCE BANK LTD'
      ],
      [
        'id' => 96,
        'cbn_code' => '301',
        'cbn_code_main' => '301',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'JAIZ BANK PLC'
      ],
      [
        'id' => 97,
        'cbn_code' => '095',
        'cbn_code_main' => '095',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'UNICAL MFB'
      ],
      [
        'id' => 98,
        'cbn_code' => '098',
        'cbn_code_main' => '098',
        'bank_type' => 'COMMERCIAL',
        'bank_name' => 'EKONDO MFB'
      ]
    ];

    DB::table('banks')->insert($data);
  }
}
