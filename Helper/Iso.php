<?php
/**
 * Mondido
 *
 * PHP version 5.6
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */

namespace Mondido\Mondido\Helper;

/**
 * Iso helper
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Robert Lord <robert@codepeak.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class Iso extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Transform ISO code
     *
     * This function will convert a ISO 3166-1 alpha-2 code to ISO 3166-1 alpha-3
     * or vice versa.
     *
     * @param $isoCode
     *
     * @return string
     * @throws \Exception
     */
    public function transform($isoCode)
    {
        if (strlen($isoCode) == 2) {
            return $this->convertFromAlpha2($isoCode);
        } elseif (strlen($isoCode) == 3) {
            return $this->convertFromAlpha3($isoCode);
        }

        throw new \Exception('Given data should either be ISO 3166-1 alpha-2 or alpha-3');
    }

    /**
     * Convert from alpha-2 to alpha-3
     *
     * @param $isoAlpha2
     *
     * @return mixed
     * @throws \Exception
     */
    public function convertFromAlpha2($isoAlpha2)
    {
        // Loop the translate array
        foreach ($this->getTranslateArray() as $iso2 => $iso3) {
            // Return translated string
            if (strtoupper($isoAlpha2) == strtoupper($iso2)) {
                return $iso3;
            }
        }

        throw new \Exception('Unable to find ISO 3166-1 alpha 2 key "' . $isoAlpha2 . '"');
    }

    /**
     * Convert from alpha-3 to alpha-2
     *
     * @param $isoAlpha2
     *
     * @return mixed
     * @throws \Exception
     */
    public function convertFromAlpha3($isoAlpha3)
    {
        // Loop the translate array
        foreach ($this->getTranslateArray() as $iso2 => $iso3) {
            // Return translated string
            if (strtoupper($isoAlpha3) == strtoupper($iso3)) {
                return $iso2;
            }
        }

        throw new \Exception('Unable to find ISO 3166-1 alpha 2 key "' . $isoAlpha2 . '"');
    }

    /**
     * Return translate array for ISO codes
     *
     * Format of array 'ISO-2' => 'ISO-3', // Country
     *
     * @return array
     */
    public function getTranslateArray()
    {
        return [
            'AF' => 'AFG', // Afghanistan
            'AX' => 'ALA', // Åland
            'AL' => 'ALB', // Albania
            'DZ' => 'DZA', // Algeria
            'AS' => 'ASM', // American Samoa
            'AD' => 'AND', // Andorra
            'AO' => 'AGO', // Angola
            'AI' => 'AIA', // Anguilla
            'AQ' => 'ATA', // Antarctica
            'AG' => 'ATG', // Antigua and Barbuda
            'AR' => 'ARG', // Argentina
            'AM' => 'ARM', // Armenia
            'AW' => 'ABW', // Aruba
            'AU' => 'AUS', // Australia
            'AT' => 'AUT', // Austria
            'AZ' => 'AZE', // Azerbaijan
            'BS' => 'BHS', // Bahamas
            'BH' => 'BHR', // Bahrain
            'BD' => 'BGD', // Bangladesh
            'BB' => 'BRB', // Barbados
            'BY' => 'BLR', // Belarus
            'BE' => 'BEL', // Belgium
            'BZ' => 'BLZ', // Belize
            'BJ' => 'BEN', // Benin
            'BM' => 'BMU', // Bermuda
            'BT' => 'BTN', // Bhutan
            'BO' => 'BOL', // Bolivia
            'BQ' => 'BES', // Bonaire, Sint Eustatius and Saba
            'BA' => 'BIH', // Bosnia and Herzegovina
            'BW' => 'BWA', // Botswana
            'BV' => 'BVT', // Bouvet Island
            'BR' => 'BRA', // Brazil
            'IO' => 'IOT', // British Indian Ocean Territory
            'BN' => 'BRN', // Brunei Darussalam
            'BG' => 'BGR', // Bulgaria
            'BF' => 'BFA', // Burkina Faso
            'BI' => 'BDI', // Burundi
            'KH' => 'KHM', // Cambodia
            'CM' => 'CMR', // Cameroon
            'CA' => 'CAN', // Canada
            'CV' => 'CPV', // Cape Verde
            'KY' => 'CYM', // Cayman Islands
            'CF' => 'CAF', // Central African Republic
            'TD' => 'TCD', // Chad
            'CL' => 'CHL', // Chile
            'CN' => 'CHN', // China
            'CX' => 'CXR', // Christmas Island
            'CC' => 'CCK', // Cocos (Keeling) Islands
            'CO' => 'COL', // Colombia
            'KM' => 'COM', // Comoros
            'CG' => 'COG', // Congo (Brazzaville)
            'CD' => 'COD', // Congo (Kinshasa)
            'CK' => 'COK', // Cook Islands
            'CR' => 'CRI', // Costa Rica
            'CI' => 'CIV', // Côte d'Ivoire
            'HR' => 'HRV', // Croatia
            'CU' => 'CUB', // Cuba
            'CW' => 'CUW', // Curaçao
            'CY' => 'CYP', // Cyprus
            'CZ' => 'CZE', // Czech Republic
            'DK' => 'DNK', // Denmark
            'DJ' => 'DJI', // Djibouti
            'DM' => 'DMA', // Dominica
            'DO' => 'DOM', // Dominican Republic
            'EC' => 'ECU', // Ecuador
            'EG' => 'EGY', // Egypt
            'SV' => 'SLV', // El Salvador
            'GQ' => 'GNQ', // Equatorial Guinea
            'ER' => 'ERI', // Eritrea
            'EE' => 'EST', // Estonia
            'ET' => 'ETH', // Ethiopia
            'FK' => 'FLK', // Falkland Islands
            'FO' => 'FRO', // Faroe Islands
            'FJ' => 'FJI', // Fiji
            'FI' => 'FIN', // Finland
            'FR' => 'FRA', // France
            'GF' => 'GUF', // French Guiana
            'PF' => 'PYF', // French Polynesia
            'TF' => 'ATF', // French Southern Lands
            'GA' => 'GAB', // Gabon
            'GM' => 'GMB', // Gambia
            'GE' => 'GEO', // Georgia
            'DE' => 'DEU', // Germany
            'GH' => 'GHA', // Ghana
            'GI' => 'GIB', // Gibraltar
            'GR' => 'GRC', // Greece
            'GL' => 'GRL', // Greenland
            'GD' => 'GRD', // Grenada
            'GP' => 'GLP', // Guadeloupe
            'GU' => 'GUM', // Guam
            'GT' => 'GTM', // Guatemala
            'GG' => 'GGY', // Guernsey
            'GN' => 'GIN', // Guinea
            'GW' => 'GNB', // Guinea-Bissau
            'GY' => 'GUY', // Guyana
            'HT' => 'HTI', // Haiti
            'HM' => 'HMD', // Heard and McDonald Islands
            'HN' => 'HND', // Honduras
            'HK' => 'HKG', // Hong Kong
            'HU' => 'HUN', // Hungary
            'IS' => 'ISL', // Iceland
            'IN' => 'IND', // India
            'ID' => 'IDN', // Indonesia
            'IR' => 'IRN', // Iran
            'IQ' => 'IRQ', // Iraq
            'IE' => 'IRL', // Ireland
            'IM' => 'IMN', // Isle of Man
            'IL' => 'ISR', // Israel
            'IT' => 'ITA', // Italy
            'JM' => 'JAM', // Jamaica
            'JP' => 'JPN', // Japan
            'JE' => 'JEY', // Jersey
            'JO' => 'JOR', // Jordan
            'KZ' => 'KAZ', // Kazakhstan
            'KE' => 'KEN', // Kenya
            'KI' => 'KIR', // Kiribati
            'KP' => 'PRK', // Korea, North
            'KR' => 'KOR', // Korea, South
            'KW' => 'KWT', // Kuwait
            'KG' => 'KGZ', // Kyrgyzstan
            'LA' => 'LAO', // Laos
            'LV' => 'LVA', // Latvia
            'LB' => 'LBN', // Lebanon
            'LS' => 'LSO', // Lesotho
            'LR' => 'LBR', // Liberia
            'LY' => 'LBY', // Libya
            'LI' => 'LIE', // Liechtenstein
            'LT' => 'LTU', // Lithuania
            'LU' => 'LUX', // Luxembourg
            'MO' => 'MAC', // Macau
            'MK' => 'MKD', // Macedonia
            'MG' => 'MDG', // Madagascar
            'MW' => 'MWI', // Malawi
            'MY' => 'MYS', // Malaysia
            'MV' => 'MDV', // Maldives
            'ML' => 'MLI', // Mali
            'MT' => 'MLT', // Malta
            'MH' => 'MHL', // Marshall Islands
            'MQ' => 'MTQ', // Martinique
            'MR' => 'MRT', // Mauritania
            'MU' => 'MUS', // Mauritius
            'YT' => 'MYT', // Mayotte
            'MX' => 'MEX', // Mexico
            'FM' => 'FSM', // Micronesia
            'MD' => 'MDA', // Moldova
            'MC' => 'MCO', // Monaco
            'MN' => 'MNG', // Mongolia
            'ME' => 'MNE', // Montenegro
            'MS' => 'MSR', // Montserrat
            'MA' => 'MAR', // Morocco
            'MZ' => 'MOZ', // Mozambique
            'MM' => 'MMR', // Myanmar
            'NA' => 'NAM', // Namibia
            'NR' => 'NRU', // Nauru
            'NP' => 'NPL', // Nepal
            'NL' => 'NLD', // Netherlands
            'NC' => 'NCL', // New Caledonia
            'NZ' => 'NZL', // New Zealand
            'NI' => 'NIC', // Nicaragua
            'NE' => 'NER', // Niger
            'NG' => 'NGA', // Nigeria
            'NU' => 'NIU', // Niue
            'NF' => 'NFK', // Norfolk Island
            'MP' => 'MNP', // Northern Mariana Islands
            'NO' => 'NOR', // Norway
            'OM' => 'OMN', // Oman
            'PK' => 'PAK', // Pakistan
            'PW' => 'PLW', // Palau
            'PS' => 'PSE', // Palestine
            'PA' => 'PAN', // Panama
            'PG' => 'PNG', // Papua New Guinea
            'PY' => 'PRY', // Paraguay
            'PE' => 'PER', // Peru
            'PH' => 'PHL', // Philippines
            'PN' => 'PCN', // Pitcairn
            'PL' => 'POL', // Poland
            'PT' => 'PRT', // Portugal
            'PR' => 'PRI', // Puerto Rico
            'QA' => 'QAT', // Qatar
            'RE' => 'REU', // Reunion
            'RO' => 'ROU', // Romania
            'RU' => 'RUS', // Russian Federation
            'RW' => 'RWA', // Rwanda
            'BL' => 'BLM', // Saint Barthélemy
            'SH' => 'SHN', // Saint Helena
            'KN' => 'KNA', // Saint Kitts and Nevis
            'LC' => 'LCA', // Saint Lucia
            'MF' => 'MAF', // Saint Martin (French part)
            'PM' => 'SPM', // Saint Pierre and Miquelon
            'VC' => 'VCT', // Saint Vincent and the Grenadines
            'WS' => 'WSM', // Samoa
            'SM' => 'SMR', // San Marino
            'ST' => 'STP', // Sao Tome and Principe
            'SA' => 'SAU', // Saudi Arabia
            'SN' => 'SEN', // Senegal
            'RS' => 'SRB', // Serbia
            'SC' => 'SYC', // Seychelles
            'SL' => 'SLE', // Sierra Leone
            'SG' => 'SGP', // Singapore
            'SX' => 'SXM', // Sint Maarten
            'SK' => 'SVK', // Slovakia
            'SI' => 'SVN', // Slovenia
            'SB' => 'SLB', // Solomon Islands
            'SO' => 'SOM', // Somalia
            'ZA' => 'ZAF', // South Africa
            'GS' => 'SGS', // South Georgia and South Sandwich Islands
            'SS' => 'SSD', // South Sudan
            'ES' => 'ESP', // Spain
            'LK' => 'LKA', // Sri Lanka
            'SD' => 'SDN', // Sudan
            'SR' => 'SUR', // Suriname
            'SJ' => 'SJM', // Svalbard and Jan Mayen Islands
            'SZ' => 'SWZ', // Swaziland
            'SE' => 'SWE', // Sweden
            'CH' => 'CHE', // Switzerland
            'SY' => 'SYR', // Syria
            'TW' => 'TWN', // Taiwan
            'TJ' => 'TJK', // Tajikistan
            'TZ' => 'TZA', // Tanzania
            'TH' => 'THA', // Thailand
            'TL' => 'TLS', // Timor-Leste
            'TG' => 'TGO', // Togo
            'TK' => 'TKL', // Tokelau
            'TO' => 'TON', // Tonga
            'TT' => 'TTO', // Trinidad and Tobago
            'TN' => 'TUN', // Tunisia
            'TR' => 'TUR', // Turkey
            'TM' => 'TKM', // Turkmenistan
            'TC' => 'TCA', // Turks and Caicos Islands
            'TV' => 'TUV', // Tuvalu
            'UG' => 'UGA', // Uganda
            'UA' => 'UKR', // Ukraine
            'AE' => 'ARE', // United Arab Emirates
            'GB' => 'GBR', // United Kingdom
            'UM' => 'UMI', // United States Minor Outlying Islands
            'US' => 'USA', // United States of America
            'UY' => 'URY', // Uruguay
            'UZ' => 'UZB', // Uzbekistan
            'VU' => 'VUT', // Vanuatu
            'VA' => 'VAT', // Vatican City
            'VE' => 'VEN', // Venezuela
            'VN' => 'VNM', // Vietnam
            'VG' => 'VGB', // Virgin Islands, British
            'VI' => 'VIR', // Virgin Islands, U.S.
            'WF' => 'WLF', // Wallis and Futuna Islands
            'EH' => 'ESH', // Western Sahara
            'YE' => 'YEM', // Yemen
            'ZM' => 'ZMB', // Zambia
            'ZW' => 'ZWE', // Zimbabwe
        ];
    }
}