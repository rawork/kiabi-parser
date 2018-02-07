<?php

define('PRJ_DIR', __DIR__.'/../../');

define('STORE_TITLE', 'Kiabi');
define('COMPANY_TITLE', 'Kiabi Europe SAS');
define('STORE_URL', 'https://www.kiabi.ru');
define('FEED_URL', 'https://www.kiabi.ru/googleshopping_RU_ru.xml');
define('TIMEFILE_PATH', __DIR__.'/../../files/time.txt');
define('YANDEX_CATEGORIES_PATH', __DIR__.'/../../files/categories_yandex.json');
define('YANDEX_COLORS_PATH', __DIR__.'/../../files/colors_yandex.json');

define('RBF_GOODS_PATH', __DIR__.'/../../files/rbf_goods.json');
define('RBF_GOODS_RAW_PATH', __DIR__.'/../../files/rbf_goods_raw.json');

define('GOOGLE_CATEGORIES_JSON_PATH', __DIR__.'/../../files/categories_google.json');
define('GOOGLE_CATEGORIES_XSLX_PATH', __DIR__.'/../../files/categories_google.xlsx');
define('GOOGLE_TAXONOMY_PATH', __DIR__.'/../../public/files/taxonomy_google.xls');

define('FEED_ORIGINAL_PATH', __DIR__.'/../../files/feed_original.xml');
define('FEED_ARCHIVE_PATH', __DIR__.'/../../files/archive/');

define('FEED_CONVERTED_YANDEX_PATH', __DIR__.'/../../files/feed_converted_yandex.xml');
define('FEED_CONVERTED_RBF_PATH', __DIR__.'/../../files/feed_converted_rbf.xml');
define('FEED_CONVERTED_RBF2_PATH', __DIR__.'/../../files/feed_converted_rbf2.xml');
define('FEED_CONVERTED_YANDEX0_PATH', __DIR__.'/../../files/feed_converted_yandex0.xml');
define('FEED_CONVERTED_YANDEX0_MAIL_PATH', __DIR__.'/../../files/feed_converted_yandex0_mail.xml');
define('FEED_CONVERTED_YANDEX_SMARTBANNER_PATH', __DIR__.'/../../files/feed_converted_yandex_smartbanner.xml');
define('FEED_CONVERTED_YANDEX_SMARTBANNER2_PATH', __DIR__.'/../../files/feed_converted_yandex_smartbanner2.xml');
define('FEED_CONVERTED_YANDEX_REMARKETING_PATH', __DIR__.'/../../files/feed_converted_yandex_remarketing.xml');
define('FEED_CONVERTED_YANDEX_VK_PATH', __DIR__.'/../../files/feed_converted_yandex_vk.xml');
define('FEED_CONVERTED_AVITO_PATH', __DIR__.'/../../files/feed_converted_avito.xml');
define('FEED_CONVERTED_PRICE_PATH', __DIR__.'/../../files/feed_converted_price.xml');
define('FEED_CONVERTED_PUSH4SITE_PATH', __DIR__.'/../../files/feed_converted_push4site.xml');
define('FEED_CONVERTED_MAIL_PATH', __DIR__.'/../../files/feed_converted_mail.xml');
define('FEED_CONVERTED_GOOGLE_PATH', __DIR__.'/../../files/feed_converted_google.xml');
define('FEED_CONVERTED_GOOGLE2_PATH', __DIR__.'/../../files/feed_converted_google2.xml');
define('FEED_CONVERTED_CRITEO_PATH', __DIR__.'/../../files/feed_converted_criteo.xml');
define('FEED_CONVERTED_FACEBOOK_PATH', __DIR__.'/../../files/feed_converted_facebook.xml');

define('LINK_COUNTER_APPENDIX_AVITO', '#'.htmlspecialchars('&utm_source=avito&utm_medium=cpc&utm_campaign={campaign_id}&utm_content={id}_{platform}_{pos}&ns_mchannel=display&ns_source=avito&ns_campaign=pma5'));
define('LINK_COUNTER_APPENDIX_PRICE', '#'.htmlspecialchars('#&ns_mchannel=cpc&ns_source=priceru&ns_campaign={campaign_id}&utm_medium=cpc&utm_source=priceru&utm_campaign={campaign_id}'));
define('LINK_COUNTER_APPENDIX_GOOGLE', '');
define('LINK_COUNTER_APPENDIX_GOOGLE2', htmlspecialchars('#&utm_source=google&utm_medium=cpc&utm_campaign={campaignid}-Din-rem-v2&fversion=v1'));
define('LINK_COUNTER_APPENDIX_FACEBOOK', htmlspecialchars('#&utm_source=facebook&utm_medium=cpc&utm_campaign={reference_id}'));
define('LINK_COUNTER_APPENDIX_GOOGLE_MOBILE', '#'.htmlspecialchars('#&ns_mchannel=sea&ns_source=google&ns_campaign=pla&utm_medium=cpc&utm_source=google&utm_campaign=MOBILKA&utm_content={adgroupid}'));
define('LINK_COUNTER_APPENDIX_YANDEX', '#'.htmlspecialchars('&utm_source=YandexMarket&utm_medium=cpc'));
define('LINK_COUNTER_APPENDIX_RBF', '');
define('LINK_COUNTER_APPENDIX_YANDEX_SMARTBANNER', '#'.htmlspecialchars('&utm_source=yandex&utm_medium=cpc&utm_campaign=Smart_Banner&utm_content={ad_id}&utm_term={keyword}_{source}&ns_mchannel=cpc&ns_source=yandex&ns_campaign=hbrand'));
define('LINK_COUNTER_APPENDIX_YANDEX_SMARTBANNER2', htmlspecialchars('#&utm_medium=cpc&utm_source=yandex&utm_campaign=30521571-dsa-smart-banner2&fversion=v3'));
define('LINK_COUNTER_APPENDIX_YANDEX_REMARKETING', '#'.htmlspecialchars('&utm_source=yandex&utm_medium=cpc&utm_campaign=Din_remarketing&utm_content={ad_id}&utm_term={adtarget_id}_{adtarget_name}&ns_mchannel=cpc&ns_source=yandex&ns_campaign=hbrand'));
define('LINK_COUNTER_APPENDIX_YANDEX_VK', '?utm_source=vk&amp;utm_medium=retargeting&amp;utm_campaign={campaign_id}&amp;utm_content={offer_id}#&amp;ad_id={ad_id}&amp;client_id={client_id}&amp;price_list_id={price_list_id}&amp;product_id={product_id}&amp;platform={platform}');
define('LINK_COUNTER_APPENDIX_YANDEX0_MAIL', '?#'.htmlspecialchars('&utm_source=mytarget&utm_medium=cpc&utm_campaign=dinremarketing&utm_content={{campaign_name}}'));

define('DB_HOST', 'localhost');
define('DB_BASE', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_TYPE', 'pdo_mysql');

define('CACHE_DRIVER', 'file'); // file / memcached / redis
define('CACHE_HOST', 'localhost');
define('CACHE_PORT', 0);   // 0 / 11211 / 6379