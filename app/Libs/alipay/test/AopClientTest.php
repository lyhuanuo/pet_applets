<?php

require_once '../AopClient.php';
require_once '../AopCertification.php';
require_once '../request/AlipayTradeQueryRequest.php';
require_once '../request/AlipayTradeWapPayRequest.php';
require_once '../request/AlipayTradeAppPayRequest.php';


/**
 * 证书类型AopClient功能方法使用测试
 * 1、execute 调用示例
 * 2、sdkExecute 调用示例
 * 3、pageExecute 调用示例
 */


//1、execute 使用
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2021001192645285';
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAz7B53dZRZQiTQQ6Ci3d15+LDEbA/95TaZhrm7hLjvFXOTeAcNy7FEJDV21zmdxRaH/60h2j+M8ipgNUMGx1dsWB5OPFQ/zpsY0Z2xre59S5JnGyYXps2m0fsRGbjxIrGzskHfJmacUZPqSzv72dhzrp+xP8IjFodegxJZSmpSzMigUZcSszEjspsoQI7wwVUxhMLaXSQilN29vs4HSIzwSwUTSCKKQx47xMmZyJXriOb3m7PMFMXq/+0u6aTO4gUOLG/gHDEgE3b1qgy5Ji+lPmIVoAFeN04udEb1SxcmEDKmCB8wMjeBSaL31SfY7bygMa4alcSQW5j2YRTgkI5NQIDAQABAoIBAQCwTZ3V/AXSX2opUrjszXbwZqWzEUvrzpdrD7EzLiPWj3x2zSUGjxRIULrJ4V3efg9Xk68KN816hf+l1rTVG6OaXEvrU6avUpgJ2hof7HzWLfnc6K6buOStAmwFUNZO1IOZrDtHwVjCia477WKsdrUneVb9wYUvJ5xYK6/uSWRl6TTomRYz9urg1eY0J3GXQKA2B7c2w24DumdWMjKlRpO4aYgV0HUnN9I74ZkrwVW+Fw5zmI6YTF5EmEaZDtf15Hw1yiLr/jN7BzuA8M0m1JoT4z1k9LQldG6qAri2OY/Bs8IWXn9uhovCaZRj0niRMGtCfbtmEl3KLAXT7boOyLMtAoGBAPjD2XZ+T3tpWyAYptnPrg+eA+7dZps9mMehaD8gcKQBd6GWz4Gf7HMX1Goe5rHnrfSuEtdzxa+A7RnD5FgWlmeuHGXp/+bx9uKinSZMCONozKC3OVHEBETqbhBzhX7pZ6B76BOdp0ofNwyt6uEEds4jEPguMRNi+y4vR9aTygQLAoGBANW6zXCYnqlobiX8I5p2ye4pFeSj5aBXBWQu2poDRdj+CWsusQb0RetdWY7RHl63MUQ0r0h8Xp/kxGQs2Yrmc9RQ8T2GzwjjWcqxu+t/mXb0/LTbSPX8qDrxqGC5Avw0QCA9P3KK8pgfZNtJjIxZjJvwTFC3aFZXtRAbaOOGr7+/AoGASXPSxykeoOsH7B87TP8EfzoCIUqgXGBAt1lSZalXeSWxxDW6iaTF9Wno33jGV2t4MtIfYBygF2UzHTN5PgVVXcHMQs9oLzHs2xh23HMBANzV5vKS6zC8rfimjRb/KO0UGw/bA8e+OKWk5IqJ2u0tg0pZXV6OQRIt5oIxbQtwT+cCgYEAxScp9yFxykegw71Qgl3ta5nOrjhZy4w6Vu25jLRULBQ6T/qkJgwSq0hTIqDYaPqHoakPv8ep6ci3OMj+a1ilpEZ+IglG1aYS4B0Pge8Ue10F9zcImJDWBnmAnqhLjqYMOTEGY/y0R4s+F+JCmfzI7de+Lf7hajRBu3ftS2SknjUCgYB0z4jOJv0Nk1uXHMBkZLA1OQaU7FWeROmda3tMCP3b58Fo2KBURzDgxKWNg61WoyOAEbh++jW6uWgTjjBre/tjoDTX46sWCcjLyYc5IgQDW6pYmbA7Tn1+V1lxs8b2IAjOI0MRL55KSVIQZ2hMNcUI+hE+JRXj37P1nM1Srh6bZw==';
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjFt/ONWr8523KqWzI9E99kqx6EayJ4huaKXbcm5i/xc12ufyx3h7LUovjuRdHgUiLbhP6PyjFGC6+tZNcYmoRfwN4SkCDqXh0p8AtvcrjiwlPFZKEzXBCGZkodi/zSuwA/9/2NNGi3Q3l/tDvJpxGd8bvzDnyXt/A1mb0viKZDXjiQsTMSIE5ONHja1QxqZ+9vxxSrOW1PAlmDXTETK6EC5MmWWVkVSziFoaqgErvK3mujkX+gWc/tD6/Y4AdSkkl7qBP23PCSvbNUXFoh6rs8oaHCwsD7LpWJTt3zMupr1KnCNMKliH1VJ0RJHS32JA32zmS3GOPvMGOg2CP8bDuQIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeQueryRequest ();
$request->setBizContent("{" .
    "\"out_trade_no\":\"20150320010101001\"," .
    "\"trade_no\":\"2014112611001004680 073956707\"," .
    "\"org_pid\":\"2088101117952222\"," .
    "      \"query_options\":[" .
    "        \"TRADE_SETTE_INFO\"" .
    "      ]" .
    "  }");
$result = $aop->execute($request);
var_dump($result);


//2、sdkExecute 测试
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '你的appid';
$aop->rsaPrivateKey = '你的应用私钥';
$aop->alipayrsaPublicKey = '你的支付宝公钥';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeAppPayRequest ();
$request->setBizContent("{" .
    "\"timeout_express\":\"90m\"," .
    "\"total_amount\":\"9.00\"," .
    "\"product_code\":\"QUICK_MSECURITY_PAY\"," .
    "\"body\":\"Iphone6 16G\"," .
    "\"subject\":\"大乐透\"," .
    "\"out_trade_no\":\"70501111111S001111119\"," .
    "\"time_expire\":\"2016-12-31 10:05\"," .
    "\"goods_type\":\"0\"," .
    "\"promo_params\":\"{\\\"storeIdType\\\":\\\"1\\\"}\"," .
    "\"passback_params\":\"merchantBizType%3d3C%26merchantBizNo%3d2016010101111\"," .
    "\"extend_params\":{" .
    "\"sys_service_provider_id\":\"2088511833207846\"," .
    "\"hb_fq_num\":\"3\"," .
    "\"hb_fq_seller_percent\":\"100\"," .
    "\"industry_reflux_info\":\"{\\\\\\\"scene_code\\\\\\\":\\\\\\\"metro_tradeorder\\\\\\\",\\\\\\\"channel\\\\\\\":\\\\\\\"xxxx\\\\\\\",\\\\\\\"scene_data\\\\\\\":{\\\\\\\"asset_name\\\\\\\":\\\\\\\"ALIPAY\\\\\\\"}}\"," .
    "\"card_type\":\"S0JP0000\"" .
    "    }," .
    "\"merchant_order_no\":\"20161008001\"," .
    "\"enable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
    "\"store_id\":\"NJ_001\"," .
    "\"specified_channel\":\"pcredit\"," .
    "\"disable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
    "      \"goods_detail\":[{" .
    "        \"goods_id\":\"apple-01\"," .
    "\"alipay_goods_id\":\"20010001\"," .
    "\"goods_name\":\"ipad\"," .
    "\"quantity\":1," .
    "\"price\":2000," .
    "\"goods_category\":\"34543238\"," .
    "\"categories_tree\":\"124868003|126232002|126252004\"," .
    "\"body\":\"特价手机\"," .
    "\"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
    "        }]," .
    "\"ext_user_info\":{" .
    "\"name\":\"李明\"," .
    "\"mobile\":\"16587658765\"," .
    "\"cert_type\":\"IDENTITY_CARD\"," .
    "\"cert_no\":\"362334768769238881\"," .
    "\"min_age\":\"18\"," .
    "\"fix_buyer\":\"F\"," .
    "\"need_check_info\":\"F\"" .
    "    }," .
    "\"business_params\":\"{\\\"data\\\":\\\"123\\\"}\"," .
    "\"agreement_sign_params\":{" .
    "\"personal_product_code\":\"CYCLE_PAY_AUTH_P\"," .
    "\"sign_scene\":\"INDUSTRY|DIGITAL_MEDIA\"," .
    "\"external_agreement_no\":\"test20190701\"," .
    "\"external_logon_id\":\"13852852877\"," .
    "\"access_params\":{" .
    "\"channel\":\"ALIPAYAPP\"" .
    "      }," .
    "\"sub_merchant\":{" .
    "\"sub_merchant_id\":\"2088123412341234\"," .
    "\"sub_merchant_name\":\"滴滴出行\"," .
    "\"sub_merchant_service_name\":\"滴滴出行免密支付\"," .
    "\"sub_merchant_service_description\":\"免密付车费，单次最高500\"" .
    "      }," .
    "\"period_rule_params\":{" .
    "\"period_type\":\"DAY\"," .
    "\"period\":3," .
    "\"execute_time\":\"2019-01-23\"," .
    "\"single_amount\":10.99," .
    "\"total_amount\":600," .
    "\"total_payments\":12" .
    "      }" .
    "    }" .
    "  }");
$result = $aop->sdkExecute($request);

$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
echo $responseNode;
$resultCode = $result->$responseNode->code;
if (!empty($resultCode) && $resultCode == 10000) {
    echo "成功";
} else {
    echo "失败";
}


//3、pageExecute 测试
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '你的appid';
$aop->rsaPrivateKey = '你的应用私钥';
$aop->alipayrsaPublicKey = '你的支付宝公钥';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeWapPayRequest ();
$request->setBizContent("{" .
    "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," .
    "    \"subject\":\"测试\"," .
    "    \"out_trade_no\":\"70501111111S001111119\"," .
    "    \"timeout_express\":\"90m\"," .
    "    \"total_amount\":9.00," .
    "    \"product_code\":\"QUICK_WAP_WAY\"" .
    "  }");
$result = $aop->pageExecute($request);
echo $result;


