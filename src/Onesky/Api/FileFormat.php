<?php

namespace Onesky\Api;

/**
 * Class FileType
 */
class FileFormat
{
    const IOS_STRINGS = 'IOS_STRINGS';
    const IOS_STRINGSDICT_XML = 'IOS_STRINGSDICT_XML';
    const GNU_PO = 'GNU_PO';
    const ANDROID_XML = 'ANDROID_XML';
    const ANDROID_JSON = 'ANDROID_JSON';
    const JAVA_PROPERTIES = 'JAVA_PROPERTIES';
    const RUBY_YML = 'RUBY_YML';
    const RUBY_YAML = 'RUBY_YAML';
    const FLASH_XML = 'FLASH_XML';
    const GNU_POT = 'GNU_POT';
    const RRC = 'RRC';
    const RESX = 'RESX';
    const RESJSON = 'RESJSON';
    const HIERARCHICAL_JSON = 'HIERARCHICAL_JSON';
    const PHP = 'PHP';
    const PHP_VARIABLES = 'PHP_VARIABLES';
    const HTML = 'HTML';
    const RESW = 'RESW';
    const YML = 'YML';
    const YAML = 'YAML';
    const ADEMPIERE_XML = 'ADEMPIERE_XML';
    const IDEMPIERE_XML = 'IDEMPIERE_XML';
    const QT_TS_XML = 'QT_TS_XML';
    const TMX = 'TMX';
    const L10N = 'L10N';
    const INI = 'INI';
    const REQUIREJS = 'REQUIREJS';

    /**
     * @var array
     */
    protected $supportedFormats = array(
        self::IOS_STRINGS,
        self::IOS_STRINGSDICT_XML,
        self::GNU_PO,
        self::ANDROID_XML,
        self::ANDROID_JSON,
        self::JAVA_PROPERTIES,
        self::RUBY_YML,
        self::RUBY_YAML,
        self::FLASH_XML,
        self::GNU_POT,
        self::RRC,
        self::RESX,
        self::RESJSON,
        self::HIERARCHICAL_JSON,
        self::PHP,
        self::PHP_VARIABLES,
        self::HTML,
        self::RESW,
        self::YML,
        self::YAML,
        self::ADEMPIERE_XML,
        self::IDEMPIERE_XML,
        self::QT_TS_XML,
        self::TMX,
        self::L10N,
        self::INI,
        self::REQUIREJS
    );
}