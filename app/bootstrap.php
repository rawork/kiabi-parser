<?php

$loader = require __DIR__.'/../vendor/autoload.php';

define('MAINPAGE_URL', 'http://www.kiabi.ru/');
define('FEED_URL', 'http://www.kiabi.com/googleshopping_RU_ru.xml');
define('TIMEFILE_PATH', __DIR__.'/../files/time.txt');
define('MAINPAGE_PATH', __DIR__.'/../files/mainpage.html');
define('CATEGORIES_PATH', __DIR__.'/../files/categories.xml');
define('YANDEX_CATEGORIES_PATH', __DIR__.'/../files/categories_yandex.json');
define('YANDEX_COLORS_PATH', __DIR__.'/../files/colors_yandex.json');
define('CP_CATEGORIES_PATH', __DIR__.'/../files/channel_pilot_categories.xml');

define('GOOGLE_CATEGORIES_JSON_PATH', __DIR__.'/../files/categories_google.json');
define('GOOGLE_CATEGORIES_XSLX_PATH', __DIR__.'/../files/categories_google.xlsx');
define('GOOGLE_TAXONOMY_PATH', __DIR__.'/../public/files/taxonomy_google.xls');

define('FEED_YANDEX_PATH', __DIR__.'/../files/feed_original_for_yandex.xml');
define('FEED_GOOGLE_PATH', __DIR__.'/../files/feed_original_for_google.xml');

define('FEED_CONVERTED_YANDEX_PATH', __DIR__.'/../files/feed_converted_yandex.xml');
define('FEED_CONVERTED_AVITO_PATH', __DIR__.'/../files/feed_converted_avito.xml');
define('FEED_CONVERTED_GOOGLE_PATH', __DIR__.'/../files/feed_converted_google.xml');

define('STORE_TITLE', 'Kiabi');
define('COMPANY_TITLE', 'Kiabi Europe SAS');
define('STORE_URL', 'http://www.kiabi.ru');

define('LINK_COUNTER_APPENDIX', '#'.urlencode('&ns_mchannel=cpc&ns_source=yandexmarket&ns_campaign={campaign_id}&utm_medium=cpc&utm_source=yandexmarket&utm_campaign={campaign_id}'));
define('LINK_COUNTER_APPENDIX_AVITO', '#'.urlencode('utm_source=avito&utm_medium=cpc&utm_campaign={campaign_id}&utm_content={id}%3A%20{platform}%3A%20{pos}&ns_mchannel=display&ns_source=avito&ns_campaign=pma5'));