<?php

define('PRJ_DIR', __DIR__.'/../../');

define('MAINPAGE_URL', 'http://www.kiabi.ru/');
define('FEED_URL', 'http://www.kiabi.com/googleshopping_RU_ru.xml');
define('TIMEFILE_PATH', __DIR__.'/../../files/time.txt');
define('MAINPAGE_PATH', __DIR__.'/../../files/mainpage.html');
define('CATEGORIES_PATH', __DIR__.'/../../files/categories.xml');
define('YANDEX_CATEGORIES_PATH', __DIR__.'/../../files/categories_yandex.json');
define('YANDEX_COLORS_PATH', __DIR__.'/../../files/colors_yandex.json');
define('CP_CATEGORIES_PATH', __DIR__.'/../../files/channel_pilot_categories.xml');

define('GOOGLE_CATEGORIES_JSON_PATH', __DIR__.'/../../files/categories_google.json');
define('GOOGLE_CATEGORIES_XSLX_PATH', __DIR__.'/../../files/categories_google.xlsx');
define('GOOGLE_TAXONOMY_PATH', __DIR__.'/../../public/files/taxonomy_google.xls');

define('FEED_YANDEX_PATH', __DIR__.'/../../files/feed_original_for_yandex.xml');
define('FEED_GOOGLE_PATH', __DIR__.'/../../files/feed_original_for_google.xml');

define('FEED_CONVERTED_YANDEX_PATH', __DIR__.'/../../files/feed_converted_yandex.xml');
define('FEED_CONVERTED_YANDEX0_PATH', __DIR__.'/../../files/feed_converted_yandex0.xml');
define('FEED_CONVERTED_AVITO_PATH', __DIR__.'/../../files/feed_converted_avito.xml');
define('FEED_CONVERTED_PRICE_PATH', __DIR__.'/../../files/feed_converted_price.xml');
define('FEED_CONVERTED_PUSH4SITE_PATH', __DIR__.'/../../files/feed_converted_push4site.xml');
define('FEED_CONVERTED_MAIL_PATH', __DIR__.'/../../files/feed_converted_mail.xml');
define('FEED_CONVERTED_GOOGLE_PATH', __DIR__.'/../../files/feed_converted_google.xml');
define('FEED_CONVERTED_CRITEO_PATH', __DIR__.'/../../files/feed_converted_criteo.xml');

define('STORE_TITLE', 'Kiabi');
define('COMPANY_TITLE', 'Kiabi Europe SAS');
define('STORE_URL', 'http://www.kiabi.ru');

define('LINK_COUNTER_APPENDIX', '#'.urlencode('&ns_mchannel=cpc&ns_source=yandexmarket&ns_campaign=SHOP&utm_medium=cpc&utm_source=yandexmarket&utm_campaign=SHOP&utm_content={offer_id}_{product_id}'));
define('LINK_COUNTER_APPENDIX_AVITO', '#'.urlencode('&ns_mchannel=cpc&ns_source=Avito_context&ns_campaign={campaignid}&utm_source=AvitoContext&utm_medium=cpc&utm_campaign={campaignid}&utm_term={platform}&utm_content={id}_{pos}'));
define('LINK_COUNTER_APPENDIX_PRICE', '#'.urlencode('&ns_mchannel=cpc&ns_source=priceru&ns_campaign={campaign_id}&utm_medium=cpc&utm_source=priceru&utm_campaign={campaign_id}'));

define('LINK_COUNTER_APPENDIX_GOOGLE', '#'.urlencode('&ns_mchannel=cpc&ns_source=google&ns_campaign=SHOP&utm_medium=cpc&utm_source=google&utm_campaign={campaignid}&utm_content={adtype}{adgroupid}{product_channel}{productid}{product_partition_id}'));

define('DB_HOST', 'localhost');
define('DB_BASE', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_TYPE', 'pdo_mysql');

define('CACHE_DRIVER', 'file'); // file / memcached / redis
define('CACHE_HOST', 'localhost');
define('CACHE_PORT', 0);   // 0 / 11211 / 6379